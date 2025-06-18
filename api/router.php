<?php
class RESTRouter {
    private $routes = [];
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $db_name = 'mybd';

    public function __construct() {
        // endpoints для запросов
        $this->addRoute('GET', '/api/router.php/rooms', [$this, 'GetHotelRooms']);
        $this->addRoute('POST', '/api/router.php/roomReservation', [$this, 'PostHotelBooking']);
        $this->addRoute('GET','/api/router.php/sendBot', [$this,'SendBot']);
        $this->addRoute('GET', '/api/router.php/contacts', [$this,'GetContacts']);
        $this->addRoute('POST', '/api/router.php/leaveReview', [$this, 'PostReview']);
    }

    private function addRoute($method, $route, $callback) {
        $this->routes[$method][$route] = $callback;
    }

    // Метод для обработки входящего запроса
    public function handleRequest() {      
        $method = $_SERVER['REQUEST_METHOD'];
        
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method][$uri])) {

            http_response_code(404);
            echo " '$method' Маршрут '$uri' не найден! 404";
            exit;
        }

        $requestData = [
            'method' => $method,
            'uri' => $uri,
            'get_params' => $_GET
        ];
        
        return ($this->routes[$method][$uri])($requestData);
    }

    private function GetHotelRooms() {
        $table_name = 'HotelRooms';
        $link = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );
        
        if ($link === false) {
            echo ("Ошибка подключения: ".mysqli_connect_error());
            exit;
        }
        
        mysqli_set_charset($link, "utf8");
        
        try {
            // Получаем параметры фильтрации и сортировки из GET-запроса
            $filters = $_GET;
            
            // Базовый SQL-запрос
            $sql = "SELECT * FROM {$table_name}";
            $params = [];
            
            // Если есть параметры фильтрации, добавляем WHERE
            if (!empty($filters)) {
                $sql .= " WHERE 1=1";
                
                // Фильтр по имени
                if (isset($filters['room_type'])) {
                    $sql .= " AND room_type LIKE ?";
                    $params[] = "%{$filters['room_type']}%";
                }
                
                // Фильтр по цене (диапазон)
                if (isset($filters['price'])) {
                    list($minPrice, $maxPrice) = explode('-', $filters['price']);
                    $sql .= " AND price BETWEEN ? AND ?";
                    $params[] = $minPrice;
                    $params[] = $maxPrice;
                }
                
                // Фильтр по количеству спальных мест
                if (isset($filters['capacity'])) {
                    $sql .= " AND capacity = ?";
                    $params[] = $filters['capacity'];
                }
            }
            
            // Добавляем сортировку
            $sortField = isset($filters['sort']) ? $filters['sort'] : 'room_type';
            $sortOrder = isset($filters['order']) ? $filters['order'] : 'asc';
            
            // Проверяем корректность параметров сортировки
            $validSortFields = ['room_type', 'price', 'capacity'];
            if (!in_array($sortField, $validSortFields)) {
                $sortField = 'room_type';
            }
            
            $validSortOrders = ['asc', 'desc'];
            if (!in_array($sortOrder, $validSortOrders)) {
                $sortOrder = 'asc';
            }
            
            $sql .= " ORDER BY {$sortField} {$sortOrder}";
            
            // Подготовка и выполнение запроса
            $stmt = mysqli_prepare($link, $sql);
            
            if (!empty($params)) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            mysqli_close($link);
            
            $this->ExportResponse($rows);
            exit;
        } catch (Exception $e) {
            $this->ExportErorr(500, 'Ошибка при получении списка номеров');
            exit;
        }
    }

    private function PostHotelBooking($data) {
        //echo "PostHotelBooking вызван<br>";
        $id = isset($data['get_params']['id']) ? $data['get_params']['id'] : null;
        $fio = isset($data['get_params']['fio']) ? $data['get_params']['fio'] : null;
        $booking_start = isset($data['get_params']['booking_start']) ? $data['get_params']['booking_start'] : null;
        $booking_end = isset($data['get_params']['booking_end']) ? $data['get_params']['booking_end'] : null;
        
        // Проверка подключения к БД
        $link = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );
        
        if ($link === false) {
            echo ("Ошибка подключения: ".mysqli_connect_error());
            exit;
        }
        
        mysqli_set_charset($link, "utf8");
        
        try 
        {
            // Проверяем доступность номера на запрошенный период
            
            $checkAvailability = mysqli_query($link, 
            "SELECT COUNT(*) as count 
            FROM Bookings 
            WHERE room_id = $id AND 
            ((booking_start <= '$booking_start' AND booking_end >= '$booking_end') OR
            (booking_start <= '$booking_end' AND booking_end >= '$booking_start'))", 
            );

            if (!$checkAvailability) {
                throw new Exception(mysqli_error($link));
            }
            $availability = mysqli_fetch_assoc($checkAvailability);

            // Если номер занят в запрошенный период
            if ($availability['count'] > 0) {
                $this->ExportErorr(400, 'Номер занят в запрошенный период');
                exit;
            }
            
            // Создаем новое бронирование
            $insertQuery = "
                INSERT INTO Bookings (
                    room_id,
                    guest_name,
                    booking_start,
                    booking_end,
                    status
                ) VALUES (?, ?, ?, ?, ?)
            ";
            
            $stmt = mysqli_prepare($link, $insertQuery);
            $status = 'active';
            $stmt->bind_param("issss", $id, $fio, $booking_start, $booking_end, $status);
            $stmt->execute();
    
            // Получаем ID созданного бронирования
            $booking_id = $stmt->insert_id;
            
            // Получаем данные о бронировании
            $result = mysqli_query($link, "
                SELECT b.*, hr.room_type, hr.price, hr.capacity, hr.description
                FROM Bookings b
                JOIN HotelRooms hr ON b.room_id = hr.room_id
                WHERE b.booking_id = $booking_id
            ",);
            
            if (!$result) {
                throw new Exception(mysqli_error($link));
            }
            
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_close($link);
            
            $this->ExportResponse($rows);
            
        } catch (Exception $e) {
            $this->ExportErorr(500, 'Ошибка при обработке брони');
        }
    }

    private function SendBot($data) {
        $message = isset($data['get_params']['message']) ? $data['get_params']['message'] : null;
        $token = "7756708742:AAHg5g9DIwciXxhoAeV7B2YvQIs5pi-wb_M";
        $chatId = isset($data['get_params']['chatId']) ? $data['get_params']['chatId'] : null;

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'chat_id' => $chatId,
            'text' => $message
        ]));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }

    private function GetContacts($data) {
        $table_name = 'Contacts';
        $link = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );

        if ($link === false) {
            echo ("Ошибка подключения: ".mysqli_connect_error());
            exit;
        }
        
        mysqli_set_charset($link, "utf8");

        try {
            // Получаем параметры фильтрации и сортировки из GET-запроса
            $filters = $_GET;
            
            // Базовый SQL-запрос
            $sql = "SELECT * FROM {$table_name}";

            $stmt = mysqli_prepare($link, $sql);

            $stmt->execute();
            $result = $stmt->get_result();
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            
            mysqli_close($link);
            
            $this->ExportResponse($rows);
            exit;

        }catch (Exception $e) {
            $this->ExportErorr(500, 'Ошибка при получении контактов');
        }
    }

    private function ExportResponse($rows) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'data' => $rows
        ]);
        exit;
    }

    private function ExportErorr($code, $message) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode([
                'status' => 'error',
                'message' => $message
        ]);
        exit;
    }
}

// Использование
$router = new RESTRouter();
$response = $router->handleRequest();
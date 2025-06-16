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
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $rows
            ]);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ошибка при получении списка номеров'
            ]);
            exit;
        }
    }

    private function PostHotelBooking($data) {
        //echo "PostHotelBooking вызван<br>";
        $id = isset($data['get_params']['id']) ? $data['get_params']['id'] : null;
        $fio = isset($data['get_params']['fio']) ? $data['get_params']['fio'] : null;
        $table_name = 'HotelRooms';
    
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
    
        try {
            // Сначала проверяем текущий статус брони
            $checkBooked = mysqli_query($link, "SELECT booked FROM {$table_name} WHERE room_id = {$id}");
            
            if (!$checkBooked) {
                throw new Exception(mysqli_error($link));
            }
    
            $roomData = mysqli_fetch_assoc($checkBooked);
            
            // Если комната уже забронирована
            if (!empty($roomData['booked'])) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Бронь уже существует для этой комнаты'
                ]);
                exit;
            }
    
            // Если комната свободна - обновляем статус брони
            $updateQuery = "
            UPDATE HotelRooms
            SET booked = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE room_id = ?
            ";
    
            $stmt = mysqli_prepare($link, $updateQuery);
            $stmt->bind_param("si", $fio, $id);
            $stmt->execute();
    
            // Получаем обновленные данные
            $result = mysqli_query($link, "SELECT * FROM {$table_name} WHERE room_id = {$id}");
            if (!$result) {
                throw new Exception(mysqli_error($link));
            }
    
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_close($link);
    
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $rows
            ]);
    
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ошибка при обработке брони'
            ]);
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
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $rows
            ]);
            exit;

        }catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ошибка при получении контактов'
            ]);
            exit;
        }
    }
}

// Использование
$router = new RESTRouter();
$response = $router->handleRequest();
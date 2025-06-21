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

        $this->addRoute('GET', '/api/router.php/roomReservation', [$this, 'GetHotelBooking']);
        $this->addRoute('POST', '/api/router.php/roomReservation', [$this, 'PostHotelBooking']);


        $this->addRoute('GET','/api/router.php/sendBot', [$this,'SendBot']);
        $this->addRoute('GET', '/api/router.php/contacts', [$this,'GetContacts']);

        $this->addRoute('GET', '/api/router.php/reviews', [$this, 'GetReviews']);
        $this->addRoute('POST', '/api/router.php/review', [$this, 'PostReview']);


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
            $sql = "SELECT 
                hr.*,
                GROUP_CONCAT(ri.image_url) as images
            FROM HotelRooms hr
            LEFT JOIN RoomImages ri ON hr.room_id = ri.room_id";
            $params = [];
            
            // Если есть параметры фильтрации, добавляем WHERE
            if (!empty($filters)) {
                $sql .= " WHERE 1=1";
                // Фильтр по имени
                if (isset($filters['room_type'])) {
                    $sql .= " AND hr.room_type = ?";
                    $params[] = $filters['room_type'];
                }
                // Фильтр по цене (диапазон)
                if (isset($filters['price'])) {
                    list($minPrice, $maxPrice) = explode('-', $filters['price']);
                    $sql .= " AND hr.price BETWEEN ? AND ?";
                    $params[] = $minPrice;
                    $params[] = $maxPrice;
                }
                // Фильтр по количеству спальных мест
                if (isset($filters['capacity'])) {
                    $sql .= " AND hr.capacity = ?";
                    $params[] = $filters['capacity'];
                }

                
            }
            
            // Добавляем сортировку
            $sortField = isset($filters['sort']) ? $filters['sort'] : 'hr.room_type';
            $sortOrder = isset($filters['order']) ? $filters['order'] : 'asc';
            // Проверяем корректность параметров сортировки
            $validSortFields = ['room_type', 'price', 'capacity'];
            if (!in_array($sortField, $validSortFields)) {
                $sortField = 'hr.room_type';
            }
            $validSortOrders = ['asc', 'desc'];
            if (!in_array($sortOrder, $validSortOrders)) {
                $sortOrder = 'asc';
            }
            $sql .= ' GROUP BY hr.room_id';
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
            
            // Преобразуем строки в массивы для изображений
            foreach ($rows as &$row) {
            // Если изображений нет или они пустые
            if (empty($row['images']) || $row['images'] === null) {
                $row['images'] = ['/image/default.jpg'];
            } else {
                // Разбиваем строку с изображениями на массив
                $row['images'] = explode(',', $row['images']);
            }
        }
            
            $this->ExportResponse($rows);
            exit;
        } catch (Exception $e) {
            $this->ExportErorr(500, 'Ошибка при получении списка номеров');
            exit;
        }
    }

    private function GetHotelBooking($data) {
        // Получаем ID из параметров запроса

        $booking_id = isset($data['get_params']['id']) ? $data['get_params']['id'] : null;

        // Проверяем наличие ID
        if (!$booking_id) {
            $this->ExportError(400, 'Не указан ID бронирования');
            return;
        }

        // Подключение к базе данных
        $link = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );

        if ($link === false) {
            $this->ExportError(500, 'Ошибка подключения к базе данных: '.mysqli_connect_error());
            return;
        }

        mysqli_set_charset($link, "utf8");

        try {
            // Запрос для получения информации о бронировании и комнате
            $query = "
                SELECT b.*, r.room_type, r.price 
                FROM Bookings b
                LEFT JOIN HotelRooms r ON b.room_id = r.room_id
                WHERE b.room_id = ?
            ";

            $stmt = mysqli_prepare($link, $query);
            if (!$stmt) {
                throw new Exception('Ошибка подготовки запроса: '.mysqli_error($link));
            }

            $stmt->bind_param("i", $booking_id);
            $stmt->execute();

            $result = $stmt->get_result();
            if (!$result) {
                throw new Exception('Ошибка выполнения запроса: '.mysqli_error($link));
            }

            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            mysqli_close($link);

            if (empty($rows)) {
                $this->ExportError(404, 'Бронирование не найдено');
                return;
            }

            $this->ExportResponse($rows);
        } catch (Exception $e) {
            $this->ExportError(500, 'Ошибка при получении бронирования: '.$e->getMessage());
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

    private function GetReviews(){
        $table_name = 'Hotel_reviews';
        $link = mysqli_connect(
            $this->host, 
            $this->user, 
            $this->password, 
            $this->db_name
        );

        if ($link === false) {
            echo "Ошибка подключения: ".mysqli_connect_error();
            exit;
        }
    
        // Установка правильной кодировки
        mysqli_set_charset($link, "utf8");

        try {
            // Получаем все отзывы с сортировкой по дате публикации
            $sql = "SELECT * FROM {$table_name}";

            $result = mysqli_query($link, $sql);

            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

            // Вычисляем средний рейтинг
            $averageSql = "SELECT AVG(rating) as average_rating FROM {$table_name}";
            $avgResult = mysqli_query($link, $averageSql);
            $avgData = mysqli_fetch_assoc($avgResult);

            // Форматируем средний рейтинг до одного знака после запятой
            $averageRating = number_format((float)$avgData['average_rating'], 1, '.', '');

            mysqli_close($link);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $rows,
                'average_rating' => $averageRating
            ]);
            exit;
        }
        catch (Exception $e){
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
            'status' => 'error',
            'message' => 'Ошибка при получении отзывов'
            ]);
            exit;
        }
    }

    private function PostReview($data) {  
        $fio = isset($data['get_params']['fio']) ? $data['get_params']['fio'] : null;
        $review = isset($data['get_params']['review']) ? $data['get_params']['review'] : null;
        $rating = isset($data['get_params']['rating']) ? $data['get_params']['rating'] : null;

        // echo $fio . $review . $rating;
    
        // Проверка обязательных полей
        if (empty($fio) ||  empty($review) || isset($rating)) {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'message' => 'Необходимы поля fio, review и rating'
            ]);
            exit;
        }
    
        // Проверка диапазона рейтинга
        if ($rating < 1 || $rating > 5) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Рейтинг должен быть от 1 до 5'
            ]);
            exit;
        }

        $link = mysqli_connect(
            $this->host, 
            $this->user, 
            $this->password, 
            $this->db_name
        );
    
        if ($link === false) {
            echo "Ошибка подключения: ".mysqli_connect_error();
            exit;
        }
    
        mysqli_set_charset($link, "utf8");

        try {
            // Вставляем данные
            $sql = "
                INSERT INTO Hotel_reviews (fio, review, rating)
                VALUES (?, ?, ?);
            ";

            $stmt = mysqli_prepare($link, $sql);
            $stmt->bind_param("ssd", $fio, $review, $rating);
            $stmt->execute();
            
            $newId = $stmt->insert_id;

            $newReview = mysqli_query($link, "
                SELECT * FROM Hotel_reviews 
                WHERE id = $newId
            ",);

            if (!$newReview) {
                throw new Exception(mysqli_error($link));
            }

            $rows = mysqli_fetch_all($newReview, MYSQLI_ASSOC);

            mysqli_close($link);
        
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(201);
            echo json_encode([
                'status' => 'success',
                'message' => 'Отзыв добавлен',
                'data' => $rows[0]
            ]);
            exit;
        } 
        catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Ошибка при создании отзыва'
            ]);
            exit;
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

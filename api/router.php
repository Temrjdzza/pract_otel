<?php
class RESTRouter {
    private $routes = [];
    private $host = 'localhost';
    private $user = 'root';
    private $password = '';
    private $db_name = 'mybd';

    public function __construct() {
        // endpoints для запросов

        //  http://localhost/index.php/rooms
        $this->addRoute('GET', '/index.php/rooms', [$this, 'handleGetHotelRooms']);
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
        
        return ($this->routes[$method][$uri])();
    }

    private function handleGetHotelRooms() {
        //echo "handleGetHotelRooms вызван<br>";
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
            $result = mysqli_query($link, "SELECT * FROM {$table_name}");
            

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
            exit;

        } catch (Exception $e) {

            http_response_code(500);
            echo "Ошибка при получении списка номеров";
        }
    }
}

// Использование
$router = new RESTRouter();
$response = $router->handleRequest();
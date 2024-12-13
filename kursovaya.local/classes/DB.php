<?php
class DB
{
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "";
    private $user = "postgres";
    private $password = "1234";
    private $dbconn3;
    private $stat;
    private $tb_name = "";
    private $sql = "";
    function __construct(string $dbname){
        $this->dbname = $dbname;
        $this->dbconn3 = pg_connect("host=".$this->host." port=".$this->port." dbname=".$this->dbname." user=".$this->user." password=".$this->password);
        $this->stat = pg_connection_status($this->dbconn3);
        if ($this->stat !== PGSQL_CONNECTION_OK) {
          die("Соединение не было установлено");
        }
    }

    function saveToken(string $username, string $jwt) {
        // Экранируем входные данные для предотвращения SQL инъекций
        $username = pg_escape_string($conn, $username);
        $jwt = pg_escape_string($conn, $jwt);

        // Формируем SQL-запрос для обновления токена
        $query = "UPDATE Users_catalog SET token = '$jwt' WHERE username = '$username'";

        // Выполняем запрос
        $result = pg_query($conn, $query);

        if (!$result) {
            echo "Ошибка выполнения запроса: " . pg_last_error($conn) . "\n";
        } else {
            echo "Токен успешно сохранен.\n";
        }
    }


    function login(string $username) {
        try {
            // Подготовка SQL-запроса с параметрами
            $this->sql = 'SELECT * FROM kursovaya."Users_catalog" WHERE username = $1';
            $result = pg_prepare($this->dbconn3, "login_query", $this->sql);

            // Выполнение подготовленного запроса
            $result = pg_execute($this->dbconn3, "login_query", array($username));

            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            echo "Ошибка подключения: " . $e->getMessage();
            return [];
        }
    }

    function selectTable(string $query){
        try {
            $keywords = explode(" ", $query);

            $this->sql = "SELECT $keywords[0] FROM $keywords[1] ";


            $result = pg_query($this->dbconn3, $this->sql);

            // Получаем результаты
            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            echo "Ошибка подключения: " . $e->getMessage();
            return [];
        }
    }

    function closeConn(){
        pg_close($this->dbconn3);
    }

    function searchBooks(string $query) {
        try {
            $keywords = explode(" ", $query);

            $this->sql = 'SELECT title, place_publication, date_publication FROM kursovaya."Books_catalog" WHERE ';

            // Генерируем условия поиска
            $conditions = [];
            foreach ($keywords as $index => $keyword) {
                $conditions[] = "(title ILIKE $" . ($index + 1) . " OR place_publication ILIKE $" . ($index + 1) . " OR date_publication ILIKE $" . ($index + 1) . ")";
            }

            // Объединяем условия с помощью OR
            $this->sql .= implode(" OR ", $conditions);

            // Подготовка запроса
            $stmt = pg_prepare($this->dbconn3, "my_query_books", $this->sql);

            // Подготовка параметров для выполнения запроса
            $params = [];
            foreach ($keywords as $keyword) {
                $params[] = '%' . $keyword . '%';
            }

            // Выполнение запроса с параметрами
            $result = pg_execute($this->dbconn3, "my_query_books", $params);

            // Получаем результаты
            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            echo "Ошибка подключения: " . $e->getMessage();
            return [];
        }
    }

    // Функция поиска авторов
    function searchAuthors(string $query) {
        try {
            $keywords = explode(" ", $query);
            $this->sql = 'SELECT author_surname, author_name, author_patronymic FROM kursovaya."Authors_catalog" WHERE ';

            // Генерируем условия поиска
            $conditions = [];
            foreach ($keywords as $index => $keyword) {
                $conditions[] = "(author_surname ILIKE $" . ($index + 1) . " OR author_name ILIKE $" . ($index + 1) . " OR author_patronymic ILIKE $" . ($index + 1) . ")";
            }

            // Объединяем условия с помощью OR
            $this->sql .= implode(" OR ", $conditions);

            // Подготовка запроса
            $stmt = pg_prepare($this->dbconn3, "my_query_authors", $this->sql);

            // Подготовка параметров для выполнения запроса
            $params = [];
            foreach ($keywords as $keyword) {
                $params[] = '%' . $keyword . '%';
            }

            // Выполнение запроса с параметрами
            $result = pg_execute($this->dbconn3, "my_query_authors", $params);

            // Получаем результаты
            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            echo "Ошибка подключения: " . $e->getMessage();
            return [];
        }
    }
}
?>
<?php

header('Access-Control-Allow-Origin: https://localhost:5173');

class DB
{
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "";
    private $user = "postgres";
    private $password = "1234";
    private $dbconn3;
    private $stat;
    private $sql = "";
    function __construct(string $dbname){
        $this->dbname = $dbname;
        $this->dbconn3 = pg_connect("host=".$this->host." port=".$this->port." dbname=".$this->dbname." user=".$this->user." password=".$this->password);
        $this->stat = pg_connection_status($this->dbconn3);
        if ($this->stat !== PGSQL_CONNECTION_OK) {
          die("Соединение не было установлено");
        }
    }

    function selectComments(string $query){
        try {
            $keywords = explode(" ", $query);
            $this->sql = "SELECT $keywords[0] FROM $keywords[1] $keywords[2] $keywords[3] $keywords[4] $keywords[5] $keywords[6] $keywords[7] $keywords[8] $keywords[9] $keywords[10] $keywords[11]";
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

    function deleteComments(string $id, string $username){
        // Начинаем транзакцию
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Выполняем вставку в таблицу Booking_catalog
            $query = "DELETE FROM kursovaya.\"Comments\"
                      WHERE id = $1 and user_id = $2;";

            $result = pg_query_params($this->dbconn3, $query, array($id, $reader));

            if (!$result) {
                throw new Exception("Ошибка при удалении комментария.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }

    function addComments(string $username, string $comment){
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Получаем максимальный id из Booking_catalog и увеличиваем его на 1
            $query = "SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM kursovaya.\"Comments\";";
            $idResult = pg_query($this->dbconn3, $query);

            if (!$idResult) {
                throw new Exception("Ошибка при получении нового id для бронирования.");
            }

            $idRow = pg_fetch_assoc($idResult);
            $id = $idRow['new_id']; // Новый id для бронирования

            // Выполняем вставку в таблицу Booking_catalog
            $query = "INSERT INTO kursovaya.\"Comments\"
                      VALUES ($1, $2, $3);";

            $result = pg_query_params($this->dbconn3, $query, array($id, $reader, $comment));

            if (!$result) {
                throw new Exception("Ошибка при добавлении комментария.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }

     function deleteOrder(string $id, string $username) {
        // Начинаем транзакцию
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Выполняем вставку в таблицу Booking_catalog
            $query = "DELETE FROM kursovaya.\"Order_catalog\"
                      WHERE id = $1 and reader_id = $2;";

            $result = pg_query_params($this->dbconn3, $query, array($id, $reader));

            if (!$result) {
                throw new Exception("Ошибка при удалении заказа.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }

    function addOrder(string $title, string $surname, string $name, string $patronymic, string $quantity, string $username, string $date) {
        // Начинаем транзакцию
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Получаем максимальный id из Booking_catalog и увеличиваем его на 1
            $query = "SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM kursovaya.\"Order_catalog\";";
            $idResult = pg_query($this->dbconn3, $query);

            if (!$idResult) {
                throw new Exception("Ошибка при получении нового id для заказа.");
            }

            $idRow = pg_fetch_assoc($idResult);
            $id = $idRow['new_id']; // Новый id для бронирования

            // Выполняем вставку в таблицу Booking_catalog
            $query = "INSERT INTO kursovaya.\"Order_catalog\"
                      VALUES ($1, $2, $3, $4, $5, $6, $7, $8);";

            $result = pg_query_params($this->dbconn3, $query, array($id, $title, $surname, $name, $patronymic, $quantity, $reader, $date));

            if (!$result) {
                throw new Exception("Ошибка при добавлении заказа.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }

    function selectOrder(string $query, string $username) {
        try {
            // Разбиваем запрос на ключевые слова
            $keywords = explode(" ", $query);

            // Проверяем, что в запросе есть достаточно элементов
            if (count($keywords) < 2) {
                throw new Exception("Недостаточно параметров в запросе.");
            }

            // Экранируем значения для предотвращения SQL-инъекций
            $usernameEscaped = pg_escape_string($this->dbconn3, $username);

            // Формируем SQL-запрос
            $this->sql = "SELECT $keywords[0] FROM $keywords[1] bc
                          JOIN kursovaya.\"Users_catalog\" u ON bc.reader_id = u.id
                          WHERE u.username = $1";

            // Подготавливаем запрос
            $result = pg_prepare($this->dbconn3, "select_order_query", $this->sql);

            // Выполняем подготовленный запрос с параметрами
            $result = pg_execute($this->dbconn3, "select_order_query", array($usernameEscaped));

            // Получаем результаты
            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            // Можно добавить логирование ошибки здесь
            return [];
        }
    }

    function deleteBooking(string $index, string $username) {
        // Начинаем транзакцию
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Выполняем вставку в таблицу Booking_catalog
            $query = "DELETE FROM kursovaya.\"Booking_catalog\"
                      WHERE index = $1 and reader_id = $2;";

            $result = pg_query_params($this->dbconn3, $query, array($index, $reader));

            if (!$result) {
                throw new Exception("Ошибка при удалении бронирования.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }

    function addBooking(string $index, string $username) {
        // Начинаем транзакцию
        pg_query($this->dbconn3, "BEGIN");
        try {
            // Получаем id пользователя по имени пользователя
            $query = "SELECT id FROM kursovaya.\"Users_catalog\" WHERE username = $1;";
            $readerResult = pg_query_params($this->dbconn3, $query, array($username));

            if (!$readerResult) {
                throw new Exception("Ошибка при получении id пользователя.");
            }

            $readerRow = pg_fetch_assoc($readerResult);
            if (!$readerRow) {
                throw new Exception("Пользователь не найден.");
            }

            $reader = $readerRow['id']; // Получаем id пользователя

            // Получаем максимальный id из Booking_catalog и увеличиваем его на 1
            $query = "SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM kursovaya.\"Booking_catalog\";";
            $idResult = pg_query($this->dbconn3, $query);

            if (!$idResult) {
                throw new Exception("Ошибка при получении нового id для бронирования.");
            }

            $idRow = pg_fetch_assoc($idResult);
            $id = $idRow['new_id']; // Новый id для бронирования

            // Выполняем вставку в таблицу Booking_catalog
            $query = "INSERT INTO kursovaya.\"Booking_catalog\"
                      VALUES ($1, $2, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP + INTERVAL '30 days', $3, false, 'false');";

            $result = pg_query_params($this->dbconn3, $query, array($index, $reader, $id));

            if (!$result) {
                throw new Exception("Ошибка при добавлении бронирования.");
            }

            // Подтверждаем транзакцию
            pg_query($this->dbconn3, "COMMIT");

            return true;
        } catch (Exception $e) {
            // Откатываем транзакцию в случае ошибки
            pg_query($this->dbconn3, "ROLLBACK");

            return false; // Возвращаем false в случае ошибки
        }
    }


    function selectBooking(string $query, string $username) {
        try {
            // Разбиваем запрос на ключевые слова
            $keywords = explode(" ", $query);

            // Проверяем, что в запросе есть достаточно элементов
            if (count($keywords) < 2) {
                throw new Exception("Недостаточно параметров в запросе.");
            }

            // Экранируем значения для предотвращения SQL-инъекций
            $usernameEscaped = pg_escape_string($this->dbconn3, $username);

            // Формируем SQL-запрос
            $this->sql = "SELECT $keywords[0] FROM $keywords[1] bc
                          JOIN kursovaya.\"Booking_catalog\" b ON bc.index = b.index
                          JOIN kursovaya.\"Users_catalog\" u ON b.reader_id = u.id
                          WHERE u.username = $1";

            // Подготавливаем запрос
            $result = pg_prepare($this->dbconn3, "select_booking_query", $this->sql);

            // Выполняем подготовленный запрос с параметрами
            $result = pg_execute($this->dbconn3, "select_booking_query", array($usernameEscaped));

            // Получаем результаты
            if ($result) {
                return pg_fetch_all($result) ?: []; // Возвращаем результаты или пустой массив
            } else {
                return [];
            }
        } catch (Exception $e) {
            // Можно добавить логирование ошибки здесь
            return [];
        }
    }

    function saveToken(string $username, string $jwt) {
        // Экранируем входные данные для предотвращения SQL инъекций
        $username = pg_escape_string($this->dbconn3, $username);
        $jwt = pg_escape_string($this->dbconn3, $jwt);

        // Формируем SQL-запрос для обновления токена
        $query = "UPDATE kursovaya.\"Users_catalog\" SET token = '$jwt' WHERE username = '$username'";

        // Выполняем запрос
        $result = pg_query($this->dbconn3, $query);
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
            return [];
        }
    }

    function selectTable(string $query){
        try {
            $keywords = explode(" ", $query);

            if(count($keywords) > 2){
                $this->sql = "SELECT $keywords[0] $keywords[1] $keywords[2] FROM $keywords[3] $keywords[4] $keywords[5] $keywords[6] $keywords[7] $keywords[8] $keywords[9] $keywords[10] $keywords[11] $keywords[12] $keywords[13] $keywords[14] $keywords[15] $keywords[16] $keywords[17]";
            }else{
            $this->sql = "SELECT $keywords[0] FROM $keywords[1] ";
            }


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

            $this->sql = 'SELECT index, title, information_publication, date_publication FROM kursovaya."Books_catalog" WHERE ';

            // Генерируем условия поиска
            $conditions = [];
            foreach ($keywords as $index => $keyword) {
                $conditions[] = "(title ILIKE $" . ($index + 1) . " OR information_publication ILIKE $" . ($index + 1) . " OR date_publication ILIKE $" . ($index + 1) . ")";
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
            $this->sql = 'SELECT id, author_surname, author_name, author_patronymic FROM kursovaya."Authors_catalog" WHERE ';

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
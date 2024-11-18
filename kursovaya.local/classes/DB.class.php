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
        var_dump($this->dbconn3);
        $this->stat = pg_connection_status($this->dbconn3);
        if ($this->stat !== PGSQL_CONNECTION_OK) {
          die("Соединение не было установлено");
        }
    }
    function infoTable(string $tablename){
        $this->tb_name = $tablename;
        $rows = pg_copy_to($this->dbconn3, $this->tb_name);
        var_dump($rows);
        echo '<br> <br> 3) Вывод строк <br>';
        foreach($rows as $row){
            $row = str_replace("\N", "", $row);
            echo '<br>'.$row;
        }
    }
    function selectTable(string $tablename, string $columns, string $numstring){
        $this->tb_name = $tablename;
        $this->sql = "select ".$columns." from ".$this->tb_name." limit ".$numstring.";";
        echo $this->sql;
        $result = pg_query($this->dbconn3, $this->sql);
        var_dump($result);
        if (!$result) {
            die("Произошла ошибка чтения таблицы");
        }
        else{
            header('Content-Type = application/json; charset = utf-8');
                $arr = [];
                while($row = pg_fetch_array($result)){
                    $arr[]=$row;
                }
            echo '<br>'.json_encode($arr);
        }
    }


}
?>
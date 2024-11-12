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
        pg_close($this->dbconn3);
    }
}
?>
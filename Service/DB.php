<?php


namespace Service;


class DB
{
    protected $host;
    protected $user;
    protected $dbName;
    protected $pass;

    public function __construct($host, $user, $dbName, $pass)
    {
        $this->host = $host;
        $this->user = $user;
        $this->dbName = $dbName;
        $this->pass = $pass;
    }

    public function getConnection()
    {
        $link = mysqli_connect($this->host, $this->user, $this->pass, $this->dbName);

        if (!$link) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        return $link;
    }

}
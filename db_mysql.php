<?php

// Якщо вдруг захочеться перейти з SQLite на MySQL достатньо просто видалити db.php
// А поточний файл db_mysql.php перейменувати в db.php
// Звичайно заздалегідь потрібно мати готову mysql базу данних і вказати нижче данні для підключення
class db {
    private static $error = array(
        'errorno' => null,
        'errormsg' => null
    );

    private static $local;
    private static $username = ""; 
    private static $pass = ""; 
    private static $dbname = ""; 
    private static $host = ""; 
    private static $port = "3306";
    private static $charset = "utf8mb4";


    private function __construct() {
        // приватний конструктор, щоб запобігти створенню об'єктів
    }

    private static function mysqlConnect() {
        @$mysqli = new mysqli(self::$host, self::$username, self::$pass, self::$dbname, self::$port);
        if (mysqli_connect_errno()) {
            self::$error = array(
                'errorno' => mysqli_connect_errno(),
                'errormsg' => mysqli_connect_error()
            );
            return false;
        }
        $mysqli->set_charset(self::$charset);
        return $mysqli;
    }

    private static function initialize() {
        self::$local = self::mysqlConnect();
    }

    public static function db() {
        if (empty(self::$local)) {
            self::initialize();
        }
        return self::$local;
    }

}

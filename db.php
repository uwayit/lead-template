<?php

// Файл зібрано таким чином, щоб у разі необхідності
// його можна було просто замінити на той що лежить поряд в корені 
// і називається db_mysql.php
// І після заміни весь проект автоматично почав працювати із MySQL базою, замість SQLite
class db
    {
    private static $error = array(
        'errorno' => null,
        'errormsg' => null
    );

    private static $local;
    private static $dbname = __DIR__ . "/SQLite.db";  // Шлях до SQLite бази даних

    private function __construct()
        {
        // Приватний конструктор, щоб запобігти створенню об'єктів
        }

    private static function sqliteConnect()
        {
        try {
            $pdo = new PDO("sqlite:" . self::$dbname);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
            } catch (PDOException $e) {
            self::$error = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage()
            );
            return false;
            }
        }

    private static function initialize()
        {
        self::$local = self::sqliteConnect();
        }

    public static function db()
        {
        if (empty(self::$local)) {
            self::initialize();
            }
        return self::$local;
        }
    }

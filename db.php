<?php

// Файл зібрано таким чином, щоб у разі необхідності
class db {
    private static $instance = null;
    private static $connection = null;


    // Параметри підключення до MySQL
    private static $username = "";
    private static $pass = "";
    private static $dbname = "";
    private static $host = "";
    private static $port = "3306";
    private static $charset = "utf8mb4";

    // Шлях до SQLite бази даних
    private static $dbsqlite = __DIR__ . "/SQLite.db";

    // Метод для отримання інстансу бази даних
    public static function db() {
        if (self::$instance === null) {
            if (core::$SQL === 'mysql') {
                self::$connection = new mysqli(
                    self::$host,
                    self::$username,
                    self::$pass,
                    self::$dbname,
                    self::$port
                );
                self::$connection->set_charset(self::$charset);
            } elseif (core::$SQL === 'sqlite') {
                self::$connection = new PDO('sqlite:' . self::$dbsqlite);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Метод для виконання запиту
    public function query($query) {
        if (core::$SQL === 'mysql') {
            return self::$connection->query($query);
        } elseif (core::$SQL === 'sqlite') {
            return self::$connection->query($query);
        }
        return false;
    }

    // Метод для отримання асоціативного масиву
    public static function fetch($result) {
        if (core::$SQL === 'mysql') {
            return $result->fetch_assoc();
        } elseif (core::$SQL === 'sqlite') {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // Метод для отримання масиву
    public static function fetch_array($result) {
        if (core::$SQL === 'mysql') {
            return $result->fetch_array();
        } elseif (core::$SQL === 'sqlite') {
            return $result->fetch(PDO::FETCH_BOTH);
        }
        return false;
    }

    public static function fetchAll($result)
        {
        if (core::$SQL === 'mysql') {
            return $result->fetch_all(MYSQLI_ASSOC);
            } elseif (core::$SQL === 'sqlite') {
            return $result->fetchAll(PDO::FETCH_ASSOC);
            }

        return false;
        }

    // Закриття результату
    public static function close($result) {
        if (core::$SQL === 'mysql') {
            $result->close();
        } elseif (core::$SQL === 'sqlite') {
            // В SQLite немає необхідності закривати результат.
            // Це можна просто залишити порожнім методом.
        }
    }

    // Метод для отримання ID останнього доданого запису
    public static function lastInsertId() {
        if (core::$SQL === 'mysql') {
            return self::$connection->insert_id;
        } elseif (core::$SQL === 'sqlite') {
            return self::$connection->lastInsertId();
        }
        return false;
    }

    // Закриття підключення до бази даних
    public static function closeConnection() {
        if (self::$connection !== null) {
            if (core::$SQL === 'mysql') {
                self::$connection->close();
            } elseif (core::$SQL === 'sqlite') {
                self::$connection = null;
            }
        }
        self::$instance = null;
    }
}

<?php


class core
    {

    const DTZERO = '0000-00-00 00:00:00';
    const DZERO = '0000-00-00';

    public static $SQL = 'sqlite';


    static function setSQL($sql){
        
    }

    // ВСЁ ЧТО СВЯЗАНО С ЗАЩИТОЙ ОТ ВЗЛОМА ЧЕГО УГОДНО В СИСТЕМЕ И ЛОГИРОВАНИЕМ ПРОБЛЕМ

    // Функция защищает пост запросы от ....
    static function securePostData()
        {
        $_POST = self::safetyCleanArray($_POST);

        }

    // Получаем текущий домен очищенный от www
    static function getCurrentDomainByServerHttpHost()
        {
        // Пытаемся получить домен по SERVER_NAME
        if (isset($_SERVER["SERVER_NAME"]) and $_SERVER["SERVER_NAME"]) {
            // Проверяем есть ли у нас $_SERVER['HTTP_HOST']
            // Если есть, то вычленяем из него домен
            if (substr($_SERVER['HTTP_HOST'], 0, 4) == 'www.') {
                return substr($_SERVER['HTTP_HOST'], 4);
                } else {
                return $_SERVER['HTTP_HOST'];
                }
            }
        // але якщо ініціатор консольний (наприклад крон), 
        // то доведеться $argv[1] переданному из вне
        // Поки не зробив

        return false;
        }



    // Функція дозволяє очистити текст від уразливостей
    // Проганяємо текст через цю функцію в ідеалі завжди
    public static function safety($text, $options = [])
        {
        $defaults = [
            'addslashes' => true,
            'strip_tags' => true,
            'escape' => true,
            'htmlspecialchars' => true
        ];
        $options = array_merge($defaults, $options);

        if ($options['strip_tags']) {
            $text = strip_tags($text);
            }
        if ($options['addslashes']) {
            $text = addslashes($text);
            }
        // Перетворюємо спеціальні символи на HTML-сутності
        // Робимо це перед escapeString
        if ($options['htmlspecialchars']) {
            $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            }
        if ($options['escape'] && db::db()) {
            $text = self::escapeString($text);
            // $text = db::db()->real_escape_string($text);
            }

        return $text;
        }

    // Почав використовувати імітатор db::db()->real_escape_string
    // КОли розпочав використовувати SQLite
    public static function escapeString($string)
        {
        // Додаємо escaping для символів, які можуть викликати проблеми в SQL
        $string = str_replace("'", "''", $string); // Дублюємо одинарні лапки
        $string = str_replace("\\", "\\\\", $string); // Дублюємо зворотні слеші
        $string = str_replace("\0", '', $string); // Видаляємо нульові байти

        return $string;
        }

    // Функція чистить массив від небезпечних данних
    public static function safetyCleanArray($data, $options = [])
        {
        $cleanedData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cleanedData[$key] = self::safetyCleanArray($value, $options);
                } else {
                $cleanedData[$key] = self::safety($value, $options);
                }
            }

        return $cleanedData;
        }

    // Ми можемо порівняти масиви після очистки від небезпечних данних
    // Якщо value ключів в масивах не співпадають, то вірогідно
    // навмисне чи випадково, але через форму намагались передати небезпечні дані
    public static function arraysAreEqual(array $array1, array $array2): bool
        {
        // Якщо ключі масивів різні, масиви не рівні
        if (array_keys($array1) !== array_keys($array2)) {
            return false;
            }

        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                // Якщо елемент є масивом, рекурсивно перевіряємо відповідні елементи
                if (!self::arraysAreEqual($value, $array2[$key])) {
                    return false;
                    }
                } else {
                // Якщо елементи не масиви, просто порівнюємо їх значення
                if ($value !== $array2[$key]) {
                    return false;
                    }
                }
            }

        return true;
        }

    // порівнює значення між двома масивами за ключами, вказаними в третьому масиві. 
    // Якщо якесь значення не збігається або ключ відсутній у будь-якому з масивів, функція повертатиме true
    public static function compareArraysByKeys(array $array1, array $array2, array $keys): bool
        {
        foreach ($keys as $key) {
            // Якщо ключ відсутній у будь-якому з масивів або значення не співпадають
            if (!array_key_exists($key, $array1) || !array_key_exists($key, $array2) || $array1[$key] !== $array2[$key]) {
                return true;
                }
            }
        return false;
        }






    // Отримуємо локаль (здебільшого на початку використовую для корректного сортування за алфавітом)
    static function getLocal($country)
        {
        if ($country == 'gb' or $country == 'en' or $country == 'us') {
            return 'en_GB';
            } else if ($country == 'ru') {
            return 'ru_RU';
            } else if ($country == 'ua' or $country == 'uk') {
            return 'uk_UA';
            } else {
            return 'uk_UA';
            }

        }

    // Функція, яка видаляє з масива єлементи які є в другому масиві
    // Але тільки у випадку якщо це треба робити
    // Зазвичай використовую аби чистити список міст або регіонів від мусору
    static function arrayСleaning($goal, $drop, $locale, $need = false)
        {
        if (!empty($drop) and !empty($need)) {
            $resultArray = array_diff($goal, $drop);
            } else {
            $resultArray = $goal;
            }
        // Сортуємо масив за алфавітом
        $collator = collator_create($locale);
        usort($resultArray, function ($a, $b) use ($collator) {
            return $collator->compare($a, $b);
            });
        return $resultArray;
        }




    // Функция делает первую букву кирилицы заглавной
    static public function mb_ucfirst($str, $encoding = 'UTF-8')
        {
        if (empty($str)) {
            return false;
            }
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtolower($str, $encoding = 'utf-8');
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) .
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
        }

    // Якщо якийсь елемент масиву пустий
    // Повертає false
    public static function checkFalse($array)
        {
        foreach ($array as $value) {
            if ($value === '') {
                return false;
                }
            }
        return true;
        }


    static function saveToTable($array, $table = 'actions_log')
        {
        // Перевіряємо, чи є всі стовпці в таблиці, і додаємо відсутні
        db::checkAndAddColumns(array_keys($array), $table);

        // Декомпозуємо масив на ключі та значення для INSERT
        $columns = implode(", ", array_keys($array));
        $values = implode(", ", array_map(function ($value) {
            return "'$value'";
            }, array_values($array)));

        // Формуємо запит на вставку
        $query = "INSERT INTO `$table` ($columns) VALUES ($values)";
        db::db()->query($query);
        }

    static function ipLastLogAction($ip, $datetime, $table)
        {
        $query = "SELECT * FROM `$table` WHERE `ip` = '$ip' and `datetime` > '{$datetime}'  ORDER by id DESC LIMIT 1";
        $data = db::db()->query($query)->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return false;
            }
        return $data;
        }

    static function lastLead($email, $datetime)
        {
        $query = "SELECT * FROM `leads` WHERE `email` = '$email' and `datetime` > '{$datetime}'  ORDER by id DESC LIMIT 1";
        $data = db::db()->query($query)->fetch(PDO::FETCH_ASSOC);
        if (empty($data)) {
            return false;
            }
        return $data;
        }


    // Функція яка допомогає зробити trim елементів масиву
    static public function trva(&$value)
        {
        $value = trim($value);
        }


    // Якщо елемент S е в масиві A повертаємо тру
    // Якщо передано третій параметр, то перевіряємо чи початок $s співпадає з елемнтом з $a
    static public function inar($s, $a, $checkStart = false)
        {
        if (empty($s) or empty($a))
            return false;
        // Якщо передано строку а не масив, то так і задумано
        // Розбиваємо строку
        if (!is_array($a)) {
            $a = explode(",", $a);
            }

        array_walk($a, 'self::trva'); // Трімім елементи масиву
        if ($checkStart) {
            foreach ($a as $prefix) {
                if (strpos($s, $prefix) === 0) {
                    return true;
                    }
                }
            return false;
            } else {
            if (in_array($s, $a))
                return true;
            return false;
            }
        }


    // РОбить з одномірного масиву рядок
    // Допомогає з массиву з помилкою сформувати рядок 
    // Рядок, який можна відправити наприклад в телегу адміну
    static function arrayToKeyValueString($array)
        {
        // Перевірка, чи масив не пустий і є асоціативним
        if (empty($array) || !is_array($array)) {
            return '';
            }

        $keyValuePairs = [];

        foreach ($array as $key => $value) {
            $keyValuePairs[] = $key . ':' . $value;
            }

        // Об'єднуємо всі пари в один рядок
        return implode(',', $keyValuePairs);
        }




    // Якщо в прізвищі або імені помилка - true
    static function isInvalidName($name)
        {
        // Перевірка на відповідність регулярному виразу
        if (!preg_match("/^[A-Za-z\' \-]{2,25}$/", $name)) {
            return true; // Рядок не відповідає вимогам
            }
        return false; // Рядок відповідає вимогам
        }





    static function setTrackId($get, $srok, $name = false)
        {
        if (!empty($get)) {
            $data = preg_replace('/[^a-zA-Z0-9_-]/', '', $get);
            }
        if (!empty($data) and strlen($data) >= '10' and strlen($data) <= '25' and $get == $data) {
            if (!empty($name)) {
                // Зберігаємо кукіси якщо вказано третій параметр
                setcookie($name, $data, time() + (3600 * 24 * $srok), '/');
                }
            return $data;
            }
        return false;
        }



    } // class core


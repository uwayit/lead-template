<?php

/*

Бібліотека для використання зовнішнього сервісу ip-api.com для отримання інформації про IP

try {
    $ipApi = new IpApiClient();
    $ipInfo = $ipApi->getIpInfo('8.8.8.8');
    print_r($ipInfo);
} catch (Exception $e) {
    echo "Помилка: " . $e->getMessage();
}


*/

class IpApiClient
{
    private $baseUrl = "http://ip-api.com/php/";

    /**
     * Отримує інформацію про IP-адресу від ip-api.com
     * 
     * @param string $ip IP-адреса, яку потрібно перевірити.
     * @return array Масив з інформацією про IP-адресу.
     * @throws Exception Якщо виникає помилка при отриманні даних.
     */
    public function getIpInfo(string $ip): array
    {
        $url = $this->baseUrl . $ip;
       // return geo::ipInfo($ip);
        // Виконуємо запит до API
        $response = file_get_contents($url);

        // Перевіряємо чи запит успішний
        if ($response === FALSE) {

            // throw new Exception("Не вдалося отримати дані від API.");
            // Я в даному випадку просто отримую з локальної бази
            // ! Але для цього локальні бази мають бути в проекті
            return geo::ipInfo($ip);
        }

        // Декодуємо відповідь
        $data = @unserialize($response);

        // Перевіряємо чи відповідь коректно розшифрована
        if ($data === FALSE || !is_array($data) || $data['status'] != 'success') {

            // throw new Exception("Невірний формат відповіді від API.");
            // Я в даному випадку просто отримую з локальної бази
            // ! Але для цього локальні бази мають бути в проекті
            return geo::ipInfo($ip);
        }
        $res['country'] = $data['countryCode'];
        $res['iso'] = $data['countryCode'].'-'. $data['region'];
        $res['region'] = $data['regionName'];
        $res['city'] = $data['city'];
        return $res;
    }
}


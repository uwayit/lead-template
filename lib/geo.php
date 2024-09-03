<?php

// Все що так чи інакше пов'язано з геолокацією, можна отримати за допомогою функцій цього класу



class geo
    {


    // Отримуємо, наскільки це можливо найкоректніший ip клієнта
    public static function getIP(): string
        {
        $ipKeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ips = array_map('trim', explode(',', $_SERVER[$key]));
                foreach ($ips as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                        }
                    }
                }
            }
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }






    // Информация о городе і області по IP				   
    static function ipGetCity($ip)
        {
        $SxGeo = new SxGeo($_SERVER["DOCUMENT_ROOT"] . '/geo/SxGeo/SxGeoCity.dat');
        //Так ми отримуємо область, місто і інше
        $data = $SxGeo->getCityFull($ip);
        $res['iso'] = $data['region']['iso'];
        $res['region'] = $data['region']['name_en'];
        $res['city'] = $data['city']['name_en'];
        $res['country'] = $data['country']['iso'];
        return $res;
        // Так отримуємо лише місто
        // return $SxGeo->get($ip);
        }



    /* 
    Визначаємо по IP 
    Використовуємо для цього в залежності від другого параметру:
    $way = 'api' - зовнішне api
    $way = 'local' дві локальних бази
    $way = 'combo' дві локальних бази, але у випадку якщо вони дають різні країни, тоді йдемо до api

     */
    static function ipInfo($ip, $way = 'local')
        {
        if ($way == false or $way === 'local') {
            return self::ipGetCity($ip);
            }
        if ($way === 'api') {
            $ipApi = new IpApiClient();
            return $ipApi->getIpInfo($ip);
            }
        if ($way === 'combo') {
            $ipInfo = self::ipGetCountry($ip, $way);
            // Якщо отримано масив, значить його отримано по API
            if (is_array($ipInfo)) {
                return $ipInfo;
                }
            // Якщо отримано рядок, значить отримано лише країну з локальної бази
            // Тому отримуємо додаткову інформацю
            $res = self::ipGetCity($ip);
            return $res;
            }
        }


    // Визначаємо країну клієнта IP
    static function ipGetCountry($ip, $way = false)
        {

        $contryTabGeo = self::tabgeo_country_v4($ip);
        $SxGeo = new SxGeo($_SERVER["DOCUMENT_ROOT"] . '/geo/SxGeo/SxGeo.dat');

        $countrySxGeo = $SxGeo->getCountry($ip);


        if (!empty($contryTabGeo) and !empty($countrySxGeo)) {
            if ($contryTabGeo == $countrySxGeo) {
                return strtolower($contryTabGeo);
                } else {
                // В даному випадку ми хочемо звернутись до API 
                if ($way === 'combo') {
                    $ipApi = new IpApiClient();
                    return $ipApi->getIpInfo($ip);
                    }
                // SxGeo більш свіжа і ніби більш достовірна
                return strtolower($countrySxGeo);

                }
            } else if (!empty($contryTabGeo)) {
            return strtolower($contryTabGeo);
            } else if (!empty($countrySxGeo)) {
            return strtolower($countrySxGeo);
            }
        return false;

        }



    // tabgeo_country_v4 библиотека
    static function tabgeo_country_v4($ip)
        {

        $fh = fopen($_SERVER["DOCUMENT_ROOT"] . '/geo/tabgeo_country_v4/tabgeo_country_v4.dat', 'rb');

        $iso = array(
            'AD',
            'AE',
            'AF',
            'AG',
            'AI',
            'AL',
            'AM',
            'AO',
            'AQ',
            'AR',
            'AS',
            'AT',
            'AU',
            'AW',
            'AX',
            'AZ',
            'BA',
            'BB',
            'BD',
            'BE',
            'BF',
            'BG',
            'BH',
            'BI',
            'BJ',
            'BL',
            'BM',
            'BN',
            'BO',
            'BQ',
            'BR',
            'BS',
            'BT',
            'BV',
            'BW',
            'BY',
            'BZ',
            'CA',
            'CC',
            'CD',
            'CF',
            'CG',
            'CH',
            'CI',
            'CK',
            'CL',
            'CM',
            'CN',
            'CO',
            'CR',
            'CU',
            'CV',
            'CW',
            'CX',
            'CY',
            'CZ',
            'DE',
            'DJ',
            'DK',
            'DM',
            'DO',
            'DZ',
            'EC',
            'EE',
            'EG',
            'EH',
            'ER',
            'ES',
            'ET',
            'FI',
            'FJ',
            'FK',
            'FM',
            'FO',
            'FR',
            'GA',
            'GB',
            'GD',
            'GE',
            'GF',
            'GG',
            'GH',
            'GI',
            'GL',
            'GM',
            'GN',
            'GP',
            'GQ',
            'GR',
            'GS',
            'GT',
            'GU',
            'GW',
            'GY',
            'HK',
            'HM',
            'HN',
            'HR',
            'HT',
            'HU',
            'ID',
            'IE',
            'IL',
            'IM',
            'IN',
            'IO',
            'IQ',
            'IR',
            'IS',
            'IT',
            'JE',
            'JM',
            'JO',
            'JP',
            'KE',
            'KG',
            'KH',
            'KI',
            'KM',
            'KN',
            'KP',
            'KR',
            'KW',
            'KY',
            'KZ',
            'LA',
            'LB',
            'LC',
            'LI',
            'LK',
            'LR',
            'LS',
            'LT',
            'LU',
            'LV',
            'LY',
            'MA',
            'MC',
            'MD',
            'ME',
            'MF',
            'MG',
            'MH',
            'MK',
            'ML',
            'MM',
            'MN',
            'MO',
            'MP',
            'MQ',
            'MR',
            'MS',
            'MT',
            'MU',
            'MV',
            'MW',
            'MX',
            'MY',
            'MZ',
            'NA',
            'NC',
            'NE',
            'NF',
            'NG',
            'NI',
            'NL',
            'NO',
            'NP',
            'NR',
            'NU',
            'NZ',
            'OM',
            'PA',
            'PE',
            'PF',
            'PG',
            'PH',
            'PK',
            'PL',
            'PM',
            'PN',
            'PR',
            'PS',
            'PT',
            'PW',
            'PY',
            'QA',
            'RE',
            'RO',
            'RS',
            'RU',
            'RW',
            'SA',
            'SB',
            'SC',
            'SD',
            'SE',
            'SG',
            'SH',
            'SI',
            'SJ',
            'SK',
            'SL',
            'SM',
            'SN',
            'SO',
            'SR',
            'SS',
            'ST',
            'SV',
            'SX',
            'SY',
            'SZ',
            'TC',
            'TD',
            'TF',
            'TG',
            'TH',
            'TJ',
            'TK',
            'TL',
            'TM',
            'TN',
            'TO',
            'TR',
            'TT',
            'TV',
            'TW',
            'TZ',
            'UA',
            'UG',
            'UM',
            'US',
            'UY',
            'UZ',
            'VA',
            'VC',
            'VE',
            'VG',
            'VI',
            'VN',
            'VU',
            'WF',
            'WS',
            'YE',
            'YT',
            'ZA',
            'ZM',
            'ZW',
            'XA',
            'YU',
            'CS',
            'AN',
            'AA',
            'EU',
            'AP',
        );

        if (!function_exists('tabgeo_bs')) {
            function tabgeo_bs($data_array, $ip, $step)
                {
                $start = 0;
                $end = count($data_array) - 1;

                while (true) {
                    $mid = floor(($start + $end) / 2);
                    $unpack = $step ? unpack('Noffset/Cip/Ccc_id', "\x00$data_array[$mid]") : unpack('Cip/Ccc_id', $data_array[$mid]);

                    if ($unpack['ip'] == $ip)
                        return $unpack;
                    if ($end - $start < 0)
                        return $ip > $unpack['ip'] ? $unpack : $unpack_prev;
                    if ($unpack['ip'] > $ip)
                        $end = $mid - 1;
                    else
                        $start = $mid + 1;

                    $unpack_prev = $unpack;
                    }
                }
            }

        $ip_array = explode('.', $ip);

        fseek($fh, (($ip_array[0] * 256) + $ip_array[1]) * 4); // fseek($fh, ($ip_array[0] * 256 + $ip_array[1]) * 4);

        $index_bin = fread($fh, 4);
        $index = unpack('Noffset/Clength', "\x00$index_bin");
        if ($index['offset'] == 16777215)
            return $iso[$index['length']];

        fseek($fh, $index['offset'] * 5 + 262144);
        $bin = fread($fh, ($index['length'] + 1) * 5);
        $d = tabgeo_bs(str_split($bin, 5), $ip_array[2], TRUE);
        if ($d['offset'] == 16777215)
            return $iso[$d['cc_id']];

        if ($ip_array[2] > $d['ip'])
            $ip_array[3] = 255;
        fseek($fh, -(($d['offset'] + 1 + $d['cc_id']) * 2), SEEK_END);
        $bin = fread($fh, ($d['cc_id'] + 1) * 2);
        $d = tabgeo_bs(str_split($bin, 2), $ip_array[3], FALSE);
        return $iso[$d['cc_id']];
        }







    } // class 


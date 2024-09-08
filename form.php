<?php
// Очищуємо від небезпечних даних массив _POST
$okPost = core::safetyCleanArray($_POST);

// Порівнюємо масиви (початковий та очищений)
$testSec = core::arraysAreEqual($_POST, $okPost);

// Якщо масиви не однакові, то форма містила небезпечні дані
$badData = 'no';
if ($testSec == false) {
    // Дані що містяться в $okPost - ВЖЕ очищені від небезпеки
    // Тому ми можемо рухатись далі
    // Але фіксуємо факт небезпеки
    $badData = 'yes';
    // ЧИ ми можемо не пускати клієнта далі показавши йому якусь помилку
    // ЧИ ми можемо зберігти ip чи/та fp клієнта в окрему таблицю внісши клієнта тим самим в чорний список
    // варіантів реакції на $badData = 'yes' може бути багато
    }

// Після, трімім елементи масиву
array_walk($okPost, 'core::trva');

// Окремо перевіряємо і встановлюємо коментар замість пустого
// Чому ми це робимо? Через грубий фінальний тест checkFalse нижче
// Все це дозволяє використовувати ядро більш гнучко на різних шаблонах
if (empty($okPost['comments']) or $okPost['comments'] == 'undefined') {
    $okPost['comments'] = 'was not'; // відмічаємо, що користвувач не додавав коментар до форми
    // Також встановлюємо
    // Яка саме з двох форм була заповнена
    if ($okPost['comments'] == 'undefined') {
        $okPost['form'] = '1';
        } else {
        $okPost['form'] = '2';
        }
    }

// Провсяк зберігаємо зліпок email до його модифікації
$okPost['emailBefore'] = $okPost['email'];
// Доповнюємо дані
$okPost['landingUrl'] = $host;
$okPost['ip'] = $ip;
$okPost['city'] = $CityByIP;
$okPost['country'] = $CountryByIP;



// Поточний час
$currentDateTime = new DateTime();
// Використаємо для фіксації часу в заявці
$okPost['datetime'] = $currentDateTime->format('Y-m-d H:i:s');

// Копія для часу з віднятою добою
$dateTimeMinus1Day = clone $currentDateTime;
$dateTimeMinus1Day->modify('-1 day');
$holdEmailSuccess = $dateTimeMinus1Day->format('Y-m-d H:i:s');


// Для запобігання дублікатам, спаму, Ddos
// Знаходимо останній запис з таким IP за ДОБУ в журналі
$ipLastLogAction = core::ipLastLogAction($ip, $holdEmailSuccess, 'actions_log');



// Копія для часу з віднятими 5 секундами
$dateTimeMinus5Seconds = clone $currentDateTime;
$dateTimeMinus5Seconds->modify('-5 seconds');
$holdDuble = $dateTimeMinus5Seconds->format('Y-m-d H:i:s');





// Якщо з минулої заявки від поточного IP пройшло меньше 5 секунд, то взагалі ігноруємо таку заявку
// Це дозволяє нам захистити систему та базу від спаму та випадкових дублікатів
if (!empty($ipLastLogAction) and $holdDuble < $ipLastLogAction['datetime']) {
    $response = [
        "status" => "error",
        // Повідомляємо користувача модальним вікном, що він надто поспішає
        "response" => "You submitted an updated request too quickly after we rejected the previous one. 
        Please take more care in addressing the errors we pointed out when rejecting your last request.",
        "badData" => $badData,
    ];
    // Третім параметром вказуємо не логувати в базу цей спам
    // Це дискусійне рішення, але 
    // якщо спам продовжуватиметься, то ми так чи інакше отримуватимемо його кожні 5 секунд
    // тож багато не втрачаємо, а захищаємо базу від спам навантаження
    finish($okPost, $response, false);
    }

$str = core::arrayToKeyValueString($ipLastLogAction);


// Нижче ми захищаємо систему від повністю однакових ERROR заявок
// Якщо ця така сама як попередня відхилена за останю добу - цю теж відхиляємо
if (!empty($ipLastLogAction) and $ipLastLogAction['status'] == 'error') {
    $paKey = ['email', 'phone', 'last_name', 'first_name', 'comments', 'select_service', 'select_price'];
    $compareArrays = core::compareArraysByKeys($okPost, $ipLastLogAction, $paKey);
    $compareArrays = true;
    // Якщо за останю добу рівно така сама заявка була відхилена
    if ($compareArrays) {
        $response = [
            "status" => "error",
            // Повідомляємо користувача модальним вікном
            // Що дублікати мі не приймаємо
            "response" => "Our system does not allow duplicate requests within a 24-hour period. 
            Less than 24 hours ago, we rejected the exact same request from you for the following reason: ". $ipLastLogAction['response'],
            "badData" => $badData,
        ];

        finish($okPost, $response);
        }

    }


// TEST MODAL ERROR
$response = [
    "status" => "error", // Якщо потрібна імітація успіху 'ok'
    "response" => "TEST MODAL ERROR:" . $str, // що виведемо в модальному вікні
    "fieldError" => 'phone' // Яке поле підсвітимо помилкою
];
//finish($okPost, $response);




// Email перевіряємо окремо
if (empty($okPost['email'])) {
    // Формуємо масив з відповідю
    $response = [
        "status" => "error",
        "response" => "EMAIL IS NOT SPECIFIED",
        "badData" => $badData,
        "fieldError" => 'email' // Яке поле підсвітимо помилкою
    ];
    // Об'єднуємо всю інформацію що в нас є в спільний масив для логування
    // Логуємо масив в базу (в журнал)
    // Виводимо json на фронт
    finish($okPost, $response);
    }



// Ми відштовхуємося від логіки, що:
// 1. email це унікальний ідентифікатор користувача
// 2. під одним email можна зареєструвати лише один аккаунт
// В зв'язку з цим ми закриваємо всі можливості множинних використань одного email
// А також на додачу, дуже уважно валідуємо email
$test = new validateEmail($okPost['email']);
$testEmailError = $test->getError();
$okPost['email'] = $test->getEmail();

if (!empty($testEmailError)) {
    // Формуємо масив з відповідю
    $response = [
        "status" => "error",
        "response" => "EMAIL VALIDATION ERROR:" . $testEmailError[0],
        "badData" => $badData,
        "fieldError" => 'email' // Яке поле підсвітимо помилкою
    ];
    // Об'єднуємо всю інформацію що в нас є в спільний масив для логування
    // Логуємо масив в базу (в журнал)
    // Виводимо json на фронт
    finish($okPost, $response);
    }




// Якщо телефон не вказано, або це не схоже на телефон
if (empty($okPost['phone']) or strlen($okPost['phone']) < 11 or strlen($okPost['phone']) > 14 or !ctype_digit($okPost['phone'])) {
    // Формуємо масив з відповідю
    $response = [
        "status" => "error",
        "response" => "PHONE VALIDATION ERROR",
        "badData" => $badData,
        "fieldError" => 'phone' // Яке поле підсвітимо помилкою
    ];
    finish($okPost, $response);
    }




if (empty($okPost['last_name']) or core::isInvalidName($okPost['last_name'])) {
    // Формуємо масив з відповідю
    $response = [
        "status" => "error",
        "response" => "LAST NAME VALIDATION ERROR",
        "badData" => $badData,
        "fieldError" => 'last_name' // Яке поле підсвітимо помилкою
    ];
    finish($okPost, $response);
    }

if (empty($okPost['first_name']) or core::isInvalidName($okPost['first_name'])) {
    // Формуємо масив з відповідю
    $response = [
        "status" => "error",
        "response" => "FIRST NAME VALIDATION ERROR",
        "badData" => $badData,
        "fieldError" => 'first_name' // Яке поле підсвітимо помилкою
    ];
    finish($okPost, $response);
    }





// Зрештою
// Перевіряємо скопом всі дані що прийшли з _POST
// Перевірка яка створена лише для того, щоб поверхнево переконатись, що нам не відправлені пусті дані
// Якщо якісь дані чомусь не вказані - виходимо, бо це означає, що не всі необхідні поля заповнено
// Після того як ми 
// видалили/екранували можливі спроби зламу та обробили $okPost['comments'], в усіх полях має бути хоч щось
// Це дуже поверхнева перевірка яка покликана нейтралізувати (або висвітлити) помилки розробки/валідації форми
// Наприклад: 
// В поточному шаблоні на відкуп цій перевірці я відав два поля select_price та select_service
if (false === core::checkFalse($okPost)) {
    $response = [
        "status" => "error",
        "response" => "FORM IS NOT COMPLETELY COMPLETED",
        "badData" => $badData
    ];
    finish($okPost, $response);
    }





// Шукаємо, чи немає вже успішної заявки від цього email
$lastLead = core::lastLead($okPost['email'], $holdEmailSuccess);

// Якщо є - відхиляємо дублікат
// Бо ми ми дозволяємо відправляти заявки НЕ частіше ніж раз на добу
if(!empty($lastLead)){
    $response = [
        "status" => "error",
        "response" => "SUCH APPLICATION HAS ALREADY BEEN SUBMITTED DURING THE CURRENT DAY (ТАКАЯ ЗАЯВКА УЖЕ ПОДАВАЛАСЬ В ТЕКУЩИЕ СУТКИ)",
        "badData" => $badData
    ];
    finish($okPost, $response);
}



// Виходимо з успішною відповідю
$response = [
    "status" => "ok",
    "response" => "Lead successfully sent!",
    "page" => $successLink, // Посилання на яке перенаправляємо користувача
    "badData" => $badData
];

// Об'єднуємо всю інформацію що в нас є в спільний масив для логування
$data = array_merge($okPost, $response);
// Зберігаємо до бази
// В таблицю з успішними лідами leads
core::saveToTable($data, 'leads');

// В log журнал також зберігаємо дублікат 
// (якщо НЕ треба, можна третім параметром поставити false)
// Але логічно щоб журнал був нагляднішим і містив усі дані
finish($okPost, $response, true);




function finish($okPost, $response, $log = true)
    {
    // Об'єднуємо всю інформацію що в нас є в спільний масив для логування
    $data = array_merge($okPost, $response);
    // Логуємо масив в базу (в журнал actions_log)
    if ($log == true) {
        core::saveToTable($data,'actions_log');
        }
    // Закриваємо з'єднання з базою
    db::closeConnection();
    // Виводимо $response в json на фронт
    echo json_encode($response); // $response
    exit;
    }
// Який спільний класс у всіх полів які треба валідувати?
let field = 'form-control';
// Які дані про конверсію ми хочемо передати
let gtagData = { // в гугл аналітику
    'event_category': 'Form',
    'event_label': 'Contact Form',
    'value': 1
};
let fbqData = { // в фейсбук аналітику
    content_name: 'Contact Form',
    value: 1,
    currency: 'USD'
};




// Скидаємо деякі помилки та надаємо можливість натиснути на кнопку відправки
$('.' + field).bind('change keypress keydown keyup', function () {
    $('.response').hide().html('').removeClass('errorField');
    $('.uspeh').hide();
    $(this).removeClass('errorField');
    $('.sendForm').prop('disabled', false);

});
// Дозволяє скидати помилки на div полях що замінюють select 
$(document).on('click', '.bootstrap-select', function () {
    $('.response').hide().html('').removeClass('errorField');
    $('.uspeh').hide();
    $(this).removeClass('errorField');
    $('.sendForm').prop('disabled', false);
});


// отправка
$(document).on('click', '.sendForm', function () {

    // Блокуємо кнопку
    $(this).prop('disabled', true).addClass('disbtn');
    let oldbtn = $(this).html();
    $(this).html('SEND...');
    let btnObj = $(this);


    // Ховаем/скидаем всі помилки
    $('.response').hide();
    $('.' + field).removeClass('errorField');
    let errors = false;
    let check_firstName = false;
    let check_lastName = false;

    // Збираємо дані з форми
    // ТУТ ОПИСУЄМО УСІ ПОЛЯ ЯКІ ПОТРЕБУЮТЬ ВАЛІДАЦІІ
    // Загалом, в майбутньому тут можна зробити автоматичний збір полів і навіть відправку
    // Але все рівно треба докручувати мануально:
    // уважну валідацію, розбір та уважну до кожного поля валідацію на стороні сервера та запис до бази
    let commentsObj = $(this).parent().parent().find('.comments:first');
    let comments = $(commentsObj).val();
    let select_serviceObj = $(this).parent().parent().find('.select_service:first');
    let select_service = $(select_serviceObj).val();
    let select_priceObj = $(this).parent().parent().find('.select_price:first');
    let select_price = $(select_priceObj).val();
    let firstNameObj = $(this).parent().parent().find('.first_name:first');
    let firstName = $(firstNameObj).val();
    let lastNameObj = $(this).parent().parent().find('.last_name:first');
    let lastName = $(lastNameObj).val();
    let phoneObj = $(this).parent().parent().find('.phone:first');
    let phone = extractDigits($(phoneObj).val()); // Беремо номер БЕЗ + та будь яких інших символів
    let emailObj = $(this).parent().parent().find('.email:first');
    let email = $(emailObj).val();
    // Можливо ми хочемо зберегти саме те що клієнт ввів
    // Наприклад, щоб тестувати валідацію на сервері
    let emailOrigin = $(emailObj).val();

    // Тестуємо дані з форми

    // Тестуємо email
    let emailtest = testEmail(emailObj);
    if (!emailtest) {
        $(emailObj).addClass('errorField');
        errors = true;
    }

    // Якщо не обрано нічого в полі вибору
    if (select_service == 'selecttime') {
        $(this).parent().parent().find('.select_service').addClass('errorField');
        errors = true;
    }


    // Якщо в формі є поле для вводу коментарю
    // Якщо поле обов'язкове для заповнення, то треба прибрати умову comments != '' &&
    // Наразі поле валідується тільки якщо в нього почали щось вводити
    if (comments !== undefined && comments != '' && comments.length < 5) {
        $(commentsObj).addClass('errorField');
        errors = true;
    }

    // ! Всі name поля перевіряються на вміст only ЛАТИНИЦІ
    // ! Це легко допрацювати (якщо потрібна кирилиця наприклад), але по замовчуваню зробив only ЛАТИНИЦЮ
    if (firstName != '') {
        check_firstName = /^[A-Za-z\' \-]{2,25}$/.test(firstName);
        if (check_firstName == false) {
            $(firstNameObj).addClass('errorField');
            errors = true;
        }
    } else {
        $(firstNameObj).addClass('errorField');
        errors = true;
    }

    if (lastName != '') {
        check_lastName = /^[A-Za-z\' \-]{2,25}$/.test(lastName);
        if (check_lastName == false) {
            $(lastNameObj).addClass('errorField');
            errors = true;
        }
    } else {
        $(lastNameObj).addClass('errorField');
        errors = true;
    }


    // Тестуємо номер телефону
    // Тут би перевіряти стараніше, але поки так
    if (phone == '' || phone.length < 11 || phone.length > 14) {
        $(phoneObj).addClass('errorField');
        errors = true;
    }


    let formData = {
        select_price: select_price,
        select_service: select_service,
        comments: comments,
        first_name: firstName,
        last_name: lastName,
        phone: phone,
        // Можемо передавати додатково оригінальне введення emailOrigin:emailOrigin
        // Або використовувати оригінальне введення для тестування backend валідації
        email: emailOrigin
        // email: email,

    };


    // ФІНАЛЬНО
    // Проходимо по всім значенням в об'єкті
    // Якщо якийсь пустий, то не даємо відправити
    // ! Але тільки якщо в формі всі поля обов'язкові для заповнення
    // Це чисто спосіб фінального захисту від дурня-розробника який не перевірив вище всі поля як слід
    // Водночас це трохи псує користувацький досвід вспливаючим модальним вікном
    let finalCheckForm = true
    // finalCheckForm = hasEmptyValues(formData) // Цей рядок можна закоментувати і тоді виконуватись не буде
    if (finalCheckForm === false) {
        $('.response').show();
        $('.response,.response_id').html('FORM IS NOT COMPLETELY COMPLETED');
        $('#modalOverlay').fadeIn(300); // Плавна поява
    }


    // Якщо помилки знайдено - нічого не відправляємо
    if (errors == true) {$(this).html(oldbtn);return false;}



    //return false;

    // Блокуємо кнопку якщо все окей
    // Готуємось відправляти форму
    $(this).prop('disabled', true).addClass('disbtn');
    $(phoneObj).val("+" + phone);


    //
    console.log(formData);

    let request = $.post("/load.php", formData, function (data) {

        // $('html,body').animate({ scrollTop: 0 }, 'slow');
        console.log(data);
        btnObj.html(oldbtn);
        if (!data.page) {
            $('.response').html(data.response).show();
            $('#modalOverlay').fadeIn(200); // Плавна поява
        }
        // Можна показувати якесь ата-та за погані дані в формі
        if (data.badData == 'yes') {
            // ! На сторінці в даний момент НЕМАЄ такого блоку
            $('.bad').show();
        }

        if (data.status != 'ok') {
            $('.response').addClass('errorField');
            
            // Підсвічуємо поле в якому помилка на думку бекенду
            if (data.fieldError) {
                $('.' + data.fieldError).addClass('errorField');
            }
        }

        // У випадку успіху
        if (data.status == 'ok') {
            // Якщо на сторінці є тег відстеження конверсій 
            // Надсилаємо конверсію
            sendGtagLead(gtagData); // гугл аналітики
            sendFbqLead(fbqData); // фейсбук піксель

            // Make sens для запобігання відправки випадкових дублікатів
            // Очищуємо 
            $(phoneObj).val("");
            $(emailObj).val("");

            
            // В разі успіху, якщо вказано сторінку на яку треба направити
            if (data.page) {
                // автоматично переходимо на цільову сторінку
                window.location.href = data.page;
                // Або ми можемо показати модальне вікно з посиланням для переходу


            }

            



            // Ставимо таймаут на n sec
            let sec = 12;
            // На всі форми .sendForm
            $('.sendForm').html("TIMEOUT " + sec + " SEC");
            let timer2 = setInterval(function () {
                sec--;
                $('.sendForm').html("TIMEOUT " + sec + " SEC");
                if (sec < 1) {
                    $('.sendForm').prop("disabled", false).html(oldbtn);
                    clearInterval(timer2);
                }
            }, 1000);
        }


    }, 'json');


});





$(document).ready(function () {
    // Відкрити модальне вікно
    $('#openModal').on('click', function () {
        $('#modalOverlay').fadeIn(200); // Плавна поява
    });

    // Закрити модальне вікно
    $('.close-modal').on('click', function () {
        $('.response,.response_id').html('');
        $('#modalOverlay').fadeOut(150); // Плавне зникнення
    });

    // Закриття модального вікна при кліку на фон
    $('#modalOverlay').on('click', function (e) {
        if (e.target === this) {
            $('.response,.response_id').html('');
            $(this).fadeOut(200);
        }
    });
});

// Функція потрібна для фінальної перевірки даних в деяких формах
// ! Але лише в тих, де всі поля обов'язкові для заповнення
function hasEmptyValues(formData) {
    // Проходимо по всім значенням в об'єкті
    for (let key in formData) {
        if (formData.hasOwnProperty(key) && formData[key] === '') {
            return false; // Знайдено пусте значення
        }
    }
    return true; // Всі значення заповнені
}

// Корисно для валідації номеру телефона та очищення значення від того що дав placeholder
function extractDigits(input) {
    // Використовуємо регулярний вираз для видалення всіх символів, окрім цифр
    const result = input.replace(/\D/g, ''); // \D - означає "не цифра"
    return result; // Повертаємо залишок
}


// Функція для отримання значення куки за її назвою
function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

// Надсилаємо в гугл аналітику інформацію про конверсію
function sendGtagLead(gtagData) {
    if (typeof gtag === 'function') {
        // Передаємо інформацію в гугл аналітику
        gtag('event', 'lead', gtagData);
    }
    return true;
}

// Надсилаємо інформацію про конверсію в Facebook Pixel
function sendFbqLead(fbqData) {
    if (typeof fbq === 'function') {
        // Передаємо інформацію в Facebook Pixel
        fbq('track', 'Lead', fbqData);
    }
    return true;
}
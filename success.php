<!DOCTYPE html>
<html lang="uk">
<!-- Basic -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>SUCCESS</title>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
</head>

<body>
    <div style="max-width:400px;margin:20px auto;font-family: sans-serif">
        <h3>The form has been successfully submitted</h3>
        Ви опинились на цій сторінці тому що форма яку ви заповнили - успішно прийнята, а цей урл вказано в ../load.php
        в якості success url.
        <br><br>
        Якщо б success url не було вказано, то Ви б нікуди не переадресовувались, а просто побачили б модальне вікно з
        інформацією про успіх.

    </div>
</body>

</html><?php
echo 'The form has been successfully submitted';
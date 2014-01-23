<?php
//Принимаем данные
$name=$_POST['name'];
$age=$_POST['age'];
$monthmoney=$_POST['monthmoney'];
$reg = $_POST['reg']; if($reg=="Yes"){$reg="Да";}else{$reg="Нет";}
$summ=$_POST['summ'];
$phone=$_POST['phone'];
$credit = $_POST['credit']; if($credit=="Yes"){$credit="Да";}else{$credit="Нет";}
$prosrochka = $_POST['prosr']; if($prosrochka=="Yes"){$prosrochka="Да";}else{$prosrochka="Нет";}

$to = "info@divinat.net";
$subject = "Заявка на кредит с сайта http://zayavka.divinat.net";


$message = "Письмо отправлено с сайта.\r\n";
$message .= "Пользователь указал: \r\n";
$message .= "Имя: ".htmlspecialchars($name)."\r\n";
$message .= "Возраст: ".htmlspecialchars($age)."\r\n";
$message .= "Ежемесячный доход: ".htmlspecialchars($monthmoney)."\r\n";
$message .= "Регистрация в СПб или ЛО: ".htmlspecialchars($reg)."\r\n";
$message .= "Требуемая сумма: ".htmlspecialchars($summ)."\r\n";
$message .= "Телефон: ".htmlspecialchars($phone)."\r\n";
$message .= "Есть ли у вас действующие кредиты: ".htmlspecialchars($credit)."\r\n";
$message .= "Есть ли у вас текущие просрочки по кредитам?: ".htmlspecialchars($prosrochka)."\r\n";

$header = "From: Zayavka s zayavka.divinat.net<admin@allavtovo.ru>\r\n"; 
$header.= "MIME-Version: 1.0\r\n"; 
$header.= "Content-Type: text/plain; charset=utf-8\r\n"; 
$header.= "X-Priority: 1\r\n"; 

mail($to, $subject, $message, $header);
?>
<meta http-equiv="refresh" content="0; url=http://zayavka.divinat.net/index2.html" />

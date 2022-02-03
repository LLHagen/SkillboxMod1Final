<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';

$order = [];
$error = [];
$errorRequired = 'Не все обязятельные поля заполнены';
$sum = 0;
if (!empty($_POST['surname'])) {
    $order['surname'] = htmlspecialchars($_POST['surname']);
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['name'])) {
    $order['name'] = htmlspecialchars($_POST['name']);
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['thirdName'])) {
    $order['thirdName'] = htmlspecialchars($_POST['thirdName']);
} else {
    $order['thirdName'] = NULL;
}
if (!empty($_POST['phone'])) {
    $order['phone'] = htmlspecialchars($_POST['phone']);

    if (!preg_match('~^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{6,10}$~', $order['phone'])) {
        $error['phone'] = "Неверный формат телефона";
    }
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['email'])) {
    $order['email'] = htmlspecialchars($_POST['email']);
    if (!preg_match("/[0-9a-z]+@[a-z]/", $order['email'])) {
        $error['email'] = "Неверный формат почты";
    }
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['pay'])) {
    $pay = htmlspecialchars($_POST['pay']);
    $order['pay'] = $pay;
}
if (!empty($_POST['city'])) {
    $city = htmlspecialchars($_POST['city']);
    $order['city'] = $city;
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['street'])) {
    $street = htmlspecialchars($_POST['street']);
    $order['street'] = $street;
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['home'])) {
    $home = htmlspecialchars($_POST['home']);
    $order['home'] = $home;
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['aprt'])) {
    $aprt = htmlspecialchars($_POST['aprt']);
    $order['aprt'] = $aprt;
} else {
    $error['required'] = $errorRequired;
}
if (!empty($_POST['comment'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $order['comment'] = $comment;
} else {
    $order['comment'] = NULL;
}
if (!empty($_POST['productId'])) {
    $productId = (int) $_POST['productId'];
    $order['productId'] = $productId;
} else {
    $error['order'] = 'Ошибка заказа';
}
if (!empty($_POST['delivery'])) {
    $delivery = htmlspecialchars($_POST['delivery']);
    $order['delivery'] = $delivery;
} else {
    $error['order'] = 'Ошибка заказа';
}
if (empty($error)){
    // Получить информацию о продукте по айди
    $order['productInfo'] = getProductInfo($order['productId']);
    $order['productInfo'] = $order['productInfo'][0];
    
    if ($order['productInfo']['price'] < 2000) {
        $sum+=$order['productInfo']['price'] + $deliveryPrice;
    } else {
        $sum+=$order['productInfo']['price'];
    }

    $order['price'] = $sum;
    $add = addOrder($order);

    if ($add == true){
        $data['add'] = $add;
    } else {
        $error['add'] = 'err' . $add;
    }
}
$data['order'] = $order;
if (!empty($error)) {
    $data['error'] = $error;
}
echo json_encode($data);
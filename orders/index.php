<?php
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['auth']) && !$_SESSION['auth']) {
        header ('Location: /admin/');
        die();
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';
    if (accessUserForAdress() != true){
        include $_SERVER['DOCUMENT_ROOT'] . '/template/noAccess.php';
    } else {
?>
<main class="page-order">
    <h1 class="h h--1">Список заказов</h1>
    <ul class="page-order__list">
<?php
    $orders = getOrders();
    foreach ($orders as $order) {
        // var_dump($order);
        $id = $order['id'];
        $name = $order['name'];
        $surname = $order['surname'];
        $thirdName = $order['thirdName'];
        $phone = $order['phone'];
        $email = $order['email'];
        if (!empty($order['payName'])) {
            $pay = $order['payName'];
        } else {
            $pay = 'Наличными(авто)';
        }
        $pay = $order['payName'];
        $productId = $order['productId'];
        $price = $order['price'];
        if (empty($order['delivery'])){
            $delivery = 'Самовывоз';
        } else {
            $delivery = 'Доставка';
        }
        $city = $order['city'];
        $street = $order['street'];
        $home = $order['home'];
        $aprt = $order['aprt'];
        $comment = $order['comment'];
        if (!empty($order['status'])) {
            $status = '<span class="order-item__info order-item__info--yes">Выполнено</span>';
        } else {
            $status = '<span class="order-item__info order-item__info--no">Не выполнено</span>';
        }
?>  
        <li class="order-item page-order__item">
            <div class="order-item__wrapper">
                <div class="order-item__group order-item__group--id">
                    <span class="order-item__title">Номер заказа</span>
                    <span class="order-item__info order-item__info--id"><?= $id ?></span>
                </div>
                <div class="order-item__group">
                    <span class="order-item__title">Сумма заказа</span>
                    <?= numberPriceFormat($price) ?>
                </div>
                <button class="order-item__toggle"></button>
            </div>
            <div class="order-item__wrapper">
                <div class="order-item__group order-item__group--margin">
                    <span class="order-item__title">Заказчик</span>
                    <span class="order-item__info"><?= $order['surname'] . ' ' . $order['name'] . ' ' . $order['thirdName'] ?></span>
                </div>
                <div class="order-item__group">
                    <span class="order-item__title">Номер телефона</span>
                    <span class="order-item__info"><?= $phone ?></span>
                </div>
                <div class="order-item__group">
                    <span class="order-item__title">Способ доставки</span>
                    <span class="order-item__info"><?= $delivery ?></span>
                </div>
                <div class="order-item__group">
                    <span class="order-item__title">Способ оплаты</span>
                    <span class="order-item__info"><?= $pay ?></span>
                </div>
                <div class="order-item__group order-item__group--status">
                    <span class="order-item__title">Статус заказа</span>
                    <?= $status ?>
                    <button class="order-item__btn">Изменить</button>
                </div>
            </div>
            <div class="order-item__wrapper">
                <div class="order-item__group">
                    <span class="order-item__title">Адрес доставки</span>
                    <span class="order-item__info">г. <?= $city ?>, ул. <?= $street ?>, д.<?= $home ?>, кв. <?= $aprt ?></span>
                </div>
            </div>
            <div class="order-item__wrapper">
                <div class="order-item__group">
                    <span class="order-item__title">Комментарий к заказу</span>
                    <span class="order-item__info"><?= $comment ?></span>
                </div>
            </div>
        </li>
<?php } ?>
    </ul>
</main>
<?php
        }
    }
include_once $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
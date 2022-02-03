<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['auth']) && !$_SESSION['auth']) {
    header ('Location: /admin/');
    die();
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';
 
    if (accessUserForAdress() != true){
        include $_SERVER['DOCUMENT_ROOT'] . '/template/noAccess.php';
    } else {
        $products = getProducts();
// Статичная страница, содержит информацию о доставке, стоимости доставки и стоимости заказа, при которой доставка бесплатна. Берется из настроек.
?>

<main class="page-products">
    <h1 class="h h--1">Товары</h1>
    <a class="page-products__button button" href="/products/add.php/">Добавить товар</a>
    <div class="page-products__header">
        <span class="page-products__header-field">Название товара</span>
        <span class="page-products__header-field">ID</span>
        <span class="page-products__header-field">Цена</span>
        <span class="page-products__header-field">Категория</span>
        <span class="page-products__header-field">Новинка</span>
    </div>
    <ul class="page-products__list">


<?php 
if (!empty($products)) {
    foreach ($products as $product) { ?>
        <?php if (isset($product['new'])){
            $new = 'Да';
        } else {
            $new = 'Нет';
        }
        ?>
        <li class="product-item page-products__item">
            <b class="product-item__name"><?= $product['name'] ?></b>
            
            <input type="hidden" class="custom-form__input" name="id" id="productId" value="<?= $product['id'] ?>">

            <span class="product-item__field"><?= $product['id'] ?></span>
            <span class="product-item__field"><?= numberPriceFormat($product['price']) ?></span>
            <span class="product-item__field"><?= $product['category'] ?></span>
            <span class="product-item__field"><?= $new ?></span>
            <a href="/products/add.php<?= "/?id=" . $product['id'] ?>" class="product-item__edit" aria-label="Редактировать"></a>
            <button class="product-item__delete"></button>
        </li>
<?php }
} else {
    echo('404');
} ?>

    </ul>
</main>

<?php
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
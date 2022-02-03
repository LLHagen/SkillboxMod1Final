<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/sessionStart.php';

if (!isset($_SESSION['auth']) && $_SESSION['auth'] != true) {
    header ('Location: /admin/');
    die();
} else {

    if (empty($_GET['id'])) {
        // текст страницы при добавлении
        $title = 'Добавление товара';
        $nameButton = 'Добавить товар';
        $textResult = 'Товар успешно добавлен';
    } else {
        // текст страницы при изменении
        $title = 'Изменение товара';
        $nameButton = 'Изменить товар';
        $textResult = 'Товар успешно изменен';
    }

    include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';

    if (accessUserForAdress() != true){
        include $_SERVER['DOCUMENT_ROOT'] . '/template/noAccess.php';
    } else {

    include_once $_SERVER['DOCUMENT_ROOT'] . '/include/loadFilesRequirements.php';

    $successResult = 'hidden=""';
    $hiddenForm = false;

    if (empty($_GET['id'])) {
        // последний айди товаров
        $lastId = useSelectQuery('SELECT MAX(id) FROM products');
        $lastId = (int) $lastId[0]['MAX(id)'];
        $lastId++;
        $product['id'] = $lastId;

        if (isset($_POST['new']) && $_POST['new'] == 'on') {
            $checkedNew = 'checked';
        }
        if (isset($_POST['sale']) && $_POST['sale'] == 'on') {
            $checkedSale = 'checked';
        }
    } else {
        $product['id'] = (int) $_GET['id'];
        $productOld = getProductInfo( (int) $_GET['id']);

        if ($productOld) {
            $productOld = $productOld[0];

            if ($productOld['new'] == true) {
                $checkedNew = 'checked';
            }
            if ($productOld['sale'] == true) {
                $checkedSale = 'checked';
            }
        }

        $urlGet = '?id=' . $product['id'];
    }

    if ($_POST) {
        // массив ошибок
        $error = [];
        // Наименование товара
        if (!empty($_POST['product-name'])) {
            $product['name'] = $_POST['product-name'];
        } else {
            $error[] = 'Наименование товара не может быть пустым';
        }
        // проверка обязательных полей на пустоту
        if (!empty($_POST['product-price'] && is_int( (int) $_POST['product-price']))) {
            $product['price'] =  (int) $_POST['product-price'];
        } else {
            $error[] = 'Ошибка стоимости товара';
        }
        if (isset($_POST['category'])) {
            foreach ($_POST['category'] as $key => $value) {
                $product['category'][] = htmlspecialchars($value);
            }
        }
        if (isset($_POST['new']) && $_POST['new'] == 'on') {
            $product['new'] = 1;
        } else {
            $product['new'] = 0;
        }
        if (isset($_POST['sale']) && $_POST['sale'] == 'on') {
            $product['sale'] = 1;
        } else {
            $product['sale'] = 0;
        }

        // загрузка файла на сервер
        if ($_FILES["product-photo"]['error'] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES["product-photo"]["tmp_name"];

            $basename = (explode(".", basename($_FILES["product-photo"]["name"])));
            $endName =  '.' . end($basename);

            // имя кратинки на сервере
            $name = 'product-' . $product['id'] . $endName;

            $uploadDir = $_SERVER['DOCUMENT_ROOT'] .'/img/products/';

            if(!in_array(mime_content_type($_FILES["product-photo"]['tmp_name']), $types ?? '')){
                $errorType = "Тип файла " . $_FILES["product-photo"]['name'] . "  не соответствует разрешенным (" . implode(",", $types) . ")";
                $error[] = $errorType;
            }
            if($_FILES["product-photo"]['size'] > ($size ?? 0)){
                $errorSize = "Размер файла " . $_FILES["product-photo"]['name'] . ' (' . $_FILES["product-photo"]['size'] . ") превышает допустимые " . $size;
                $error[] = $errorSize;
            }
            if (empty($error)){
                if(move_uploaded_file($tmp_name, $uploadDir . $name)){
                    $product['img'] = $name;
                } else {
                    $errorCopy = "Ошибка при загрузке. Файл " . $_FILES["product-photo"]['name'] . " не был загружен(" . $uploadDir . '' . $name . ')';
                    $error[] = $errorCopy;
                }
            }
        } elseif (!empty($_FILES["product-photo"]["name"])) {
            $errorLoad = "Произошла ошибка загрузки файла  " . $_FILES["product-photo"]['name'] . " код ошибки - " . $_FILES["product-photo"]['error'];
            $error[] = $errorLoad;
        }
        if (empty($_GET['id']) && empty($product['img'])) {

            if (empty($product['img'])) {
                $error[] = "Загрузите изображение или обратитесь к Администратору";
            }
        }
        if (empty($error)){
            if (empty($_GET['id'])) {
                $result = addProduct($product);
            } else {
                $result = updateProduct($product);
            }
            if ($result != true) {
                $textResult = 'Ошибка операции обратитесь к Администратору';
            }
            $successResult = '';

            $hiddenForm = 'hidden=""';
        }
    }
?>

<main class="page-add">
    <h1 class="h h--1"><?= $title ?></h1>

<?php
if (!empty($error)) {
    foreach ($error as $err) {
?>

        <p style="color: red"><?= $err ?></p>

<?php
    }
}
?>

    <form class="custom-form" action="/products/add.php/<?= $urlGet ?? '' ?>" method="post"  enctype='multipart/form-data' <?= $hiddenForm ?>>
    <fieldset class="page-add__group custom-form__group">

        <input type="hidden" class="custom-form__input" name="id" id="productID" value="<?= $product['id'] ?>">

        <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
        <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
        <input type="text" class="custom-form__input" name="product-name" id="product-name" value="<?= $productOld['name'] ?? $_POST['product-name'] ?? '' ?>">
        <p class="custom-form__input-label">
            Название товара
        </p>
        </label>
        <label for="product-price" class="custom-form__input-wrapper">
        <input type="text" class="custom-form__input" name="product-price" id="product-price" value="<?= $productOld['price'] ?? $_POST['product-price'] ?? '' ?>">
        <p class="custom-form__input-label">
            Цена товара
        </p>
        </label>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
        <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
        <ul class="add-list">
        <li class="add-list__item add-list__item--add" <?= (!empty($productOld['img'])) ? ' hidden' : '' ?> >
            <input type="file" name="product-photo" id="product-photo" hidden=""  accept="<?= implode(',', $types) ?>">
            <label for="product-photo">Добавить фотографию</label>

            <?php if (!empty($productOld['img'])) { ?>
                <li class="add-list__item add-list__item--active"><img src="/img/products/<?= $productOld['img'] ?>"></li>
            <?php } ?>

        </li>
        </ul>
    </fieldset>
    <fieldset class="page-add__group custom-form__group">
        <legend class="page-add__small-title custom-form__title">Раздел</legend>
        <div class="page-add__select">
            <select name="category[]" class="custom-form__select" multiple>
                <option hidden="">Название раздела</option>

            <?php 
            $categories = getCategories();
            $productOld['category'] = explode(', ', $productOld['category'] ?? '');

            foreach ($categories as $category) { 
                $selected = '';
                if (isset($product)) {
                    if (
                            (!empty($_GET['id']) && (in_array($category['title'] ?? [], $productOld['category'] ?? []))) ||
                            (isset($_POST['category']) && (in_array($category['id'], $_POST['category'])))
                    ) {
                        $selected = 'selected';
                    }
                } else {
                    if (in_array($category['title'], $productOld['category']) && !empty($_GET['id'])) {
                        $selected = 'selected';
                    }
                } ?>
                <option value="<?= $category['id'] ?>"  <?= $selected ?> ><?= $category['title'] ?></option>
        <?php } ?>

            </select>
        </div>
        <input type="checkbox" name="new" id="new" class="custom-form__checkbox" <?= $checkedNew ?? '' ?> >
        <label for="new" class="custom-form__checkbox-label">Новинка</label>
        <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" <?= $checkedSale ?? '' ?> >
        <label for="sale" class="custom-form__checkbox-label">Распродажа</label>

    </fieldset>
    <button class="button" type="submit"><?= $nameButton ?></button>
    </form>
    <section class="shop-page__popup-end page-add__popup-end" <?= $successResult ?>>
    <div class="shop-page__wrapper shop-page__wrapper--popup-end">
        <h2 class="h h--1 h--icon shop-page__end-title"><?= $textResult ?></h2>
    </div>
    </section>

</main>

<?php
    }
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';

// ----------------------------------------------------
//     В меню категорий выводятся все категории товаров на сайте, при клике на категорию происходит переход на страницу каталога, на которой отображаются только товары, привязанные к выбранной категории.
//     тут под вопросом или реализовать на JS
//     При изменении параметров фильтрации происходит изменение содержимого каталога, отображаются только товары из текущего активного раздела, подходящие под фильтр (совет: для передачи данных фильтра будет удобно использовать get-параметры).
//     тут под вопросом или реализовать на JS
//         При изменении диапазона цен должны быть выбраны только товары, подходящие под выбранный диапазон.
//     В списке товаров выводятся товары в виде блоков, каждый блок содержит: изображение товара, название товара и цену товара, при клике на товар происходит переход на страницу оформления заказа.
// ----------------------------------------------------


$countOfItemsPerPage = 9;
$page = getPage();
$result = getProductsForPage($countOfItemsPerPage);

$products = false;
if ($result) {
    $countProducts = $result['count'];
    $pagesCount = $result['pagesCount'];
//    var_dump($pagesCount);
    $products = $result['products'];
}

if (isset($_POST['surname']) && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['productId'])) {

    // фамилия
    $surname = htmlspecialchars($_POST['surname']);
    //  Имя
    $name = htmlspecialchars($_POST['name']);
    // Телефон
    $phone = htmlspecialchars($_POST['phone']);
    // Почта
    $email = htmlspecialchars($_POST['email']);
    // id продукта
    $productId = htmlspecialchars($_POST['productId']);
    $thirdName = NULL;
    // Отчество
    if (isset($_POST['thirdName'])) {
        $thirdName = htmlspecialchars($_POST['thirdName']);
    }
    $delivery = htmlspecialchars($_POST['delivery']);
    $pay = htmlspecialchars($_POST['pay']);
}

?>
    <main class="shop-page">
        <header class="intro">
            <div class="intro__wrapper">
                <h1 class=" intro__title">COATS</h1>
                <p class="intro__info">Collection 2018</p>
            </div>
        </header>
        <section class="shop container">
            <section class="shop__filter filter">
                <form method="get" action="/">
                    <div class="filter__wrapper">
                        <b class="filter__title">Категории</b>
                        <ul class="filter__list">
                            <li>
                                <a class="filter__list-item" href="/?category=*">Все</a>
                            </li>
                            <?php
                            $categories = getCategories();
                            if (!empty($categories)) {
                                foreach ($categories as $category) { ?>
                                    <li>
                                        <a class="filter__list-item"
                                           href="/?category=<?= $category['name'] ?>"><?= $category['title'] ?></a>
                                    </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="filter__wrapper">
                        <b class="filter__title">Фильтры</b>
                        <div class="filter__range range">
                            <span class="range__info">Цена</span>
                            <div class="range__line" aria-label="Range Line"></div>
                            <div class="range__res">
                                <?php
                                // получаем минимальную и максимальную цены для фильтра цен
                                $rangePrice = getMinAndMaxPriceProducts();
                                $maxPrice = numberPriceFormat($rangePrice[0]['max']);
                                $minPrice = numberPriceFormat($rangePrice[0]['min']);
                                ?>
                                <span class="range__res-item min-price"><?= $minPrice ?></span>
                                <span class="range__res-item max-price"><?= $maxPrice ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- скрытые поля для данных из фильтра цены range__line -->
                    <input id="minPrice" type="hidden" name="minPrice" value="<?= $rangePrice[0]['min'] ?>">
                    <input id="maxPrice" type="hidden" name="maxPrice" value="<?= $rangePrice[0]['max'] ?>">

                    <fieldset class="custom-form__group">
                        <input type="checkbox" name="new" id="new"
                               class="custom-form__checkbox"<?php if (!empty($_GET['new'])) echo "checked"; ?>>
                        <label for="new" class="custom-form__checkbox-label custom-form__info" style="display: block;">Новинка</label>
                        <input type="checkbox" name="sale" id="sale"
                               class="custom-form__checkbox" <?php if (!empty($_GET['sale'])) echo "checked"; ?> >
                        <label for="sale" class="custom-form__checkbox-label custom-form__info" style="display: block;">Распродажа</label>
                    </fieldset>
                    <button class="button" type="submit" style="width: 100%">Применить</button>
                </form>
            </section>
            <div class="shop__wrapper">

                <?php include $_SERVER['DOCUMENT_ROOT'] . '/template/sort.php'; ?>

                <section class="shop__list">
                    <?php if (!empty($products)) {
                        foreach ($products as $product) { ?>
                            <article class="shop__item product" onclick="buyProduct(this)"
                                     data-id="<?= $product['id'] ?>" tabindex="0">
                                <div class="product__image">
                                    <img src="img/products/<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
                                </div>
                                <p class="product__name"><?= $product['name'] ?></p>
                                <span class="product__price"><?= numberPriceFormat($product['price']) ?></span>
                            </article>
                        <?php }
                    } else {
                        echo('Товары не найдены');
                    } ?>
                </section>

                <ul class="shop__paginator paginator">
                    <?php printPages($pagesCount ?? 0); ?>
                </ul>

            </div>
        </section>

        <section class="shop-page__order" hidden="">
            <div class="shop-page__wrapper">
                <h2 class="h h--1">Оформление заказа</h2>

                <form action="/" method="post" class="custom-form js-order">
                    <input type="hidden" name="productId" value="">
                    <fieldset class="custom-form__group">
                        <legend class="custom-form__title">Укажите свои личные данные</legend>
                        <p class="custom-form__info">
                            <span class="req">*</span> поля обязательные для заполнения
                        </p>
                        <div class="custom-form__column">
                            <label class="custom-form__input-wrapper" for="surname">
                                <input id="surname" class="custom-form__input" type="text" name="surname" required="">
                                <p class="custom-form__input-label">Фамилия <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="name">
                                <input id="name" class="custom-form__input" type="text" name="name" required="">
                                <p class="custom-form__input-label">Имя <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="thirdName">
                                <input id="thirdName" class="custom-form__input" type="text" name="thirdName">
                                <p class="custom-form__input-label">Отчество</p>
                            </label>
                            <label class="custom-form__input-wrapper" for="phone">
                                <input id="phone" class="custom-form__input" type="tel" name="phone" required="">
                                <p class="custom-form__input-label">Телефон <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="email">
                                <input id="email" class="custom-form__input" type="email" name="email" required="">
                                <p class="custom-form__input-label">Почта <span class="req">*</span></p>
                            </label>
                        </div>
                    </fieldset>
                    <fieldset class="custom-form__group js-radio">
                        <legend class="custom-form__title custom-form__title--radio">Способ доставки</legend>
                        <input id="dev-no" class="custom-form__radio" type="radio" name="delivery" value="dev-no"
                               checked="">
                        <label for="dev-no" class="custom-form__radio-label">Самовывоз</label>
                        <input id="dev-yes" class="custom-form__radio" type="radio" name="delivery" value="dev-yes">
                        <label for="dev-yes" class="custom-form__radio-label">Курьерная доставка</label>
                    </fieldset>
                    <div class="shop-page__delivery shop-page__delivery--no">
                        <table class="custom-table">
                            <caption class="custom-table__title">Пункт самовывоза</caption>
                            <tr>
                                <td class="custom-table__head">Адрес:</td>
                                <td>Москва г, Тверская ул,<br> 4 Метро «Охотный ряд»</td>
                            </tr>
                            <tr>
                                <td class="custom-table__head">Время работы:</td>
                                <td>пн-вс 09:00-22:00</td>
                            </tr>
                            <tr>
                                <td class="custom-table__head">Оплата:</td>
                                <td>Наличными или банковской картой</td>
                            </tr>
                            <tr>
                                <td class="custom-table__head">Срок доставки:</td>
                                <td class="date">13 декабря—15 декабря</td>
                            </tr>
                        </table>
                    </div>
                    <div class="shop-page__delivery shop-page__delivery--yes" hidden="">
                        <fieldset class="custom-form__group">
                            <legend class="custom-form__title">Адрес</legend>
                            <p class="custom-form__info">
                                <span class="req">*</span> поля обязательные для заполнения
                            </p>
                            <div class="custom-form__row">
                                <label class="custom-form__input-wrapper" for="city">
                                    <input id="city" class="custom-form__input" type="text" name="city">
                                    <p class="custom-form__input-label">Город <span class="req">*</span></p>
                                </label>
                                <label class="custom-form__input-wrapper" for="street">
                                    <input id="street" class="custom-form__input" type="text" name="street">
                                    <p class="custom-form__input-label">Улица <span class="req">*</span></p>
                                </label>
                                <label class="custom-form__input-wrapper" for="home">
                                    <input id="home" class="custom-form__input custom-form__input--small" type="text"
                                           name="home">
                                    <p class="custom-form__input-label">Дом <span class="req">*</span></p>
                                </label>
                                <label class="custom-form__input-wrapper" for="aprt">
                                    <input id="aprt" class="custom-form__input custom-form__input--small" type="text"
                                           name="aprt">
                                    <p class="custom-form__input-label">Квартира <span class="req">*</span></p>
                                </label>
                            </div>
                        </fieldset>
                    </div>
                    <fieldset class="custom-form__group shop-page__pay">
                        <legend class="custom-form__title custom-form__title--radio">Способ оплаты</legend>
                        <input id="cash" class="custom-form__radio" type="radio" name="pay" value="cash">
                        <label for="cash" class="custom-form__radio-label">Наличные</label>
                        <input id="card" class="custom-form__radio" type="radio" name="pay" value="card" checked="">
                        <label for="card" class="custom-form__radio-label">Банковской картой</label>
                    </fieldset>
                    <fieldset class="custom-form__group shop-page__comment">
                        <legend class="custom-form__title custom-form__title--comment">Комментарии к заказу</legend>
                        <textarea class="custom-form__textarea" name="comment"></textarea>
                    </fieldset>
                    <button class="button" type="submit">Отправить заказ</button>
                </form>

            </div>
        </section>

        <section class="shop-page__popup-end" hidden="">
            <div class="shop-page__wrapper shop-page__wrapper--popup-end">
                <h2 class="h h--1 h--icon shop-page__end-title">Спасибо за заказ!</h2>
                <p class="shop-page__end-message">Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время</p>
                <button class="button">Продолжить покупки</button>
            </div>
        </section>
    </main>

<?php
include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
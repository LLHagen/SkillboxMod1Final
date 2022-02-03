<?php

function sortMenuAscending($a, $b)
{
    return ($a["sort"] < $b["sort"]) ? -1 : 1;
}
function sortMenuDescending($a, $b)
{
    return ($a["sort"] > $b["sort"]) ? -1 : 1;
}

/**
* функция для вывода меню
* @param array $menu массив меню
* @param string $ulClass класс тега <ul> в формате string
* @param string $sortBy режим сортировки sortMenuAscending(по возрастанию) или sortMenuDescending(по убыванию) (по умолчанию сортирует по возрастанию $item['path'])
*/
function printMenu($menu, $navClass, $ulClass, $sortBy = 'sortMenuAscending')
{
    if (!empty($menu)) {

        usort($menu, $sortBy);
        include $_SERVER['DOCUMENT_ROOT'] . '/template/menu.php';
    }
}
/**
* Функция возвращает заголовок меню найденное в массиве меню $menu
* @param array $menu массив меню
* @return string возвращает заголовок текущей вкладки меню
*/
function getRouteTitle($menu)
{
    foreach ($menu as $item) {
        if (isCurrentUrl($item['path'])) {
        	if ($item['title'] == 'Главная') {
        		return 'Fashion';
        	}
            return $item['title'];                
        }
    }
    return false;
}
/**
* Функция сравнивает путь указанный в $path с путем в URL
* @param strng $path путь на сервере который нужно сравнить с текущим адресом страницы
* @return bool результат сравнения
*/
function isCurrentUrl($path)
{
    if ($path == $_SERVER['REQUEST_URI']) {
        return true;
    } else {
        if ($path == parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) && $_SERVER['REQUEST_URI'] != '/?new=on'  && $_SERVER['REQUEST_URI'] != '/?sale=on'){
            return true;
        }
    }
    return false;
}
/**
* функция добавляет класс активного пункта меню
* @param string $link ссылка текущего пункта меню для вывода в меню из массива $menu[номер пукта меню][path]
* @return string возвращает класс активного пункта меню
*/
function addClassActiveLink($link)
{
    if (isCurrentUrl($link) == true) {
        return " active";
    }
}

/**
* функция для подключения к БД
* @return object $connect представляющий подключение к серверу MySQL, или FALSE в случае возникновения ошибки
*/
function getConnection()
{
    static $connect;

    if (empty($connect)){
        if (!defined('DB_HOST')) {
            require $_SERVER['DOCUMENT_ROOT'] . '/config.php';
        }

        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE) or die("Ошибка " . mysqli_error($connect));

        /* проверка соединения */
        if (mysqli_connect_errno()) {
            exit(mysqli_connect_error());
        } else {
            return $connect;
        }
    } else {
        return $connect;
    }
}

/**
* функция выполняет запрос к БД требующий возврата выборки(select)
* @param string $query строка SQL запроса
* @return array|bool результат запроса
*/
function useSelectQuery($query)
{
    $connect = getConnection();

    // запрос к БД
    $result = mysqli_query($connect, $query);
    
    // проверка была ли получена выборка из БД
    $rowCount = mysqli_num_rows($result);
    // проверка ответа БД
    if ($rowCount) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return false;
    }
}
/**
* функция для получения массива продуктов
* @return array|bool результат запроса
*/

function getProducts()
{
    $connect = getConnection();
    // строка запроса к БД
    $query = '
    SELECT
        p.*,
        GROUP_CONCAT(c.title SEPARATOR ", ") AS category
    FROM
        `products` AS p
    LEFT JOIN `category_product` AS cp
    ON
        p.id = cp.product_id
    LEFT JOIN `categories` AS c
    ON
        cp.category_id = c.id
    GROUP BY
        p.id
    ORDER BY id DESC
    ';
    return useSelectQuery($query);
}

/**
* функция добавляет товар в базу в таблицу products
* @param array информация о товаре
* @return bool результат выполнения запроса на добавление
*/
function addProduct($product){
    $connect = getConnection();
    $data = [];
    // экранирование данных для базы (БЕЗ КАТЕГОРИИ!)
    $data['id']     = mysqli_real_escape_string($connect, $product['id']);
    $data['name']   = mysqli_real_escape_string($connect, $product['name']);
    $data['price']  = mysqli_real_escape_string($connect, $product['price']);
    $data['new']    = mysqli_real_escape_string($connect, $product['new']);
    $data['sale']   = mysqli_real_escape_string($connect, $product['sale']);
    $data['img']    = mysqli_real_escape_string($connect, $product['img']);
    
    $stringColumn = '';
    $stringValue = '';
    foreach ($data as $key => $value) {
        if (!empty($value) || $value == 0) {
            if($value != end($data)) {
                $stringColumn .=  '`' . $key . '`, ';
                if ($value == 'false') {
                    $stringValue .= $value . ', ';
                } else {
                    $stringValue .= '"' . $value . '", ';
                }
            } else {
                $stringColumn .=  '`' . $key . '`';
                $stringValue .= '"' . $value . '"';
            }   
        }
    }
    // строка запроса к БД
    $productQuery = 'INSERT INTO `products` (' . $stringColumn . ') VALUES (' . $stringValue . ')';
    
    $result = useQuery($productQuery);
    if ($result){
        if (!empty($product['category'])) {

            $testAddCategory = true;
            foreach ($product['category'] as $key => $value) {
                if ($testAddCategory){
                    $data['category'] = mysqli_real_escape_string($connect, $value);
                    $categoryQuery = 'INSERT INTO `category_product`(`product_id`, `category_id`) VALUES (' . $data['id'] . ',' . $data['category'] . ')';
                    $testAddCategory = useQuery($categoryQuery);
                }
            }
            return $testAddCategory;
        } else {
            return $result;
        }
    }
}

/**
* функция Для изменения статуса заказа
* @param int $id айди товара в котором надо изменить заказ
* @param bool (int 0 или 1) $status статус заказа который необходимо установить
* @return bool результат выполнения запроса на добавление
*/
function updateStatusOrder($id, $status) {
    $updateStatusOrderQuery = 'UPDATE `orders` SET `status`=' . $status . ' WHERE id=' . $id;
    return useQuery($updateStatusOrderQuery);
}

/**
* функция Для изменения информации о продукте
* @param array $product массив с новой информацией для продукта
* @return bool результат выполнения запроса на добавление
*/
function updateProduct($product){
    $connect = getConnection();
    $data = [];
    $error = [];

    $dataOld = getProductInfo($product['id']);

    // экранирование данных для базы
    $data['id']     = mysqli_real_escape_string($connect, $product['id']);
    $data['name']   = mysqli_real_escape_string($connect, $product['name']);
    $data['price']  = mysqli_real_escape_string($connect, $product['price']);
    $data['new']    = mysqli_real_escape_string($connect, $product['new']);
    $data['sale']   = mysqli_real_escape_string($connect, $product['sale']);
    if (empty($product['img'])) {
        $data['img'] = $dataOld['img'] ?? '';
    } else {
        $data['img']    = mysqli_real_escape_string($connect, $product['img']);
    }
    $stringValue = '';
    $changes = 0;
    foreach ($data as $key => $value) {

        $dataOld[$key] = $dataOld[$key] ?? '';
        $updateValueTest = ($value != $dataOld[$key]) ? true : false;
        
        if ((!empty($value) || $value == 0) && $updateValueTest == true) {
            if($changes == 0) {
                $stringValue .= '`' . $key . '` = \'' . $value . '\'';
            } else {
                $stringValue .=  ', `' . $key . '` = \'' . $value . '\'';
            }
            $changes++;
        }
    }

    // строка запроса к БД
    if (!empty($stringValue)){
        $productQuery = 'UPDATE `products` SET' . $stringValue . ' WHERE `products`.`id` = ' . $data['id'];
        // внести изменения продукт
        if (!useQuery($productQuery)) {
            $error[] = 'Ошибка обновления данных - ' . $productQuery;
        }
    }

    // добавить category
    $categoryQuery = 'DELETE FROM `category_product`  WHERE product_id=' . (int) $data['id'];
    if (!useQuery($categoryQuery)){
        $error[] = 'Ошибка удаления - ' . $categoryQuery;
    }
    
    if (!empty($product['category'])){

        $testAddCategory = true;
        foreach ($product['category'] as $key => $value) {

            if ($testAddCategory){
                $data['category'] = mysqli_real_escape_string($connect, $value);
                $categoryQuery = 'INSERT INTO `category_product`(`product_id`, `category_id`) VALUES (' . $data['id'] . ',' . $data['category'] . ')';
                $testAddCategory = useQuery($categoryQuery);
            } else {
                $error[] = 'Ошибка добавления категорий - ';
            }
        }
    }
    
    if (empty($error)){
        return true;
    } else {
        return $error;
    }
}

/**
* функция для получения информации о продукте по айди
* @param int айди товара
* @return array|bool результат запроса
*/
function getProductInfo($productId)
{
    getConnection();
    // строка запроса к БД
    $query = '
    SELECT
        p.*,
        GROUP_CONCAT(c.title SEPARATOR ", ") AS category
    FROM
        `products` AS p
    LEFT JOIN `category_product` AS cp
    ON
        p.id = cp.product_id
    LEFT JOIN `categories` AS c
    ON
        cp.category_id = c.id
    WHERE
        p.id = ' . $productId . '
    GROUP BY
        p.id
    ';
    return useSelectQuery($query);
}

/**
* функция Удаляет продукт по айди и категории связанные с ним
* @param int айди заказа для удаления
* @return bool результат выполнения запроса на удаления
*/
function deleteProduct ($id) {

    $connect = getConnection();
    $product = getProductInfo ( (int) $id );
    
    // экранирование данных для базы
    $id = mysqli_real_escape_string($connect, $id);

    if ($product) {
        $error = [];
        $orderProductDelete = 'DELETE FROM `products`  WHERE id=' . (int) $id;
        $result = useQuery($orderProductDelete);
        if ($result != true) {
            $error[] = 'Ошибка удаления продукта';
        }
    }
    if (isset($product['category'])) {
        $orderCategoryDelete = 'DELETE FROM `category_product`  WHERE product_id=' . (int) $id;
        $result = useQuery($orderCategoryDelete);
        if ($result != true) {
            $error[] = 'Ошибка удаления категорий';
        }
    }
    if (empty($error)) {
        return true;
    } else {
        return $error;
    }
}

/**
* функция для добавляет заказа в базу в таблицу orders
* @param array информация о заказе
* @return bool результат выполнения запроса на добавление
*/
function addOrder($order){

    $connect = getConnection();
    $data = [];
    // экранирование данных для базы
    $data['name']           = mysqli_real_escape_string($connect, $order['name']);
    $data['surname']        = mysqli_real_escape_string($connect, $order['surname']);
    $data['thirdName']      = mysqli_real_escape_string($connect, $order['thirdName']);
    $data['phone']          = mysqli_real_escape_string($connect, $order['phone']);
    $data['email']          = mysqli_real_escape_string($connect, $order['email']);
    $data['pay']            = mysqli_real_escape_string($connect, $order['pay']);
    $data['productId']      = mysqli_real_escape_string($connect, $order['productId']);
    $data['price']          = mysqli_real_escape_string($connect, $order['price']);
    if ($order['delivery'] == 'true') {
        $data['delivery']   = 1;
    } else {
        $data['delivery']   = 0;
    }
    $data['city']           = mysqli_real_escape_string($connect, $order['city']);
    $data['street']         = mysqli_real_escape_string($connect, $order['street']);
    $data['home']           = mysqli_real_escape_string($connect, $order['home']);
    $data['aprt']           = mysqli_real_escape_string($connect, $order['aprt']);
    $data['comment']        = mysqli_real_escape_string($connect, $order['comment']);
    
    $stringColumn = '';
    $stringValue = '';
    foreach ($data as $key => $value) {
        if (!empty($value || $value == 'false')) {
            if ($key == 'price' || $key == 'productId' || $key == 'id' || $value == 'false') {
                $quotes = '';
            } else {
                $quotes = '"';
            }
            if($stringColumn != '') {
                $stringColumn .=  ', `' . $key . '`';
                $stringValue .=  ', ' . $quotes . $value . $quotes;
            } else {
                $stringColumn .=  '`' . $key . '`';
                $stringValue .= $quotes . $value . $quotes;
            }   
        }
    }
    // строка запроса к БД
    $orderQuery = 'INSERT INTO `orders` (' . $stringColumn . ') VALUES (' . $stringValue . ')';

    return useQuery($orderQuery);
}

/**
* функция выполняет запрос к БД не требующий возврата данных
* @param string $query строка SQL запроса
* @return bool результат запроса
*/
function useQuery($query)
{
    $connect = getConnection();
    return mysqli_query($connect, $query);
}

// ---------------------------------------------------

/**
* функция для получения минимальной и максимальной цены
* @return array|bool результат запроса
*/
function getRangePrice()
{
    $connect = getConnection();
    // строка запроса к БД
    $query = '
        SELECT MAX(price)  
        FROM products
    ';
    return useSelectQuery($query);
}
/**
* функция для получения информации о категориях
* @return object mysqli_result $result в случае успеха возвращает результат запроса
*/
function getCategories()
{
    $connect = getConnection();
    // строка запроса к БД
    $query = '
        SELECT
            *
        FROM
            categories
    ';
    return useSelectQuery($query);
}

/**
* функция для получения информации о заказах
* @return object mysqli_result $result в случае успеха возвращает результат запроса
*/
function getOrders()
{
    $connect = getConnection();
    // строка запроса к БД
    $query = '
    SELECT
        o.*, p.name as payName
    FROM
        `orders` AS o
    LEFT JOIN `pay` AS p
    ON
        p.code = o.pay
    ORDER BY o.status, o.id desc
    ';
    return useSelectQuery($query);
}
/**
* функция возвращает цену
* @return object mysqli_result $result в случае успеха возвращает результат запроса
*/
function numberPriceFormat($price)
{
    return number_format($price, 0, ',', ' ') . ' руб.';

}

/**
* функция возвращает массив с выборкой  из базы и количеством результирующих строк
* @param int $page текущая(активная) страница
* @param int $pagesCount количество товаров которые нужно отобразить на одной странице
* @return array $result массив с выборкой из бд и количеством страниц для данной выборки согласно настройкам
*/
function getProductsForPage($countOfItemsPerPage = 15)
{
    $connect = getConnection();

    $page = getPage();

    $filterParameters = [];
    if (!empty($_GET['category'])) {
        $category = mysqli_real_escape_string($connect, $_GET['category']);
        if ($category != '*') {
            $category = 'c.name="' . $category . '"';
            $filterParameters[] = $category;
        }
    }
    if (!empty($_GET['new'])) {
        $new = 'p.new="1"';
        $filterParameters[] = $new;
    }
    if (!empty($_GET['sale'])) {
        $sale = 'p.sale="1"';
        $filterParameters[] = $sale;
    }

    if (!empty($_GET['minPrice'])) {
        $minPrice = 'p.price>="' . $_GET['minPrice'] . '"';
        $filterParameters[] = $minPrice;
    }

    if (!empty($_GET['maxPrice'])) {
        $maxPrice = 'p.price<="' . $_GET['maxPrice'] . '"';
        $filterParameters[] = $maxPrice;
    }


    if (!empty($_GET['sorting'])) {
        $orderParameter = mysqli_real_escape_string($connect, $_GET['sorting']);
    } else {
        $orderParameter = 'name';
    }
    if (!empty($_GET['groupingOrder']) && $_GET['groupingOrder'] == 'desc') {
        $sortDirection = 'desc';
    } else {
        $sortDirection = 'asc';
    }

    $orderParameter = 'ORDER BY ' . $orderParameter . ' ' . $sortDirection;
    
    $filters = '';
    foreach ($filterParameters as $filterParameter) 
    {
        if (empty($filters)) {
            $filters = '
            where ' . $filterParameter;
        } else {
            $filters .= ' and ' . $filterParameter;
        }
    }

    $query = '
        SELECT
            p.*,
            GROUP_CONCAT(c.title SEPARATOR ", ") AS category
        FROM
            `products` AS p
        LEFT JOIN `category_product` AS cp
        ON
            p.id = cp.product_id
        LEFT JOIN `categories` AS c
        ON
            cp.category_id = c.id
            ' . $filters . '
        GROUP BY
            p.id
        ' . $orderParameter . '
    ';

    $queryResult = useSelectQuery($query);

    // Общее количество строк продуктов
    if ($queryResult) {
        $count = count($queryResult);
    } else {
        return false;
    }

    // Количество страниц
    $pagesCount = ceil($count / $countOfItemsPerPage);

    // Если номер страницы оказался больше количества страниц
    if ($page > $pagesCount) $page = $pagesCount;
    // Начальная позиция, для запроса к БД
    $startPosition = ($page - 1) * $countOfItemsPerPage;

    $queryLimit = $query . ' LIMIT ' . $startPosition . ', ' . $countOfItemsPerPage;

    $result['pagesCount'] = $pagesCount;
    $result['products'] = useSelectQuery($queryLimit);
    $result['count'] = $count;
    $result['page'] = $page;

    return $result;
}
/**
* Проверяет авторизацию
* @return bool результат проверки на авторизацию
*/
function isAuth()
{
    return isset($_SESSION['auth']) && $_SESSION['auth'];
}

/**
* функция возвращает максимальную и минимальную цены для базы продуктов
* @return array|| $result['max'] максимальная цена и $result['min'] минимальная цена массив с выборкой из бд и количеством страниц для данной выборки согласно настройкам
*/
function getMinAndMaxPriceProducts()
{
    $connect = getConnection();
    // строка запроса к БД
    $query = '
        SELECT
            MAX(price) as max,
            MIN(price) as min
        FROM
            products
    ';

    return useSelectQuery($query);
}

/**
* функция возвращает текущую страницу из GET
* @return int $page номер старницы
*/
function getPage()
{
    if (empty($_GET['page']) || ($_GET['page'] <= 0)) {
      $page = 1;
    } else {
      $page = (int) $_GET['page'];
    }
    return $page;
}

/**
* функция выводит панель со страницами
* @param int $page текущая(активная) страница
* @param int $pagesCount сколько всего страниц
*/
function printPages($pagesCount)
{
    $page = getPage();

    if ($pagesCount != 1){
        for ($j = 1; $j <= $pagesCount; $j++)
        {
            $getQueryString = $_SERVER['QUERY_STRING'] ?? '';
            $http = parse_url('/?'. $getQueryString);
            parse_str($http['query'] ?? '', $output);
            if(isset($output['page'])) {
              unset($output['page']);
            }
            $getParametersURL = $http["path"] . '?' . http_build_query($output);

            // Вывод ссылки
            if ($j == $page) {
                echo ' <li><a class="paginator__item">'.$j.'</a></li>';
            } else {
                $_GET['page'] = $j;
                if ($getParametersURL != '/?') {
                    $ampersand = '&';
                } else {
                    $ampersand  = '';
                }
                echo ' <li><a class="paginator__item" href=' . $getParametersURL . $ampersand .'page=' . $j  . '>' . $j . '</a></li>';
            }
            // Выводим разделитель после ссылки, кроме последней
            if ($j != $pagesCount) echo ' ';
        }
    }
}

/**
* функция для получения информации о пользователе по логину из БД
* @param string $login принимает логин  пользователя
* @return object mysqli_result $result в случае успеха возвращает результат запроса
*/
function getUserByLogin($login)
{
    $connect = getConnection();
    $login = mysqli_real_escape_string($connect, $login);
    // строка запроса к БД
    $query = '
        SELECT
            u.*, g.name AS "groups"
        FROM
            `users` AS u
        LEFT JOIN `group_user` AS gu ON u.id = gu.id_user
        LEFT JOIN `groups` AS g ON gu.id_group = g.id
        WHERE u.login = "' . $login . '"
    ';

    return useSelectQuery($query);
}

/**
* функция проверяет есть ли у текущего пользователя доступ к текущей странице 
* @param string $URL адрес без доменного имени и гет параметров
* @return bool если true то доступ к странице разрешен
*/
function accessUserForAdress ($URL = false)
{
    if ($URL == false) $URL = $_SERVER['REQUEST_URI'];

    $URL = preg_replace('/\\?.*/', '', $URL);

    include $_SERVER['DOCUMENT_ROOT'] . '/include/settingAccessAddress.php';
    
    if (isset($_SESSION) && !empty($_SESSION['login']) && ($URL !== null || $URL != '/')){

        $getUserByLogin = getUserByLogin($_SESSION['login']);

        if (!empty($getUserByLogin)) {
            foreach ($getUserByLogin as $user) {
                if (empty($user['groups'])) {
                    $user['groups'] = 'Гости';
                }
                if (in_array($URL, $access[$user['groups']])){
                    return true;
                }
            }
        } else {
            if (in_array($URL, $access['Гости'])){
                return true;
            } else {
                return false;
            }
        }
    }
    if (in_array($URL, $access['Гости'])) {
        return true;
    }
    return false;
}

/**
* функция для проверки авторизации
* @param string $login принимает логин  пользователя
* @param string $password принимает пароль  пользователя
* @return bool результат авторизации
*/
function verification($login, $password)
{
    $rows = getUserByLogin($login);

    if ($rows) {
        // проверка пароля и авторизация
        if (password_verify($password, $rows[0]['password'])) {
            $_SESSION['login'] = $login;
            return $_SESSION['auth'] = true;  
        } else {
            return $_SESSION['auth'] = false;
        }
    } else {
        return $_SESSION['auth'] = false;
    }
}

/**
* функция возвращает список груп пользователя
* @param string $login принимает логин  пользователя
* @return  array|bool $groups возвращает список груп пользователя или false в случае ошибки выполнения запроса
*/
function getUserGroups($login)
{
    $rows = getUserByLogin($login);

    $groups = [];
    foreach ($rows as $row) {
        $groups[] = $row['groups'];
    }
    return $groups;
}
// мб не нужная функция
/**
* функция для вывода информации по пользователю
* @param string $login принимает логин  пользователя
*/
function printUserInfo($login)
{
    $rows = getUserByLogin($login);
    $groups = getUserGroups($login);
    $groups = implode(", ", $groups);

    if ($rows) { ?>
        <table class="profile">
      <?php foreach ($rows[0] as $title => $value) {
                if ($title == 'groups') {
                    $value = $groups;
                } ?>
                <tr>
                    <td><?= $title ?></td>
                    <td><?= $value ?></td>
                </tr>
      <?php } ?>
        </table>
    <?php
    }
}
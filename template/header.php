<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/include/sessionStart.php';

// продлевать куку при хитах
if (!empty($_SESSION['auth']) && isset($_SESSION['login'])) {
    setcookie('login', $_SESSION['login'], time() + 3600 * 24 * 30, '/', 'prod.loc');
}
include_once  $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';
include $_SERVER['DOCUMENT_ROOT'] . '/include/menu.php';

// Доступ
if (isset($_SESSION['auth']) && $_SESSION['auth'] == true &&  isset($_SESSION['login'])) {
    $menu = $adminMenu;
}
    $title = $title ?? getRouteTitle($menu) ?? 'Fashion';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>

    <meta name="description" content="Fashion - интернет-магазин">
    <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">
    <meta name="theme-color" content="#393939">

    <link rel="preload" href="/img/intro/coats-2018.jpg" as="image">

    <link rel="icon" href="/img/favicon.png">

    <link rel="stylesheet" href="/css/style.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/js/scripts.js" defer=""></script>
</head>
<body>
<header class="page-header">
    <a class="page-header__logo" href="/">
        <img src="/img/logo.svg" alt="Fashion">
    </a>
    <?php printMenu($menu, "page-header__menu", "main-menu--header"); ?>
</header>
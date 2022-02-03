<?php

$menu = [
    [
        'title' => 'Главная',
        'path'  => '/',
        'sort'  => 100,
    ],
    [
        'title' => 'Новинки',
        'path'  => '/?new=on',
        'sort'  => 200,
    ],
    [
        'title' => 'Sale',
        'path'  => '/?sale=on',
        'sort'  => 300,
    ],
    [
        'title' => 'Доставка',
        'path'  => '/delivery/',
        'sort'  => 400,
    ]
];

$adminMenu = [
    [
        'title' => 'Главная',
        'path'  => '/',
        'sort'  => 100,
    ],
    [
        'title' => 'Товары',
        'path'  => '/products/',
        'sort'  => 200,
    ],
    [
        'title' => 'Заказы',
        'path'  => '/orders/',
        'sort'  => 300,
    ],
    [
        'title' => 'Выход',
        'path'  => '/admin/logout.php',
        'sort'  => 400,
    ]
];
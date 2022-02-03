<?php
$access = [
    'Администратор' => [
        '/products/',
        '/orders/',
        '/products/add.php/'
    ],
    'Оператор' => [
        '/orders/'
    ],
    'Гости' => [
        '/delivery/',
        '/admin/',
        '/test/',
        '/admin/logout.php',
        '/',
        null
    ],
];

<?php

include $_SERVER['DOCUMENT_ROOT'] . '/include/functions.php';

if (isset($_POST['auth'])) {
    session_start();
    $login = $_SESSION['login'] = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);

    // получение информации о логине пароле из БД
    $resultLogin = verification($login, $password);

    if ($resultLogin == true) {
        header('Location: /orders/');//
        die();
    } else {
        $error = 'Неверные логин или пароль';
    }
}
$title = 'Вход';
include $_SERVER['DOCUMENT_ROOT'] . '/template/header.php';

?>

<main class="page-authorization">
    <h1 class="h h--1">Авторизация</h1>

    <?php
    if (!empty($error)) { ?>
        <p style="color: red"><?= $error ?></p>
    <?php
    }
    ?>

    <form class="custom-form" action="/admin/" method="post">
        <input type="email" class="custom-form__input" name='login' value="<?= $_POST['login'] ?? '' ?>" required="">
        <input type="password" class="custom-form__input" name='password' required="">
        <button class="button" name='auth' type="submit">Войти в личный кабинет</button>
    </form>
</main>


<?php
include $_SERVER['DOCUMENT_ROOT'] . '/template/footer.php';
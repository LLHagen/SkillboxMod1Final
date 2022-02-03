<td class="right-collum-index">
    <div class="project-folders-menu">
        <ul class="project-folders-v">
            <li class="project-folders-v-active"><a href="/?login=yes">Авторизация</a></li>
            <li><a href="#">Регистрация</a></li>
            <li><a href="#">Забыли пароль?</a></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    <div class="index-auth">
        <form action="/?login=yes" method="post">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <?php
                    //  "упрощенная" авторизация без указания логина
                    $specialLogin = '';
                    if (isset($_COOKIE['login']) && !isset($_SESSION['auth']) && !isset($_SESSION['login'])) {
                        if (isset($_GET['specialLogin']) && $_GET['specialLogin'] == 'yes') {
                            $class = '';
                        } else {
                            $class = 'hide';
                            // кнопка если нужно сменить пользователя
                            $specialLogin = '<a href="/?login=yes&specialLogin=yes">Сменить пользователяя ' . $_COOKIE['login'] . '</a>';
                        }
                        $login = $_COOKIE['login'];
                    } else {
                        $class = '';
                        $login = $_SESSION['login'] ?? '';
                    }

                ?>      
                    <td class="iat <?= $class ?>">
                        <label for="login_id">Ваш e-mail:</label>
                        <input id="login_id" size="30" name="login" value="<?= $login ?>">
                    
                    </td>
                </tr>
                <tr>
                    <td class="iat">
                        <label for="password_id">Ваш пароль:</label>
                        <input id="password_id" size="30" name="password" type="password" >
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" name='auth' value="Войти">
                    <?php
                        // паказать кнопку о смене пользователя
                        if (!empty($specialLogin)) {
                            echo $specialLogin;
                        }
                        // Фейл авторизации
                        if (isset($_SESSION['auth']) && $_SESSION['auth'] == false) {
                            include $_SERVER['DOCUMENT_ROOT'] . '/template/fail.php';
                            unset($_SESSION['auth']);
                        }
                    ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
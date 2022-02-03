<?php
include $_SERVER['DOCUMENT_ROOT'] . '/include/menu.php';
?>

<footer class="page-footer">
  <div class="container">
    <a class="page-footer__logo" href="#">
      <img src="/img/logo--footer.svg" alt="Fashion">
    </a>
    <?php printMenu($menu, "page-footer__menu", "main-menu--footer"); ?>
    <address class="page-footer__copyright">
      © Все права защищены
    </address>
  </div>
</footer>

</body>
</html>
<?php
if (!empty($connect)){
    mysqli_close($connect);
}
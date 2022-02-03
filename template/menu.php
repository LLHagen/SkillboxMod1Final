
<nav class="<?= $navClass ?>">
    <ul class="main-menu <?= $ulClass ?>">

<?php foreach ($menu as $item) {

    if (accessUserForAdress($item['path'])){
        $hidden = ''; 
    } else {
        $hidden = 'hidden=""';
    } 
    ?>
        <li>
            <a class="main-menu__item <?= addClassActiveLink ($item['path']) ?>" href="<?= $item['path'] ?>" <?= $hidden ?>><?= $item['title'] ?></a>
        </li>
<?php } ?>

    </ul>
</nav>
<section class="shop__sorting">
    <div class="shop__sorting-item custom-form__select-wrapper">
        <select class="custom-form__select" name="category">
            <option hidden="">Сортировка</option>
            <option value="Price">По цене</option>
            <option value="Name">По названию</option>
        </select>
    </div>
    <div class="shop__sorting-item custom-form__select-wrapper">
        <select class="custom-form__select" name="prices">
            <option hidden="">Порядок</option>
            <option value="asc">По возрастанию</option>
            <option value="desc">По убыванию</option>
        </select>
    </div>
        <p class="shop__sorting-res">Найдено <span class="res-sort"><?= $countProducts ?? 0 ?></span> моделей</p>
</section>
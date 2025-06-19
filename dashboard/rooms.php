<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="stylesheets/clear-stock.css" />
        <link rel="stylesheet" href="stylesheets/rooms_page.css" />
        <link rel="stylesheet" href="stylesheets/room.css" />
        <link rel="stylesheet" href="stylesheets/slider.css" />
    </head>

    <body>
        <?php include "components/header.html"; ?>

        <section class="tab">
            <div class="tab-div">
                <button class="sort">Сортировать</button>
                <div class="sort-menu">
                    <h3>Сортировка</h3><br>
                    <button class="sort-price__up">&#11205;стоимость</button><br>
                    <button class="sort-price__down">&#11206;стоимость</button><br>
                    <button class="sort-capacity__up">&#11205;количество мест</button><br>
                    <button class="sort-capacity__down">&#11206;количество мест</button><br>
                    <button class="sort-type__up">&#11205;тип</button><br>
                    <button class="sort-type__down">&#11206;тип</button><br>
                    <button class="sort-reboot">сброс</button><br>
                </div>

                <button class="filter">Фильтры</button>
                <div class="filter-menu">
                    <h3>Фильтры</h3><br>
                    <select class="combobox">
                        <option selected="selected" value="all">Любой</option>
                        <option value="Апартаменты">Апартаменты</option>
                        <option value="Люкс">Люкс</option>
                        <option value="Полулюкс">Полулюкс</option>
                        <option value="Стандарт">Стандарт</option>
                        <option value="Студия">Студия</option>
                    </select>
                    <div class="filter-price">
                        <h4>стоимость</h4>
                        <input class="price-min" type="text" placeholder="от" />
                        <input class="price-max" type="text" placeholder="до" />
                    </div>
                    <div class="filter-capacity">
                        <h4>количество мест</h4>
                        <input class="capacity-count" type="text" />
                    </div>


                    <button class="reboot">сброс</button>
                    <button class="find">Найти</button>
                </div>
            </div>
        </section>

        <section class="main">
            <ul class="list-rooms" id="list-rooms">
                <?php include "components/room.html"; ?>
                <script src="javascripts/rooms.js"></script>
            </ul>
        </section>

        <?php include "components/footer.html"; ?>
    </body>
</html>

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
                <button class="filter">Фильтры</button>
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

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>

        <link rel="stylesheet" href="stylesheets/clear-stock.css"/>
        <link rel="stylesheet" href="stylesheets/main_page.css"/>


        <title>OTEL</title>
    </head>

    <body class="index">
        <?php include "components/header.html"; ?>

        <section class="info">

            <img src="images/otel-outside.jpg">
            <div class="otel-mini__info">
                <h3>Лучший отель в мире</h3>

                <?php include "components/rating.html"; ?>

            </div>

        </section>

        <section class="main">
            <ul>
                <li>
                    <div class="info"><h3>Если хочешь забронировать или просто посмотреть имеющиеся номера</h3></div>
                    <button onclick="location.href='/'"><h3>то тебе
                    сюда</h3></button>
                </li>
                <li>
                    <button onclick="location.href='/'"><h3>тебе
                    сюда</h3></button>
                    <div class="info"><h3>Если хочешь узнать к кому можно обратиться за помощью</h3></div>
                </li>
                <li>
                    <div class="info"><h3>Если хочешь узнать мнение других</h3></div>
                    <button onclick="location.href='/'"><h3>то тебе
                    сюда</h3></button>
                </li>
            </ul>
        </section>

        <?php include "components/footer.html"; ?>
    </body>
</html>

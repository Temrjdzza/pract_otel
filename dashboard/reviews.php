<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="stylesheets/clear-stock.css" />
        <link rel="stylesheet" href="stylesheets/reviews_page.css" />
        <link rel="stylesheet" href="stylesheets/rating.css" />
    </head>
    <body>
        <?php include "components/header.html"; ?>
        <section class="comment-form">
            <div class="form">
                <div>

                    <?php include "components/interactive-rating.html"; ?>
                    <input class="form-name" placeholder="Имя" />
                </div>
                <textarea
                    class="form-comment"
                    placeholder="Комментарий"
                ></textarea>
                <button class="form-public">Опубликовать</button>
            </div>
        </section>
        <section class="main">
            <ul class="list-comments">
                <?php include "components/comment.html"; ?>
                <script src="javascripts/reviews.js"></script>
            </ul>
        </section>

<?php include "components/footer.html"; ?>
    </body>
</html>

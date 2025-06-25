<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <link rel="stylesheet" href="stylesheets/clear-stock.css" />
        <link rel="stylesheet" href="stylesheets/contacts_page.css" />

    </head>
    <body>
        <?php include "components/header.html"; ?>
        
        <main class="container">
        <div class="contact-section">
            
            <div class="contact-map">
                <h2>Мы на карте</h2>
                <div class="map-container">
                    
                    <iframe 
                        src="https://yandex.ru/map-widget/v1/?um=constructor%3A9daa710ccd18deb46a76c13ecaae5a6c5f71bcd9409c794794fe3882b1762b1a&amp;source=constructor" 
                        frameborder="0">
                    </iframe>
                    
                </div>
            </div>
        </div>
        
        <section class="team-section">
            <h2>Наша команда</h2>
            <div id="contacts-container" class="contacts-grid"></div>
             <script src="javascripts/contacts.js"></script>
        </section>
    </main>     
</section>

        <?php include "components/footer.html"; ?>
        
    </body>
</html>
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
        <h1>Наши контакты</h1>
        
        <div class="contact-section">
            <div class="contact-info">
                <h2>Свяжитесь с нами</h2>
                
                <div class="contact-item">
                    <span class="contact-icon">📞</span>
                    <strong>Телефон:</strong> 
                    <a href="tel:+78005353535">+7 (800) 535-35-35</a> 
                </div>
                
                <div class="contact-item">
                    <span class="contact-icon">📱</span>
                    <strong>WhatsApp:</strong> 
                    <a href="https://wa.me/78005353535">+7 (800) 535-35-35</a>
                </div>
                
                <div class="contact-item">
                    <span class="contact-icon">✉️</span>
                    <strong>Email:</strong> 
                    <a href="mailto:vkusni@mail.py">vkusni@mail.py</a>
                </div>
                
                <div class="contact-item">
                    <span class="contact-icon">🏨</span>
                    <strong>Адрес:</strong> 
                    <p>Дальний восток, промышленный проспект, первая улица, дом последний</p>
                </div>
                
                <div class="contact-item">
                    <span class="contact-icon">⏰</span>
                    <strong>Режим работы:</strong> 
                    <p>Круглосуточно, без выходных</p>
                </div>
            </div>
            
            <div class="contact-map">
                <h2>Мы на карте</h2>
                <div class="map-container">
                    
                    <iframe 
                        src="https://yandex.ru/map-widget/v1/?um=constructor%3A9daa710ccd18deb46a76c13ecaae5a6c5f71bcd9409c794794fe3882b1762b1a&amp;source=constructor" 
                        width="500" 
                        height="400" 
                        frameborder="0">
                    </iframe>
                    
                </div>
            </div>
        </div>
    </main>     
</section>

        <?php include "components/footer.html"; ?>
    </body>
</html>
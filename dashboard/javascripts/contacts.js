document.addEventListener('DOMContentLoaded', function() {
    const api = '/api/router.php/contacts';
    const contactsContainer = document.getElementById('contacts-container');
    
    if (!contactsContainer) return;

    loadContacts(api);
    function loadContacts(url) {
        fetch(url)
            .then(response => response.json())
            .then(data => renderContacts(data.data))
    }

    function renderContacts(contacts) {
        if (!contacts || !contacts.length) {
            contactsContainer.innerHTML = `<div class="error-message">Контакты не найдены</div>`;
            return;
        }

        let html = '';
        contacts.forEach(contact => {
            html += `
                <div class="contact-card">
                    <h3>${contact.name || 'Имя не указано'}</h3>
                    <div class="contact-details">
                        ${contact.phone ? `<p><strong>Телефон:</strong> <a href="tel:${contact.phone}">${contact.phone}</a></p>` : ''}
                        ${contact.email ? `<p><strong>Email:</strong> <a href="mailto:${contact.email}">${contact.email}</a></p>` : ''}
                    </div>
                </div>
            `;
        });

        contactsContainer.innerHTML = html;
    }
    
});
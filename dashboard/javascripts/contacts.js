document.addEventListener('DOMContentLoaded', function() {
    const apiUrl = '/api/router.php/contacts';
    
    function loadContacts() {
        fetch(apiUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    displayContacts(data.data);
                } else {
                    console.error('API returned error:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching contacts:', error);
            });
    }
    
    function displayContacts(contacts) {
        const contactSection = document.querySelector('.contact-section');
        
        const contactsListContainer = document.createElement('div');
        contactsListContainer.className = 'contacts-list';
        
        const contactsHeader = document.createElement('h2');
        contactsHeader.textContent = 'Наша команда';
        contactsListContainer.appendChild(contactsHeader);
        
        const contactsList = document.createElement('div');
        contactsList.className = 'contacts-grid';
        
        contacts.forEach(contact => {
            const contactItem = document.createElement('div');
            contactItem.className = 'contact-card';
            
            contactItem.innerHTML = `
                <h3>${contact.name}</h3>
                <div class="contact-details">
                    ${contact.phone ? `<p><strong>Телефон:</strong> <a href="tel:${contact.phone}">${contact.phone}</a></p>` : ''}
                    ${contact.email ? `<p><strong>Email:</strong> <a href="mailto:${contact.email}">${contact.email}</a></p>` : ''}
                </div>
            `;
            
            contactsList.appendChild(contactItem);
        });
        
        contactsListContainer.appendChild(contactsList);
        
        const existingContactInfo = document.querySelector('.contact-info');
        contactSection.insertBefore(contactsListContainer, existingContactInfo.nextSibling);
    }
    
    loadContacts();
});
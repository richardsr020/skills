
class LeadForm {

    constructor() {
        this.form = document.getElementById('leadForm');
        this.phoneInput = document.getElementById('phone');

        this.emailInput = document.getElementById('email');
        this.submitBtn = document.getElementById('submitBtn');
        this.messageContainer = document.getElementById('message-container');

        
        this.initEvents();
    }

    initEvents() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.phoneInput.addEventListener('input', () => this.validatePhone());

        this.emailInput.addEventListener('input', () => this.validateEmail());
    }

    validatePhone() {
        const phone = this.phoneInput.value.trim();
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        

        if (!phone) {

            this.showError(this.phoneInput, 'Le téléphone est requis');

            return false;

        }


        if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
            this.showError(this.phoneInput, 'Format invalide (ex: 06 12 34 56 78)');

            return false;
        }


        this.clearError(this.phoneInput);
        return true;
    }

    validateEmail() {
        const email = this.emailInput.value.trim();
        
        if (email && !this.isValidEmail(email)) {
            this.showError(this.emailInput, 'Format d\'email invalide');
            return false;
        }

        this.clearError(this.emailInput);
        return true;

    }


    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        return emailRegex.test(email);

    }

    showError(input, message) {
        input.classList.add('error');

        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {

            errorElement.textContent = message;
            errorElement.style.color = '#dc3545';
        }

    }

    clearError(input) {
        input.classList.remove('error');

        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {
            errorElement.textContent = '';
        }

    }

    showMessage(message, type = 'success') {
        this.messageContainer.innerHTML = `
            <div class="alert alert-${type}">
                ${message}
            </div>
        `;
        
        setTimeout(() => {
            this.messageContainer.innerHTML = '';

        }, 5000);

    }


    setLoading(loading) {

        this.submitBtn.disabled = loading;
        this.submitBtn.textContent = loading ? 'Envoi en cours...' : 'Envoyer mes coordonnées';

    }


    async handleSubmit(e) {

        e.preventDefault();


        const isPhoneValid = this.validatePhone();
        const isEmailValid = this.validateEmail();


        if (!isPhoneValid || !isEmailValid) {

            this.showMessage('Veuillez corriger les erreurs du formulaire', 'error');
            return;
        }

        const formData = {
            phone: this.phoneInput.value.trim(),
            email: this.emailInput.value.trim() || null
        };

        this.setLoading(true);


        try {

            const response = await fetch('api/lead/add.php', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                },
                body: JSON.stringify(formData)
            });


            const result = await response.json();

            if (result.success) {

                this.showMessage('✅ Vos coordonnées ont été enregistrées avec succès !');
                this.form.reset();

            } else {
                this.showMessage(result.message || '❌ Une erreur est survenue', 'error');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showMessage('❌ Erreur de connexion au serveur', 'error');

        } finally {

            this.setLoading(false);
        }

    }
}


// Initialisation quand la page est chargée

document.addEventListener('DOMContentLoaded', () => {

    new LeadForm();

});


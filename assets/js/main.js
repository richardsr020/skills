

// Animation d'écriture progressive - À ajouter dans votre main.js
class TextAnimation {
    constructor() {
        this.textElement = document.getElementById('animated-text');
        this.cursorElement = document.getElementById('typing-cursor');
        this.skipButton = document.getElementById('skip-animation');
        this.isAnimating = false;
        this.currentAnimation = null;
        
        this.fullText = `
            <strong>Fini les modifications manuelles de CV et de lettres de motivation !</strong><br>
            
            Notre <strong class="text-cyan-600">agent IA intelligent</strong> personnalise automatiquement vos documents pour chaque candidature. 
            Un simple <strong class="text-pink-600">commandement vocal</strong> suffit pour adapter votre profil à n'importe quelle offre.<br>
            • <span class="text-cyan-600">Recherche intelligente</span> des meilleures offres selon votre profil<br>
            • <span class="text-pink-600">Mise en relation proactive</span> avec les employeurs potentiels<br>
            • <span class="text-gradient-cyan-pink">Notre agent</span> vous recherche un job h24, 7j/7<br>
            
            <strong class="text-lg">Transformez votre carrière dès aujourd'hui ! 🚀</strong>
        `;
        
        this.init();
    }

    init() {
        if (!this.textElement) return;
        
        // Afficher le bouton skip après 2 secondes
        setTimeout(() => {
            this.skipButton.classList.remove('opacity-0');
        }, 2000);

        // Événement pour skip l'animation
        this.skipButton.addEventListener('click', () => {
            this.skipAnimation();
        });

        // Démarrer l'animation
        this.startAnimation();
    }

    async startAnimation() {
        this.isAnimating = true;
        this.textElement.innerHTML = '';
        
        // Désactiver le bouton skip pendant l'animation
        this.skipButton.style.pointerEvents = 'none';
        
        // Animation d'apparition du curseur
        this.cursorElement.style.opacity = '1';

        // Typewriter effect
        await this.typeWriter(this.fullText, 15);
        
        // Fin de l'animation
        this.isAnimating = false;
        this.cursorElement.style.opacity = '0';
        this.skipButton.style.opacity = '0';
        this.skipButton.style.pointerEvents = 'none';
        
        // Petit effet de fin
        this.textElement.classList.add('animate-pulse');
        setTimeout(() => {
            this.textElement.classList.remove('animate-pulse');
        }, 1000);
    }

    async typeWriter(text, speed = 20) {
        return new Promise((resolve) => {
            let i = 0;
            let currentHtml = '';
            let inTag = false;
            let currentTag = '';
            
            const type = () => {
                if (i < text.length) {
                    const char = text[i];
                    
                    // Gestion des balises HTML
                    if (char === '<') {
                        inTag = true;
                        currentTag = '';
                    }
                    
                    if (inTag) {
                        currentTag += char;
                        if (char === '>') {
                            inTag = false;
                            currentHtml += currentTag;
                            this.textElement.innerHTML = currentHtml;
                        }
                    } else {
                        currentHtml += char;
                        this.textElement.innerHTML = currentHtml;
                    }
                    
                    i++;
                    
                    // Vitesse variable selon la ponctuation
                    let delay = speed;
                    if (char === '.' || char === '!' || char === '?') {
                        delay = speed * 8; // Pause plus longue après un point
                    } else if (char === ',' || char === ';') {
                        delay = speed * 4;
                    } else if (char === ' ') {
                        delay = speed * 2;
                    }
                    
                    setTimeout(type, delay);
                } else {
                    resolve();
                }
            };
            
            type();
        });
    }

    skipAnimation() {
        if (this.isAnimating) {
            this.textElement.innerHTML = this.fullText;
            this.isAnimating = false;
            this.cursorElement.style.opacity = '0';
            this.skipButton.style.opacity = '0';
            this.skipButton.style.pointerEvents = 'none';
        }
    }
}

// Animation de fade-in pour les éléments au scroll
class ScrollAnimations {
    constructor() {
        this.observer = null;
        this.init();
    }

    init() {
        // Configuration de l'Intersection Observer
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);

        // Observer les éléments à animer
        document.querySelectorAll('.glass-card, .feature-icon').forEach(el => {
            this.observer.observe(el);
        });
    }
}

// Gestion des particules
class ParticlesBackground {
    constructor() {
        this.container = document.getElementById('particles-container');
        this.particles = [];
        this.init();
    }

    init() {
        this.createParticles();
        this.animate();
    }

    createParticles() {
        const particleCount = 15;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Taille aléatoire
            const size = Math.random() * 60 + 20;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Position aléatoire
            particle.style.left = `${Math.random() * 100}vw`;
            particle.style.top = `${Math.random() * 100}vh`;
            
            // Opacité aléatoire
            particle.style.opacity = Math.random() * 0.2 + 0.1;
            
            this.container.appendChild(particle);
            this.particles.push({
                element: particle,
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                speedX: (Math.random() - 0.5) * 0.5,
                speedY: (Math.random() - 0.5) * 0.5
            });
        }
    }

    animate() {
        this.particles.forEach(particle => {
            particle.x += particle.speedX;
            particle.y += particle.speedY;

            // Rebond sur les bords
            if (particle.x <= 0 || particle.x >= window.innerWidth) particle.speedX *= -1;
            if (particle.y <= 0 || particle.y >= window.innerHeight) particle.speedY *= -1;

            particle.element.style.transform = `translate(${particle.x}px, ${particle.y}px)`;
        });

        requestAnimationFrame(() => this.animate());
    }
}

// Gestion des formulaires
class FormManager {
    constructor() {
        this.forms = ['betaForm', 'betaFormBottom'];
        this.init();
    }

    init() {
        this.forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', (e) => this.handleSubmit(e, formId));
            }
        });

        // Validation en temps réel
        this.initRealTimeValidation();
    }

    initRealTimeValidation() {
        const inputs = document.querySelectorAll('input[type="email"], input[type="tel"]');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        
        if (field.type === 'email' && value) {
            if (!this.isValidEmail(value)) {
                this.showFieldError(field, 'Format d\'email invalide');
                return false;
            }
        }

        if (field.type === 'tel' && value) {
            if (!this.isValidPhone(value)) {
                this.showFieldError(field, 'Format de téléphone invalide');
                return false;
            }
        }

        this.clearFieldError(field);
        return true;
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}$/;
        return phoneRegex.test(phone.replace(/\s/g, ''));
    }

    showFieldError(field, message) {
        field.classList.add('error');
        // Créer ou mettre à jour le message d'erreur
        let errorElement = field.nextElementSibling;
        if (!errorElement || !errorElement.classList.contains('field-error')) {
            errorElement = document.createElement('div');
            errorElement.className = 'field-error text-red-500 text-sm mt-1';
            field.parentNode.appendChild(errorElement);
        }
        errorElement.textContent = message;
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.nextElementSibling;
        if (errorElement && errorElement.classList.contains('field-error')) {
            errorElement.remove();
        }
    }

    async handleSubmit(e, formId) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const submitText = form.querySelector('#submitText' + (formId === 'betaFormBottom' ? 'Bottom' : ''));
        const loadingSpinner = form.querySelector('#loadingSpinner' + (formId === 'betaFormBottom' ? 'Bottom' : ''));
        const alertSuccess = form.querySelector('#alertSuccess' + (formId === 'betaFormBottom' ? 'Bottom' : ''));
        const alertError = form.querySelector('#alertError' + (formId === 'betaFormBottom' ? 'Bottom' : ''));

        // Validation des champs
        const emailField = form.querySelector('input[type="email"]');
        const phoneField = form.querySelector('input[type="tel"]');
        const consentField = form.querySelector('input[type="checkbox"]');

        let isValid = true;

        if (!emailField.value.trim()) {
            this.showFieldError(emailField, 'L\'email est requis');
            isValid = false;
        } else if (!this.isValidEmail(emailField.value.trim())) {
            this.showFieldError(emailField, 'Format d\'email invalide');
            isValid = false;
        }

        if (!phoneField.value.trim()) {
            this.showFieldError(phoneField, 'Le téléphone est requis');
            isValid = false;
        } else if (!this.isValidPhone(phoneField.value.trim())) {
            this.showFieldError(phoneField, 'Format de téléphone invalide');
            isValid = false;
        }

        if (!consentField.checked) {
            isValid = false;
            // Vous pouvez ajouter un message d'erreur pour la checkbox si nécessaire
        }

        if (!isValid) {
            this.showAlert(alertError, 'Veuillez corriger les erreurs du formulaire');
            return;
        }

        // Préparation des données
        const formData = {
            email: emailField.value.trim(),
            phone: phoneField.value.trim(),
            consent: consentField.checked
        };

        // Affichage du loading
        this.setLoadingState(submitBtn, submitText, loadingSpinner, true);

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
                this.showAlert(alertSuccess, '✅ Merci ! Nous vous contacterons dès le lancement.');
                form.reset();
                // Cacher le message après 5 secondes
                setTimeout(() => {
                    this.hideAlert(alertSuccess);
                }, 5000);
            } else {
                this.showAlert(alertError, result.message || '❌ Une erreur est survenue');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showAlert(alertError, '❌ Erreur de connexion au serveur');
        } finally {
            this.setLoadingState(submitBtn, submitText, loadingSpinner, false);
        }
    }

    setLoadingState(button, textElement, spinner, loading) {
        if (loading) {
            button.disabled = true;
            if (textElement) textElement.textContent = 'Envoi en cours...';
            if (spinner) spinner.classList.remove('hidden');
        } else {
            button.disabled = false;
            if (textElement) textElement.textContent = button === document.querySelector('#betaFormBottom button') ? 'Rejoindre maintenant' : 'Commencer';
            if (spinner) spinner.classList.add('hidden');
        }
    }

    showAlert(alertElement, message) {
        if (alertElement) {
            const messageSpan = alertElement.querySelector('span');
            if (messageSpan) messageSpan.textContent = message;
            alertElement.classList.remove('hidden');
            // Cacher les autres alertes
            this.hideAllAlertsExcept(alertElement);
        }
    }

    hideAlert(alertElement) {
        if (alertElement) {
            alertElement.classList.add('hidden');
        }
    }

    hideAllAlertsExcept(exceptAlert) {
        document.querySelectorAll('.alert').forEach(alert => {
            if (alert !== exceptAlert) {
                alert.classList.add('hidden');
            }
        });
    }
}

// Navigation smooth scroll
class SmoothScroll {
    constructor() {
        this.init();
    }

    init() {
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const offsetTop = targetElement.offsetTop - 80; // Compensation pour la navbar fixe
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
}

// Animation au scroll
// class ScrollAnimations {
//     constructor() {
//         this.init();
//     }

//     init() {
//         const observerOptions = {
//             threshold: 0.1,
//             rootMargin: '0px 0px -50px 0px'
//         };

//         const observer = new IntersectionObserver((entries) => {
//             entries.forEach(entry => {
//                 if (entry.isIntersecting) {
//                     entry.target.classList.add('animate-fade-in');
//                 }
//             });
//         }, observerOptions);

//         // Observer les éléments à animer
//         document.querySelectorAll('.glass-card, .feature-icon, .stat-card').forEach(el => {
//             observer.observe(el);
//         });
//     }
// }

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new ParticlesBackground();
    new FormManager();
    new SmoothScroll();
    new ScrollAnimations();
    
    // Démarrer l'animation de texte après un délai
    setTimeout(() => {
        new TextAnimation();
    }, 1000);
    // Effet de vague sur le texte après l'animation
    const animateTextWaves = () => {
        const animatedText = document.getElementById('animated-text');
        if (animatedText) {
            const words = animatedText.querySelectorAll('strong');
            words.forEach((word, index) => {
                setTimeout(() => {
                    word.classList.add('animate-underline');
                }, index * 200);
            });
        }
    };
    
    // Démarrer l'effet vague après l'animation principale
    setTimeout(animateTextWaves, 8000);

    // Ajout de la classe CSS pour l'animation fade-in
    const style = document.createElement('style');
    style.textContent = `
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .field-error {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .form-input.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
    `;
    document.head.appendChild(style);
});
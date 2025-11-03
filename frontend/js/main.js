// Particle system
function createParticles() {
    const container = document.getElementById('particles-container');
    const particleCount = 30;
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const size = Math.random() * 6 + 3;
        const x = Math.random() * window.innerWidth;
        const y = Math.random() * window.innerHeight;
        
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = x + 'px';
        particle.style.top = y + 'px';
        
        container.appendChild(particle);
        
        // Animate particles
        anime({
            targets: particle,
            translateX: () => anime.random(-150, 150),
            translateY: () => anime.random(-150, 150),
            scale: [0, 1, 0],
            opacity: [0, 0.4, 0],
            duration: () => anime.random(4000, 8000),
            loop: true,
            easing: 'easeInOutQuad'
        });
    }
}

// Track page visit
async function trackPageVisit() {
    try {
        await fetch('/api/track-visit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
    } catch (error) {
        console.log('Visit tracking failed:', error);
    }
}

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Fonction pour envoyer les données
async function submitBetaForm(formData, formId) {
    const submitText = document.getElementById(`submitText${formId}`);
    const loadingSpinner = document.getElementById(`loadingSpinner${formId}`);
    const alertSuccess = document.getElementById(`alertSuccess${formId}`);
    const alertError = document.getElementById(`alertError${formId}`);
    
    // Afficher le loading
    submitText.textContent = 'Envoi en cours...';
    loadingSpinner.classList.remove('hidden');
    
    try {
        const response = await fetch('/api/beta-signup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (response.ok) {
            // Succès
            alertSuccess.classList.remove('hidden');
            alertError.classList.add('hidden');
            document.getElementById(`betaForm${formId}`).reset();
        } else {
            // Erreur
            throw new Error(result.detail || 'Une erreur est survenue');
        }
    } catch (error) {
        // Erreur réseau ou serveur
        alertError.classList.remove('hidden');
        document.getElementById(`errorMessage${formId}`).textContent = error.message;
        alertSuccess.classList.add('hidden');
    } finally {
        // Réinitialiser le bouton
        submitText.textContent = formId === '' ? 'Rejoindre la liste d\'attente' : 'Rejoindre maintenant';
        loadingSpinner.classList.add('hidden');
    }
}

// Gestion du formulaire principal
document.getElementById('betaForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        consent: document.getElementById('consent').checked,
        source: 'hero_section',
        timestamp: new Date().toISOString()
    };
    
    submitBetaForm(formData, '');
});

// Gestion du formulaire secondaire
document.getElementById('betaFormBottom')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        email: document.getElementById('emailBottom').value,
        phone: document.getElementById('phoneBottom').value,
        consent: document.getElementById('consentBottom').checked,
        source: 'bottom_section',
        timestamp: new Date().toISOString()
    };
    
    submitBetaForm(formData, 'Bottom');
});

// Validation du téléphone
function validatePhone(phone) {
    const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,9}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Validation en temps réel
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && !validatePhone(this.value)) {
            this.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.3)';
        } else {
            this.style.boxShadow = '';
        }
    });
});

// Animate elements on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe all cards
document.querySelectorAll('.glass-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
    observer.observe(el);
});

// Initialize particles and track visit when page loads
document.addEventListener('DOMContentLoaded', function() {
    trackPageVisit();
    createParticles();
});
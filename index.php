<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Skills - L'IA qui révolutionne votre carrière</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/main.js" defer></script>
    <style>
        .form-container {
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.08);
        }
        .btn-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #ec4899 100%);
        }
        .btn-cyan:hover {
            background: linear-gradient(135deg, #0891b2 0%, #db2777 100%);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.15);
        }
    </style>
</head>
<body>
    <!-- Particles Background -->
    <div id="particles-container" class="fixed inset-0 pointer-events-none z-0"></div>
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <div class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-brain mr-3 text-cyan-500"></i>Skills
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#accueil" class="nav-link text-lg font-medium px-3 py-2 text-gray-700 hover:text-cyan-500 transition-colors">Accueil</a>
                        <a href="#fonctionnalites" class="nav-link text-lg font-medium px-3 py-2 text-gray-700 hover:text-cyan-500 transition-colors">Fonctionnalités</a>
                        <a href="#contact" class="nav-link text-lg font-medium px-3 py-2 text-gray-700 hover:text-cyan-500 transition-colors">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="accueil" class="relative min-h-screen flex items-center justify-center pt-20">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Logo animé -->
            <div class="animate-float mb-12">
                <div class="w-40 h-40 mx-auto mb-8 bg-gradient-to-br from-cyan-100 to-pink-100 rounded-3xl flex items-center justify-center border border-white/30">
                    <i class="fas fa-robot text-7xl text-cyan-500"></i>
                </div>
            </div>
            
            <!-- Titre principal -->
            <h1 class="text-6xl md:text-8xl font-bold mb-8 leading-tight">
                <span class="bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent">Skills</span>
            </h1>
            
            <p class="text-2xl md:text-3xl text-gray-700 mb-12 max-w-4xl mx-auto leading-relaxed font-light">
                L'IA qui révolutionne votre recherche d'emploi
            </p>
            
            <p class="text-xl text-gray-600 mb-16 max-w-2xl mx-auto leading-relaxed">
                <strong>Fini les modifications manuelles de CV et de lettres de motivation !</strong><br><br>
                
                Notre <strong>agent IA intelligent</strong> personnalise automatiquement vos documents pour chaque candidature. 
                Un simple <strong>commandement vocal</strong> suffit pour adapter votre profil à n'importe quelle offre.<br><br>
                
            </p>
            
            <!-- Formulaire principal -->
            <div class="max-w-2xl mx-auto mb-16">
                <div class="glass-card rounded-3xl p-8 form-container">
                    <h3 class="text-3xl font-bold text-gray-800 mb-4 text-center">
                        Soyez parmi les premiers
                    </h3>
                    <p class="text-gray-600 text-center mb-6 text-lg">
                        Rejoignez pour essayer
                    </p>
                    
                    <form id="betaForm" class="space-y-6">
                        <div id="alertSuccess" class="alert alert-success hidden">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Merci ! Nous vous contacterons dès le lancement.</span>
                        </div>
                        <div id="alertError" class="alert alert-error hidden">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="errorMessage">Une erreur s'est produite. Veuillez réessayer.</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="email" id="email" name="email" placeholder="Votre email" 
                                       class="w-full px-4 py-3 rounded-2xl form-input text-base border border-cyan-100 focus:border-cyan-300" required>
                            </div>
                            <div>
                                <input type="tel" id="phone" name="phone" placeholder="Votre téléphone" 
                                       class="w-full px-4 py-3 rounded-2xl form-input text-base border border-cyan-100 focus:border-cyan-300" required>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <input type="checkbox" id="consent" name="consent" class="mt-1 mr-3 rounded border-cyan-300 text-cyan-500 focus:ring-cyan-300" required>
                            <label for="consent" class="text-gray-600 text-sm">
                                J'accepte d'être contacté(e) lors du lancement de Skills
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full btn-cyan px-6 py-4 rounded-2xl font-semibold text-lg text-white transition-all flex items-center justify-center shadow-md">
                            <span id="submitText">Commencer</span>
                            <div id="loadingSpinner" class="loading-spinner ml-3 hidden"></div>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-500 mb-2">IA</div>
                    <div class="text-gray-600">Intelligence Avancée</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-500 mb-2">95%</div>
                    <div class="text-gray-600">Taux de Succès</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-500 mb-2">24/7</div>
                    <div class="text-gray-600">Disponible</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fonctionnalites" class="py-20 bg-gradient-to-br from-cyan-50/30 to-pink-50/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-5xl md:text-6xl font-bold text-gray-800 mb-6">
                    La puissance de <span class="text-cyan-500">l'IA</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez comment Skills transforme votre approche de la recherche d'emploi
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card rounded-3xl p-6 text-center hover-lift">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-pink-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-magic text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">CV Optimisé</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Notre IA analyse et optimise automatiquement votre CV pour chaque offre d'emploi
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-card rounded-3xl p-6 text-center hover-lift">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-pink-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-feather-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Lettres Personnalisées</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Des lettres de motivation uniques adaptées à chaque entreprise et poste
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass-card rounded-3xl p-6 text-center hover-lift">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-pink-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-md">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Suivi Intelligent</h3>
                    <p class="text-gray-600 leading-relaxed text-sm">
                        Analysez vos performances et améliorez votre stratégie de candidature
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="contact" class="py-20 bg-gradient-to-br from-cyan-50/40 to-pink-50/40">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="glass-card rounded-3xl p-8 form-container">
                <div class="w-20 h-20 bg-gradient-to-br from-cyan-100 to-pink-100 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/30">
                    <i class="fas fa-rocket text-3xl text-cyan-500"></i>
                </div>
                
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                    Prêt à révolutionner votre carrière ?
                </h2>
                
                <p class="text-xl text-gray-600 mb-8 leading-relaxed max-w-2xl mx-auto">
                    Rejoignez des milliers de professionnels qui ont déjà transformé leur recherche d'emploi avec Skills
                </p>
                
                <!-- Formulaire secondaire -->
                <div class="max-w-md mx-auto">
                    <form id="betaFormBottom" class="space-y-4">
                        <div id="alertSuccessBottom" class="alert alert-success hidden">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Merci ! Nous vous contacterons dès le lancement.</span>
                        </div>
                        <div id="alertErrorBottom" class="alert alert-error hidden">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="errorMessageBottom">Une erreur s'est produite. Veuillez réessayer.</span>
                        </div>
                        
                        <input type="email" id="emailBottom" name="email" placeholder="votre@email.com" 
                               class="w-full px-4 py-3 rounded-2xl form-input text-base border border-cyan-100 focus:border-cyan-300" required>
                        <input type="tel" id="phoneBottom" name="phone" placeholder="Votre numéro de téléphone" 
                               class="w-full px-4 py-3 rounded-2xl form-input text-base border border-cyan-100 focus:border-cyan-300" required>
                        
                        <div class="flex items-start">
                            <input type="checkbox" id="consentBottom" name="consent" class="mt-1 mr-3 rounded border-cyan-300 text-cyan-500 focus:ring-cyan-300" required>
                            <label for="consentBottom" class="text-gray-600 text-sm">
                                J'accepte d'être contacté(e) lors du lancement
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full btn-cyan px-6 py-4 rounded-2xl font-semibold text-lg text-white transition-all flex items-center justify-center shadow-md">
                            <span id="submitTextBottom">Rejoindre maintenant</span>
                            <div id="loadingSpinnerBottom" class="loading-spinner ml-3 hidden"></div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-gradient-to-br from-cyan-50/20 to-pink-50/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="text-3xl font-bold mb-4 text-gray-800">
                    <i class="fas fa-brain mr-3 text-cyan-500"></i>Skills
                </div>
                <p class="text-gray-600 mb-8 text-lg">L'IA qui révolutionne votre recherche d'emploi</p>
                <div class="flex justify-center space-x-6 mb-8">
                    <a href="https://x.com/RichardMil56104" class="text-gray-400 hover:text-cyan-500 transition-colors text-xl">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/richard-m-metta-ceo-9b395b257/" class="text-gray-400 hover:text-cyan-500 transition-colors text-xl">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
                <div class="border-t border-cyan-100 pt-8">
                    <p class="text-gray-500">
                        © 2024 NestCorporation. Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </footer>

 <script>
    // Gestion des formulaires - Version ultra-robuste
    document.addEventListener('DOMContentLoaded', function() {
                // Compteur de visites - À ajouter dans votre js/main.js existant
        class VisitCounter {
            constructor() {
                this.apiUrl = this.getApiUrl() + '/api/visits/count.php';
                this.init();
            }

            getApiUrl() {
                const baseUrl = window.location.origin;
                const pathname = window.location.pathname;
                
                if (pathname !== '/' && pathname !== '/index.html') {
                    const folders = pathname.split('/').filter(f => f);
                    if (folders.length > 0 && !folders.includes('api')) {
                        return baseUrl + '/' + folders[0];
                    }
                }
                
                return baseUrl;
            }

            async init() {
                try {
                    console.log('🔢 Enregistrement de la visite...');
                    
                    const response = await fetch(this.apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            page: 'index',
                            timestamp: new Date().toISOString()
                        })
                    });

                    if (response.ok) {
                        console.log('✅ Visite enregistrée avec succès');
                    } else {
                        console.warn('⚠️ Impossible d\'enregistrer la visite');
                    }
                } catch (error) {
                    console.error('❌ Erreur compteur visites:', error);
                }
            }
        }

        new VisitCounter();
        console.log('🚀 Initialisation des formulaires...');
        
        const API_URL = determineApiUrl();
        console.log('📍 URL API détectée:', API_URL);

        // Tester l'API au chargement
        testApiConnection(API_URL);

        initForm('betaForm', API_URL);
        initForm('betaFormBottom', API_URL);

        function determineApiUrl() {
            const base = window.location.origin;
            const path = window.location.pathname;
            
            // Si dans un sous-dossier comme /mon-site/
            if (path !== '/' && path !== '/index.html') {
                const firstFolder = path.split('/')[1];
                if (firstFolder) {
                    return `${base}/${firstFolder}/api/lead/add.php`;
                }
            }
            
            return `${base}/api/lead/add.php`;
        }

        async function testApiConnection(apiUrl) {
            console.log('🧪 Test de connexion API...');
            try {
                const testData = { email: 'test@test.com', phone: '0612345678' };
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(testData)
                });
                
                const text = await response.text();
                console.log('📨 Réponse brute:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('✅ API fonctionne - Réponse JSON:', data);
                } catch (e) {
                    console.error('❌ API ne renvoie pas du JSON:', text.substring(0, 200));
                }
            } catch (error) {
                console.error('💥 Erreur de test API:', error);
            }
        }

        function initForm(formId, apiUrl) {
            const form = document.getElementById(formId);
            if (!form) {
                console.warn('Formulaire non trouvé:', formId);
                return;
            }

            form.addEventListener('submit', async function(e) {
                await handleFormSubmit(e, form, apiUrl);
            });
        }

        async function handleFormSubmit(e, form, apiUrl) {
            e.preventDefault();
            
            const email = form.querySelector('input[type="email"]').value.trim();
            const phone = form.querySelector('input[type="tel"]').value.trim();
            const consent = form.querySelector('input[type="checkbox"]').checked;

            // Validation
            if (!email || !phone || !consent) {
                showAlert(form, 'Veuillez remplir tous les champs', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showAlert(form, 'Email invalide', 'error');
                return;
            }

            setFormLoading(form, true);

            try {
                console.log('🔄 Envoi vers:', apiUrl);
                
                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, phone })
                });

                // TOUJOURS lire la réponse comme texte d'abord
                const responseText = await response.text();
                console.log('📩 Réponse brute:', responseText);

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('❌ Réponse non-JSON:', responseText);
                    throw new Error('Le serveur a renvoyé une réponse invalide');
                }

                if (result.success) {
                    showAlert(form, result.message, 'success');
                    form.reset();
                } else {
                    showAlert(form, result.message, 'error');
                }

            } catch (error) {
                console.error('💥 Erreur complète:', error);
                showAlert(form, 'Erreur: ' + error.message, 'error');
            } finally {
                setFormLoading(form, false);
            }
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function setFormLoading(form, isLoading) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = form.querySelector('#submitText' + (form.id === 'betaFormBottom' ? 'Bottom' : ''));
            const spinner = form.querySelector('#loadingSpinner' + (form.id === 'betaFormBottom' ? 'Bottom' : ''));

            if (isLoading) {
                submitBtn.disabled = true;
                if (submitText) submitText.textContent = 'Envoi...';
                if (spinner) spinner.classList.remove('hidden');
            } else {
                submitBtn.disabled = false;
                if (submitText) submitText.textContent = form.id === 'betaFormBottom' ? 'Rejoindre' : 'Commencer';
                if (spinner) spinner.classList.add('hidden');
            }
        }

        function showAlert(form, message, type) {
            const alerts = form.querySelectorAll('.alert');
            alerts.forEach(alert => alert.classList.add('hidden'));

            const alertId = type === 'success' ? 'alertSuccess' : 'alertError';
            const alertElement = form.querySelector('#' + alertId + (form.id === 'betaFormBottom' ? 'Bottom' : ''));
            
            if (alertElement) {
                alertElement.querySelector('span').textContent = message;
                alertElement.classList.remove('hidden');
                
                setTimeout(() => {
                    alertElement.classList.add('hidden');
                }, type === 'success' ? 6000 : 4000);
            }
        }
    });
</script>
</body>
</html>
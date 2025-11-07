<?php
session_start();
require_once 'config/database.php';

// Vérifier si l'admin est connecté
$isLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Si pas connecté, afficher le formulaire de connexion
if (!$isLoggedIn) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Skills - Administration</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
        <style>
            body {
                background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 50%, #fdf2f8 100%);
                font-family: 'Inter', sans-serif;
            }
            .glass-card {
                background: rgba(255, 255, 255, 0.8);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 8px 25px rgba(6, 182, 212, 0.08);
            }
        </style>
    </head>
    <body class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-cyan-400 to-pink-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-brain text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Skills Admin</h2>
                <p class="mt-2 text-gray-600">Accès réservé aux administrateurs</p>
            </div>
            <form id="loginForm" class="mt-8 space-y-6 glass-card p-8 rounded-2xl">
                <div>
                    <input type="text" name="username" required 
                           class="w-full px-4 py-3 border border-cyan-100 rounded-xl focus:ring-2 focus:ring-cyan-300 focus:border-cyan-300 transition-colors"
                           placeholder="Nom d'utilisateur" value="admin">
                </div>
                <div>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 border border-cyan-100 rounded-xl focus:ring-2 focus:ring-cyan-300 focus:border-cyan-300 transition-colors"
                           placeholder="Mot de passe" value="admin123">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-pink-500 text-white py-3 px-4 rounded-xl hover:from-cyan-600 hover:to-pink-600 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Se connecter
                </button>
            </form>
        </div>
        <script>
            document.getElementById('loginForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                
                const response = await fetch('api/admin/login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Identifiants incorrects');
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #fdf2f8 100%);
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(240, 253, 250, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.08);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 25px rgba(6, 182, 212, 0.08);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="glass-panel rounded-2xl p-6 mb-8 border border-cyan-100">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-cyan-400 to-pink-400 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                        <i class="fas fa-brain text-xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Skills Dashboard</h1>
                        <p class="text-cyan-600 font-medium">Tableau de bord administrateur</p>
                    </div>
                </div>
                <button onclick="logout()" class="bg-gradient-to-r from-cyan-500 to-pink-500 text-white px-6 py-3 rounded-xl hover:from-cyan-600 hover:to-pink-600 transition-all font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                </button>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Ajoutez cette carte dans la grid des statistiques -->
            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="totalVisits">0</div>
                <div class="text-gray-600 font-medium mt-2">Visites Total</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>

            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="conversionRate">0%</div>
                <div class="text-gray-600 font-medium mt-2">Taux Conversion</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="totalLeads">0</div>
                <div class="text-gray-600 font-medium mt-2">Leads Totaux</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="todayLeads">0</div>
                <div class="text-gray-600 font-medium mt-2">Leads Aujourd'hui</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="leadsWithEmail">0</div>
                <div class="text-gray-600 font-medium mt-2">Avec Email</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>
            <div class="stat-card rounded-2xl p-6 text-center transition-all hover:transform hover:-translate-y-1 hover:shadow-lg">
                <div class="text-3xl font-bold bg-gradient-to-r from-cyan-500 to-pink-500 bg-clip-text text-transparent" id="leadsWithoutEmail">0</div>
                <div class="text-gray-600 font-medium mt-2">Sans Email</div>
                <div class="w-8 h-1 bg-gradient-to-r from-cyan-500 to-pink-500 rounded-full mx-auto mt-3"></div>
            </div>
        </div>

        <!-- Liste des leads -->
        <div class="glass-panel rounded-2xl overflow-hidden border border-cyan-100">
            <div class="px-6 py-4 border-b border-cyan-100 bg-gradient-to-r from-cyan-50 to-pink-50">
                <h2 class="text-xl font-bold text-gray-800">
                    <i class="fas fa-list mr-2 text-cyan-500"></i>Liste des Leads
                </h2>
                <p class="text-cyan-600 text-sm">Dernières inscriptions</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cyan-100">
                    <thead class="bg-gradient-to-r from-cyan-50 to-pink-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">
                                <i class="fas fa-hashtag mr-1"></i>ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">
                                <i class="fas fa-envelope mr-1"></i>Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">
                                <i class="fas fa-phone mr-1"></i>Téléphone
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-cyan-700 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-1"></i>Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cyan-50" id="leadsTableBody">
                        <!-- Les leads seront chargés ici -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer info -->
        <div class="mt-6 text-center">
            <p class="text-cyan-600 text-sm">
                <i class="fas fa-sync-alt mr-1"></i>Actualisation automatique toutes les 30 secondes
            </p>
        </div>
    </div>

    <script>
    async function loadStats() {
        try {
            const response = await fetch('api/admin/stats.php');
            const text = await response.text();
            
            try {
                const stats = JSON.parse(text);
                
                if (stats.success) {
                    document.getElementById('totalLeads').textContent = stats.data.total_leads;
                    document.getElementById('todayLeads').textContent = stats.data.today_leads;
                    document.getElementById('leadsWithEmail').textContent = stats.data.leads_with_email;
                    document.getElementById('leadsWithoutEmail').textContent = stats.data.leads_without_email;
                } else {
                    console.error('Erreur stats:', stats.message);
                }
            } catch (e) {
                console.error('Réponse non-JSON stats:', text.substring(0, 200));
            }
        } catch (error) {
            console.error('Erreur fetch stats:', error);
        }
    }

    async function loadLeads() {
        try {
            const response = await fetch('api/admin/leads.php');
            const text = await response.text();
            
            try {
                const leads = JSON.parse(text);
                
                if (leads.success) {
                    const tbody = document.getElementById('leadsTableBody');
                    if (leads.data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-cyan-600">
                                    <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">Aucun lead pour le moment</p>
                                </td>
                            </tr>
                        `;
                    } else {
                        tbody.innerHTML = leads.data.map(lead => `
                            <tr class="hover:bg-cyan-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cyan-700">${lead.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${lead.email || '<span class="text-gray-400">-</span>'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-700">${lead.phone}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1 text-cyan-500"></i>${new Date(lead.created_at).toLocaleDateString('fr-FR')}
                                </td>
                            </tr>
                        `).join('');
                    }
                } else {
                    console.error('Erreur leads:', leads.message);
                }
            } catch (e) {
                console.error('Réponse non-JSON leads:', text.substring(0, 200));
            }
        } catch (error) {
            console.error('Erreur fetch leads:', error);
        }
    }

    async function logout() {
        try {
            await fetch('api/admin/logout.php');
            window.location.reload();
        } catch (error) {
            console.error('Erreur logout:', error);
            window.location.reload();
        }
    }
    async function loadVisitStats() {
    try {
        const response = await fetch('api/visits/stats.php');
        const text = await response.text();
        
        try {
            const stats = JSON.parse(text);
            
            if (stats.success) {
                document.getElementById('totalVisits').textContent = stats.data.total_visits.toLocaleString();
                document.getElementById('conversionRate').textContent = stats.data.conversion_rate + '%';
                
                // Mettre à jour les stats existantes avec les leads
                document.getElementById('totalLeads').textContent = stats.data.total_leads;
                
                console.log('📊 Stats visites:', stats.data);
            } else {
                console.error('Erreur stats visites:', stats.message);
            }
        } catch (e) {
            console.error('Réponse non-JSON stats visites:', text.substring(0, 200));
        }
    } catch (error) {
        console.error('Erreur fetch stats visites:', error);
    }
}

    // Charger les données au démarrage
    loadStats();
    loadLeads();
    loadVisitStats(); // ← AJOUTEZ CETTE LIGNE

    // Actualiser toutes les 30 secondes
    setInterval(() => {
        loadStats();
        loadLeads();
        loadVisitStats(); // ← AJOUTEZ CETTE LIGNE
    }, 30000);
</script>
</body>
</html>
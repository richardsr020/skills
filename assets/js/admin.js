class AdminDashboard {
    constructor() {
        this.isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
        
        if (this.isLoggedIn) {
            this.initDashboard();
        } else {
            this.initLogin();
        }
    }

    initLogin() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
    }

    initDashboard() {
        this.loadStats();
        this.loadLeads();
        
        // Actualiser toutes les 30 secondes
        setInterval(() => {
            this.loadStats();
            this.loadLeads();
        }, 30000);
    }

    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const loginBtn = document.getElementById('loginBtn');
        const messageContainer = document.getElementById('message-container');

        loginBtn.disabled = true;
        loginBtn.textContent = 'Connexion...';

        try {
            const response = await fetch('api/admin/login.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                messageContainer.innerHTML = `
                    <div class="alert alert-success">
                        ✅ Connexion réussie! Redirection...
                    </div>
                `;
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                messageContainer.innerHTML = `
                    <div class="alert alert-error">
                        ❌ ${result.message || 'Erreur de connexion'}
                    </div>
                `;
            }
        } catch (error) {
            console.error('Erreur:', error);
            messageContainer.innerHTML = `
                <div class="alert alert-error">
                    ❌ Erreur de connexion au serveur
                </div>
            `;
        } finally {
            loginBtn.disabled = false;
            loginBtn.textContent = 'Se connecter';
        }
    }

    async loadStats() {
        try {
            const response = await fetch('api/admin/stats.php');
            const stats = await response.json();

            if (stats.success) {
                this.renderStats(stats.data);
            } else {
                this.showError('Erreur lors du chargement des statistiques');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur de connexion');
        }
    }

    async loadLeads() {
        try {
            const response = await fetch('api/admin/leads.php');
            const leads = await response.json();

            if (leads.success) {
                this.renderLeads(leads.data);
            } else {
                this.showError('Erreur lors du chargement des leads');
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showError('Erreur de connexion');
        }
    }

    renderStats(stats) {
        const statsContainer = document.getElementById('stats-container');
        
        statsContainer.innerHTML = `
            <div class="stat-card">
                <div class="stat-number">${stats.total_leads}</div>
                <div class="stat-label">Leads Totaux</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.today_leads}</div>
                <div class="stat-label">Leads Aujourd'hui</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.leads_with_email}</div>
                <div class="stat-label">Avec Email</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">${stats.leads_without_email}</div>
                <div class="stat-label">Sans Email</div>
            </div>
        `;
    }

    renderLeads(leads) {
        const leadsContainer = document.getElementById('leads-container');
        
        if (leads.length === 0) {
            leadsContainer.innerHTML = '<div class="alert alert-info">Aucun lead pour le moment</div>';
            return;
        }

        let html = `
            <table class="leads-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
        `;

        leads.forEach(lead => {
            html += `
                <tr>
                    <td>${lead.id}</td>
                    <td>${this.formatPhone(lead.phone)}</td>
                    <td>${lead.email || '<em style="color: #999;">Non renseigné</em>'}</td>
                    <td>${this.formatDate(lead.created_at)}</td>
                </tr>
            `;
        });

        html += '</tbody></table>';
        leadsContainer.innerHTML = html;
    }

    formatPhone(phone) {
        // Formater le téléphone français
        return phone.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    showError(message) {
        const statsContainer = document.getElementById('stats-container');
        const leadsContainer = document.getElementById('leads-container');
        
        if (statsContainer) {
            statsContainer.innerHTML = `<div class="alert alert-error">${message}</div>`;
        }
        if (leadsContainer) {
            leadsContainer.innerHTML = `<div class="alert alert-error">${message}</div>`;
        }
    }
}

// Fonction de déconnexion
function logout() {
    if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
        fetch('api/admin/logout.php')
            .then(() => {
                window.location.reload();
            })
            .catch(error => {
                console.error('Erreur:', error);
                window.location.reload();
            });
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});

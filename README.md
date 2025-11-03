# 🚀 Skills Platform - Documentation

## 📖 Table des matières
- [Description](#-description)
- [Fonctionnalités](#-fonctionnalités)
- [Architecture](#-architecture)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [API Documentation](#-api-documentation)
- [Dashboard Admin](#-dashboard-admin)
- [Développement](#-développement)

## 🎯 Description

**Skills** est une plateforme moderne de gestion de leads et d'analytics avec interface d'administration complète. Le projet comprend un backend FastAPI et un frontend responsive.

## ✨ Fonctionnalités

### 🔹 Frontend Public
- **Page d'accueil** avec design moderne
- **Formulaire d'inscription** beta
- **Tracking automatique** des visites
- **Interface responsive** (mobile & desktop)

### 🔹 Dashboard Admin
- **Analytics en temps réel**
- **Gestion des contacts** et leads
- **Graphiques interactifs** (Chart.js)
- **Sécurité par mot de passe**
- **Filtres et recherche** avancée

### 🔹 Backend API
- **API RESTful** complète
- **Gestion CORS** configurable
- **Sauvegarde des données** en base
- **Tracking des statistiques**

## 🏗 Architecture

```
skills-platform/
├── 📁 backend/
│   ├── main.py              # Application FastAPI principale
│   ├── database.py          # Gestion de la base de données
│   ├── models.py            # Modèles Pydantic
│   ├── visit_tracker.py     # Système de tracking
│   └── requirements.txt     # Dépendances Python
│
├── 📁 frontend/
│   ├── index.html           # Page d'accueil publique
│   ├── admin_login.html     # Connexion admin
│   ├── admin_dashboard.html # Dashboard admin
│   ├── 📁 css/
│   │   └── style.css        # Styles personnalisés
│   └── 📁 static/           # Assets statiques
│
└── README.md
```

## 🚀 Installation

### Prérequis
- Python 3.8+
- pip (gestionnaire de packages Python)

### 1. Cloner le projet
```bash
git clone <votre-repo>
cd skills-platform
```

### 2. Configuration du backend
```bash
cd backend

# Installer les dépendances
pip install -r requirements.txt

# Ou installer manuellement
pip install fastapi uvicorn python-multipart jinja2
```

### 3. Lancer l'application
```bash
# Depuis le dossier backend
python main.py
```

L'application sera accessible sur : `http://localhost:8000`

## ⚙ Configuration

### Variables d'environnement
Le projet utilise des configurations par défaut :

| Variable | Valeur par défaut | Description |
|----------|------------------|-------------|
| `HOST` | `0.0.0.0` | Adresse d'écoute |
| `PORT` | `8000` | Port du serveur |
| `ADMIN_PASSWORD` | `richardA022A` | Mot de passe admin |

### Structure de la base de données
Les données sont sauvegardées dans des fichiers JSON :
- `beta_users.json` - Inscriptions beta
- `visits.json` - Statistiques de visites
- `analytics.json` - Données analytiques

## 📡 API Documentation

### Endpoints Publics

#### 🔹 Inscription Beta
```http
POST /api/beta-signup
Content-Type: application/json

{
  "email": "user@example.com",
  "userType": "professional",
  "consent": true
}
```

#### 🔹 Tracking des visites
```http
POST /api/track-visit
```

#### 🔹 Récupération des stats
```http
GET /api/stats
```

### Endpoints Admin (Protégés)

#### 🔹 Analytics
```http
GET /api/admin/analytics?password=xxx
```

#### 🔹 Liste des utilisateurs
```http
GET /api/beta-users
```

## 🔐 Dashboard Admin

### Accès
1. Aller sur : `http://localhost:8000/adm`
2. Entrer le mot de passe : `richardA022A`

### Sections du Dashboard

#### 📊 Aperçu
- **Statistiques principales** (contacts, visites, conversion)
- **Graphiques** des sources et inscriptions
- **Derniers contacts** en temps réel

#### 👥 Contacts
- **Liste complète** des leads
- **Filtres** par type de profil
- **Recherche** en temps réel

#### 📈 Analytics
- **Métriques détaillées** de performance
- **Répartition** des profils
- **Comparaison** visites vs inscriptions

## 🛠 Développement

### Structure du code

#### Backend (FastAPI)
```python
# Modèle de données
class BetaUserCreate(BaseModel):
    email: str
    userType: str
    consent: bool

# Route API
@app.post("/api/beta-signup")
async def beta_signup(user: BetaUserCreate):
    # Traitement de l'inscription
```

#### Frontend (JavaScript)
```javascript
// Chargement des données admin
async function loadAdminData() {
    const response = await fetch(`/api/admin/analytics?password=${password}`);
    const data = await response.json();
    updateDashboard(data);
}
```

### Personnalisation

#### Modifier le mot de passe admin
Dans `backend/main.py` :
```python
ADMIN_PASSWORD = "votre-nouveau-mot-de-passe"
```

#### Ajouter de nouvelles statistiques
Dans `backend/database.py` :
```python
def get_analytics():
    return {
        "custom_metric": your_calculation,
        # ... autres métriques
    }
```

## 🐛 Dépannage

### Problèmes courants

#### ❌ "Module not found"
```bash
# S'assurer d'être dans le bon dossier
cd backend
pip install -r requirements.txt
```

#### ❌ "CORS Error"
- Vérifier la configuration CORS dans `main.py`
- S'assurer que le frontend accède à la bonne URL

#### ❌ "Password incorrect"
- Vérifier le mot de passe dans `main.py`
- Utiliser l'URL : `/adm?password=votre_mot_de_passe`

#### ❌ Données non affichées
- Vérifier que les fichiers JSON existent dans `backend/data/`
- Vérifier les permissions en écriture

## 📊 Fonctionnalités Avancées

### Tracking des visites
- Compteur total de visites
- Statistiques quotidiennes
- Historique sur 7 jours

### Gestion des contacts
- Détection automatique du type de profil
- Filtrage par source d'acquisition
- Export visuel des données

### Sécurité
- Protection par mot de passe
- Validation des données d'entrée
- Gestion des erreurs sécurisée

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push sur la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 License

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## 🔗 Liens utiles

- 📧 **Support** : Contactez-nous pour toute question
- 🐛 **Bugs** : Ouvrir une issue sur GitHub
- 💡 **Suggestions** : Les contributions sont bienvenues

**Développé avec ❤️ pour la plateforme Skills**

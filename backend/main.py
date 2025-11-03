from fastapi import FastAPI, HTTPException, Request, Form, Depends
from fastapi.responses import RedirectResponse, JSONResponse, HTMLResponse
from fastapi.staticfiles import StaticFiles
from fastapi.middleware.cors import CORSMiddleware
from fastapi.templating import Jinja2Templates
from typing import Optional
import os
import sys

# Ajouter le chemin pour les imports
current_dir = os.path.dirname(os.path.abspath(__file__))
project_root = os.path.dirname(current_dir)
sys.path.append(current_dir)

from database import init_database
from models import BetaUserCreate
from typing import List

# Initialiser la base de données
init_database()

app = FastAPI(title="Skills API", description="API pour la plateforme Skills")

# 🌐 Autoriser le front à communiquer avec l'API (CORS)
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# 🗂 Servir les fichiers statiques depuis le dossier frontend
frontend_dir = os.path.join(project_root, "frontend")
app.mount("/static", StaticFiles(directory=frontend_dir), name="static")

# Templates pour le dashboard
templates = Jinja2Templates(directory=frontend_dir)

# 🔹 Mot de passe admin
ADMIN_PASSWORD = "richardA022A"

# 🔹 Vérification du mot de passe
def verify_password(password: str) -> bool:
    return password == ADMIN_PASSWORD

# 🚀 Rediriger la racine "/" vers la page principale
@app.get("/")
def root():
    return RedirectResponse(url="/static/index.html")

# 🔹 API pour l'inscription beta
@app.post("/api/beta-signup")
async def beta_signup(user: BetaUserCreate):
    try:
        # Valider le consentement
        if not user.consent:
            raise HTTPException(status_code=400, detail="Le consentement est requis")
        
        # Sauvegarder dans la base de données
        from database import save_beta_user
        success = save_beta_user(user.dict())
        
        if not success:
            raise HTTPException(status_code=400, detail="Cet email est déjà inscrit")
        
        return {
            "success": True,
            "message": "Inscription réussie ! Nous vous contacterons bientôt.",
            "data": {
                "email": user.email,
                "userType": user.userType
            }
        }
        
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# 🔹 API pour tracker les visites
@app.post("/api/track-visit")
async def track_visit(request: Request):
    try:
        from database import track_visit
        client_host = request.client.host if request.client else "unknown"
        user_agent = request.headers.get("user-agent", "unknown")
        
        track_visit(client_host, user_agent)
        return {"success": True, "message": "Visit tracked"}
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# 🎯 Page de connexion admin - GET
@app.get("/adm")
async def admin_login_page(request: Request, password: Optional[str] = None):
    # Si le mot de passe est fourni en paramètre et est correct, afficher le dashboard
    if password and verify_password(password):
        return templates.TemplateResponse("admin_dashboard.html", {"request": request})
    
    # Sinon, afficher la page de login
    return templates.TemplateResponse("admin_login.html", {"request": request})

# 🎯 Traitement du formulaire de connexion - POST
@app.post("/adm")
async def admin_login_submit(request: Request, password: str = Form(...)):
    if verify_password(password):
        # Mot de passe correct - afficher le dashboard
        return templates.TemplateResponse("admin_dashboard.html", {"request": request})
    else:
        # Mot de passe incorrect - réafficher la page de login avec erreur
        return templates.TemplateResponse(
            "admin_login.html", 
            {
                "request": request, 
                "error": "Mot de passe incorrect"
            }
        )

# 🔹 API pour les données du dashboard admin
@app.get("/api/admin/analytics")
async def get_admin_analytics(password: Optional[str] = None):
    if not verify_password(password):
        raise HTTPException(status_code=401, detail="Non autorisé")
    
    try:
        from database import get_analytics, get_all_contacts
        analytics = get_analytics()
        contacts = get_all_contacts()
        return {
            "analytics": analytics,
            "contacts": contacts
        }
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# 🔹 API pour récupérer tous les beta testers
@app.get("/api/beta-users")
async def get_beta_users():
    try:
        from database import get_all_beta_users
        users = get_all_beta_users()
        return users
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

# 🔹 API pour les statistiques
@app.get("/api/stats")
async def get_stats():
    try:
        from database import get_stats
        stats = get_stats()
        return stats
    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
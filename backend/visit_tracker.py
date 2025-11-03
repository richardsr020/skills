import os
from datetime import datetime
import json

VISITS_FILE = "visits_data.json"

def init_visits_file():
    """Initialise le fichier de visites s'il n'existe pas"""
    if not os.path.exists(VISITS_FILE):
        data = {
            "total_visits": 0,
            "today_visits": 0,
            "last_reset": datetime.now().strftime("%Y-%m-%d"),
            "daily_visits": {}
        }
        save_visits_data(data)

def load_visits_data():
    """Charge les données de visites depuis le fichier"""
    try:
        with open(VISITS_FILE, 'r') as f:
            return json.load(f)
    except (FileNotFoundError, json.JSONDecodeError):
        init_visits_file()
        return load_visits_data()

def save_visits_data(data):
    """Sauvegarde les données de visites dans le fichier"""
    with open(VISITS_FILE, 'w') as f:
        json.dump(data, f, indent=2)

def track_visit_file():
    """Enregistre une visite dans le fichier"""
    data = load_visits_data()
    today = datetime.now().strftime("%Y-%m-%d")
    
    # Réinitialiser le compteur du jour si c'est un nouveau jour
    if data["last_reset"] != today:
        data["today_visits"] = 0
        data["last_reset"] = today
    
    # Incrémenter les compteurs
    data["total_visits"] += 1
    data["today_visits"] += 1
    
    # Enregistrer la visite quotidienne
    if today not in data["daily_visits"]:
        data["daily_visits"][today] = 0
    data["daily_visits"][today] += 1
    
    save_visits_data(data)
    return True

def get_visit_stats_file():
    """Récupère les statistiques de visites depuis le fichier"""
    data = load_visits_data()
    today = datetime.now().strftime("%Y-%m-%d")
    
    # Préparer les données des 7 derniers jours
    last_7_days = []
    for i in range(6, -1, -1):
        date = datetime.now().replace(hour=0, minute=0, second=0, microsecond=0)
        date = date.replace(day=date.day - i)
        date_str = date.strftime("%Y-%m-%d")
        
        last_7_days.append({
            "date": date_str,
            "count": data["daily_visits"].get(date_str, 0)
        })
    
    return {
        "total_visits": data["total_visits"],
        "today_visits": data["today_visits"],
        "last_7_days": last_7_days
    }
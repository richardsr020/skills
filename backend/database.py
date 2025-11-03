import sqlite3
import os
from datetime import datetime

# def init_database():
#     """Initialise la base de données SQLite si elle n'existe pas"""
#     db_path = "skills.db"
    
#     if not os.path.exists(db_path):
#         print("Création de la base de données...")
#         conn = sqlite3.connect(db_path)
#         cursor = conn.cursor()
        
#         # Table pour les beta testers
#         cursor.execute('''
#             CREATE TABLE beta_users (
#                 id INTEGER PRIMARY KEY AUTOINCREMENT,
#                 first_name TEXT,
#                 last_name TEXT,
#                 email TEXT UNIQUE NOT NULL,
#                 phone TEXT NOT NULL,
#                 user_type TEXT,
#                 source TEXT DEFAULT 'unknown',
#                 signup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
#                 status TEXT DEFAULT 'pending'
#             )
#         ''')
        
#         # Table pour les statistiques (optionnelle)
#         cursor.execute('''
#             CREATE TABLE stats (
#                 id INTEGER PRIMARY KEY AUTOINCREMENT,
#                 total_users INTEGER DEFAULT 0,
#                 last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
#             )
#         ''')
        
#         # Insérer une ligne initiale pour les stats
#         cursor.execute('INSERT INTO stats (total_users) VALUES (0)')
        
#         conn.commit()
#         conn.close()
#         print("Base de données créée avec succès!")

def init_database():
    """Initialise la base de données SQLite si elle n'existe pas"""
    db_path = "skills.db"
    
    if not os.path.exists(db_path):
        print("Création de la base de données...")
        conn = sqlite3.connect(db_path)
        cursor = conn.cursor()
        
        # Table pour les beta testers
        cursor.execute('''
            CREATE TABLE beta_users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                first_name TEXT,
                last_name TEXT,
                email TEXT UNIQUE NOT NULL,
                phone TEXT NOT NULL,
                user_type TEXT,
                source TEXT DEFAULT 'unknown',
                signup_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status TEXT DEFAULT 'pending'
            )
        ''')
        
        # Table pour les statistiques (optionnelle)
        cursor.execute('''
            CREATE TABLE stats (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                total_users INTEGER DEFAULT 0,
                last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        # Table pour le tracking des visites
        cursor.execute('''
            CREATE TABLE page_visits (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                visit_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                ip_address TEXT,
                user_agent TEXT
            )
        ''')
        
        # Insérer une ligne initiale pour les stats
        cursor.execute('INSERT INTO stats (total_users) VALUES (0)')
        
        conn.commit()
        conn.close()
        print("Base de données créée avec succès!")

def track_visit(ip_address=None, user_agent=None):
    """Enregistre une visite de page"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    try:
        cursor.execute('''
            INSERT INTO page_visits (ip_address, user_agent)
            VALUES (?, ?)
        ''', (ip_address, user_agent))
        
        conn.commit()
        return True
    except Exception as e:
        print(f"Erreur lors du tracking de visite: {e}")
        return False
    finally:
        conn.close()

def get_visit_stats():
    """Récupère les statistiques de visites"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Total des visites
    cursor.execute('SELECT COUNT(*) FROM page_visits')
    total_visits = cursor.fetchone()[0]
    
    # Visites aujourd'hui
    cursor.execute('SELECT COUNT(*) FROM page_visits WHERE DATE(visit_date) = DATE("now")')
    today_visits = cursor.fetchone()[0]
    
    # Visites des 7 derniers jours
    cursor.execute('''
        SELECT DATE(visit_date) as date, COUNT(*) as count 
        FROM page_visits 
        WHERE visit_date >= DATE('now', '-7 days') 
        GROUP BY DATE(visit_date) 
        ORDER BY date DESC
    ''')
    last_7_days = cursor.fetchall()
    
    conn.close()
    
    return {
        'total_visits': total_visits,
        'today_visits': today_visits,
        'last_7_days': [{'date': row[0], 'count': row[1]} for row in last_7_days]
    }

def get_analytics():
    """Récupère les statistiques analytiques pour le dashboard"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Total des utilisateurs inscrits
    cursor.execute('SELECT COUNT(*) FROM beta_users')
    total_users = cursor.fetchone()[0]
    
    # Utilisateurs avec email seulement
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE phone IS NULL OR phone = ""')
    email_only = cursor.fetchone()[0]
    
    # Utilisateurs avec téléphone seulement
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE email IS NULL OR email = ""')
    phone_only = cursor.fetchone()[0]
    
    # Utilisateurs avec email ET téléphone
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE email IS NOT NULL AND email != "" AND phone IS NOT NULL AND phone != ""')
    complete_profiles = cursor.fetchone()[0]
    
    # Inscriptions par source
    cursor.execute('SELECT source, COUNT(*) FROM beta_users GROUP BY source')
    sources = cursor.fetchall()
    
    # Dernières inscriptions (7 derniers jours)
    cursor.execute('''
        SELECT DATE(signup_date) as date, COUNT(*) as count 
        FROM beta_users 
        WHERE signup_date >= DATE('now', '-7 days') 
        GROUP BY DATE(signup_date) 
        ORDER BY date DESC
    ''')
    last_7_days_signups = cursor.fetchall()
    
    # Statistiques de visites
    visit_stats = get_visit_stats()
    
    # Calcul du taux de conversion
    conversion_rate = (total_users / visit_stats['total_visits'] * 100) if visit_stats['total_visits'] > 0 else 0
    
    conn.close()
    
    return {
        'total_users': total_users,
        'email_only': email_only,
        'phone_only': phone_only,
        'complete_profiles': complete_profiles,
        'conversion_rate': conversion_rate,
        'profile_completion_rate': (complete_profiles / total_users * 100) if total_users > 0 else 0,
        'sources': dict(sources),
        'last_7_days_signups': [{'date': row[0], 'count': row[1]} for row in last_7_days_signups],
        'visits': visit_stats
    }


def get_db_connection():
    """Retourne une connexion à la base de données"""
    return sqlite3.connect("skills.db", check_same_thread=False)

def save_beta_user(user_data):
    """Sauvegarde un nouvel utilisateur beta dans la base de données"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    try:
        cursor.execute('''
            INSERT INTO beta_users 
            (first_name, last_name, email, phone, user_type, source, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ''', (
            user_data.get('firstName'),
            user_data.get('lastName'),
            user_data['email'],
            user_data['phone'],
            user_data.get('userType'),
            user_data.get('source', 'unknown'),
            'pending'
        ))
        
        # Mettre à jour les statistiques
        cursor.execute('UPDATE stats SET total_users = total_users + 1, last_updated = CURRENT_TIMESTAMP')
        
        conn.commit()
        return True
    except sqlite3.IntegrityError:
        # Email déjà existant
        return False
    except Exception as e:
        print(f"Erreur lors de la sauvegarde: {e}")
        return False
    finally:
        conn.close()

def get_all_beta_users():
    """Récupère tous les utilisateurs beta"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    cursor.execute('''
        SELECT id, first_name, last_name, email, phone, user_type, source, signup_date, status
        FROM beta_users 
        ORDER BY signup_date DESC
    ''')
    
    users = cursor.fetchall()
    conn.close()
    
    return [{
        'id': user[0],
        'first_name': user[1],
        'last_name': user[2],
        'email': user[3],
        'phone': user[4],
        'user_type': user[5],
        'source': user[6],
        'signup_date': user[7],
        'status': user[8]
    } for user in users]

def get_stats():
    """Récupère les statistiques"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    cursor.execute('SELECT total_users, last_updated FROM stats WHERE id = 1')
    stats = cursor.fetchone()
    
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE DATE(signup_date) = DATE("now")')
    today_signups = cursor.fetchone()[0]
    
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE user_type = "candidate"')
    candidates = cursor.fetchone()[0]
    
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE user_type = "recruiter"')
    recruiters = cursor.fetchone()[0]
    
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE user_type = "both"')
    both = cursor.fetchone()[0]
    
    conn.close()
    
    return {
        'total_users': stats[0],
        'last_updated': stats[1],
        'today_signups': today_signups,
        'candidates': candidates,
        'recruiters': recruiters,
        'both': both
    }

def get_analytics():
    """Récupère les statistiques analytiques pour le dashboard"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    # Total des utilisateurs inscrits
    cursor.execute('SELECT COUNT(*) FROM beta_users')
    total_users = cursor.fetchone()[0]
    
    # Utilisateurs avec email seulement
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE phone IS NULL OR phone = ""')
    email_only = cursor.fetchone()[0]
    
    # Utilisateurs avec téléphone seulement
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE email IS NULL OR email = ""')
    phone_only = cursor.fetchone()[0]
    
    # Utilisateurs avec email ET téléphone
    cursor.execute('SELECT COUNT(*) FROM beta_users WHERE email IS NOT NULL AND email != "" AND phone IS NOT NULL AND phone != ""')
    complete_profiles = cursor.fetchone()[0]
    
    # Inscriptions par source
    cursor.execute('SELECT source, COUNT(*) FROM beta_users GROUP BY source')
    sources = cursor.fetchall()
    
    # Dernières inscriptions (7 derniers jours)
    cursor.execute('''
        SELECT DATE(signup_date) as date, COUNT(*) as count 
        FROM beta_users 
        WHERE signup_date >= DATE('now', '-7 days') 
        GROUP BY DATE(signup_date) 
        ORDER BY date DESC
    ''')
    last_7_days = cursor.fetchall()
    
    conn.close()
    
    return {
        'total_users': total_users,
        'email_only': email_only,
        'phone_only': phone_only,
        'complete_profiles': complete_profiles,
        'conversion_rate': (complete_profiles / total_users * 100) if total_users > 0 else 0,
        'sources': dict(sources),
        'last_7_days': [{'date': row[0], 'count': row[1]} for row in last_7_days]
    }

def get_all_contacts():
    """Récupère tous les contacts avec leurs coordonnées"""
    conn = get_db_connection()
    cursor = conn.cursor()
    
    cursor.execute('''
        SELECT id, first_name, last_name, email, phone, source, signup_date, status
        FROM beta_users 
        ORDER BY signup_date DESC
    ''')
    
    users = cursor.fetchall()
    conn.close()
    
    return [{
        'id': user[0],
        'first_name': user[1],
        'last_name': user[2],
        'email': user[3],
        'phone': user[4],
        'source': user[5],
        'signup_date': user[6],
        'status': user[7],
        'profile_complete': bool(user[3] and user[4])  # Email ET téléphone
    } for user in users]
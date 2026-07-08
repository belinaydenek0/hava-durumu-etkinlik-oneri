import os
import requests
import mysql.connector
from dotenv import load_dotenv

load_dotenv()

def get_db_connection():
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password=os.getenv("DB_PASSWORD"),
        database="weather_assistant"
    )

def get_weather_suggestion(city):
    api_key = os.getenv("WEATHER_API_KEY")
    api_url = f"http://api.openweathermap.org/data/2.5/weather?q={city}&appid={api_key}&units=metric"
    
    try:
        response = requests.get(api_url).json()
        condition = response['weather'][0]['main']
        
        db = get_db_connection()
        cursor = db.cursor(dictionary=True)
        
        
        cursor.execute("SELECT prefers_new_activities FROM user_preferences ORDER BY id DESC LIMIT 1")
        pref = cursor.fetchone()
        
        # JOIN ile hava durumu ve aktiviteyi eşleştir
        query = """SELECT a.suggestion 
                   FROM activities a
                   JOIN weather_conditions wc ON a.weather_id = wc.id
                   WHERE wc.condition_name = %s"""
        
        if pref and not pref['prefers_new_activities']:
            query += " AND a.is_new_idea = 0"
            
        cursor.execute(query, (condition,))
        result = cursor.fetchone()
        
        db.close()
        return result['suggestion'] if result else "Bugün hava çok güzel, dışarı çıkıp biraz yürüyüş yapabilirsin!"
    except Exception as e:
        return "Şu an öneri alamıyorum ama hava harika!"

if __name__ == "__main__":
    import sys
    city = sys.argv[1] if len(sys.argv) > 1 else "Ankara"
    print(get_weather_suggestion(city))
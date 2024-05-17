import mysql.connector
import csv

# Se connecter à la base de données
db = mysql.connector.connect(
    host="localhost",
    user="tomh",
    password="HJ34!5r&*",
    database="project",
    port = 8457
)

# Créer un curseur pour exécuter des requêtes
cursor = db.cursor()

# Exécuter la requête pour récupérer du texte
query = "SELECT * FROM MOTSCLEF"
cursor.execute(query)
result = cursor.fetchall()

# Ouvrir un fichier CSV pour écrire les données
with open("Bonnes_Pratiques.csv", mode="w", newline="") as file:
    writer = csv.writer(file)
    
    # Écrire les en-têtes des colonnes (si nécessaire)
    column_headers = [i[0] for i in cursor.description]
    writer.writerow(column_headers)
    
    # Écrire les lignes de données
    for row in result:
        writer.writerow(row)

# Fermer la connexion à la base de données
cursor.close()
db.close()

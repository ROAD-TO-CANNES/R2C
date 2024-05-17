from fpdf import FPDF
import mysql.connector
from fpdf.enums import XPos, YPos

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

# Créer un fichier PDF
pdf = FPDF()
pdf.add_page()

# Définir une police
pdf.set_font("Helvetica", size=12)

# Ajouter le texte récupéré au fichier PDF
for row in result:
    # Assurer que toutes les valeurs sont des chaînes de caractères
    text = str(row[0])
    pdf.cell(0, 10, text, new_x=XPos.LMARGIN, new_y=YPos.NEXT)

# Enregistrer le fichier PDF
pdf.output("Bonnes_Pratiques.pdf")

# Fermer la connexion à la base de données
cursor.close()
db.close()

from fpdf import FPDF
import mysql.connector
from fpdf.enums import XPos, YPos
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/generate_pdf', methods=['POST'])
def generate_pdf():
    # Récupérer les données envoyées via POST et stocker dans une variable
    data = request.get_json()
    
    # Extraire les ids de la variable data
    ids = data['ids']
    

    # Convertir les ids en une chaîne de caractères pour la requête SQL
    ids_str = ','.join(map(str, ids))

    # Se connecter à la base de données
    db = mysql.connector.connect(
        host="localhost",
        user="tomh",
        password="HJ34!5r&*",
        database="project",
        port=8457
    )

    # Créer un curseur pour exécuter des requêtes
    cursor = db.cursor()

    # Exécuter la requête pour récupérer les données spécifiques
    query = f"SELECT * FROM MOTSCLEF WHERE id IN ({ids_str})"
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
    pdf_filename = "Bonnes_Pratiques.pdf"
    pdf.output(pdf_filename)

    # Fermer la connexion à la base de données
    cursor.close()
    db.close()

    return jsonify({"message": "PDF generated successfully", "filename": pdf_filename}), 200

if __name__ == '__main__':
    app.run(debug=True)

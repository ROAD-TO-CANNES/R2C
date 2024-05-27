import mariadb
import csv
import sys

def generate_csv(idbp_list):
    # Se connecter à la base de données
    db = mariadb.connect(
        host="localhost",
        user="tomh",
        password="HJ34!5r&*",
        database="project",
        port=8457
    )

    # Créer un curseur pour exécuter des requêtes
    cursor = db.cursor()

    # Créer un fichier CSV
    with open('/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.csv', 'w', newline='') as file:
        writer = csv.writer(file, delimiter=';')
        # Ajouter les en-têtes du tableau
        writer.writerow(["Nom", "Description"])

        for idbp in idbp_list:
            # Exécuter la requête pour récupérer du texte
            query = f"SELECT nombp, descbp, idbp FROM BONNESPRATIQUES WHERE idbp = {idbp};"
            cursor.execute(query)
            result = cursor.fetchall()

            # Ajouter les données récupérées au tableau
            for row in result:
                nombp = str(row[0])
                descbp = str(row[1])
                writer.writerow([nombp, descbp])

    # Fermer la connexion à la base de données
    cursor.close()
    db.close()

if __name__ == "__main__":
    # Convertir les arguments de la ligne de commande en entiers
    idbp_list = [int(idbp) for idbp in sys.argv[1:]]

    # Appeler la fonction avec la liste d'idbp
    generate_csv(idbp_list)
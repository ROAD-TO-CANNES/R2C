import csv
import mariadb
import argparse
import re
import datetime
import os


def generate_csv(idbp_list, prog_list, phase_list, keyword_list, user):
    # Connect to the database
    db = mariadb.connect(
        host="localhost",
        user="tomh",
        password="HJ34!5r&*",
        database="project",
        port=8457
    )

    # Create a cursor to execute queries
    cursor = db.cursor()

    # Get the directory of the current script
    script_dir = os.path.dirname(os.path.realpath(__file__))

    # Define the path to the CSV file
    csv_file_path = os.path.join(script_dir, 'Download', 'Bonnes_Pratiques.csv')
    
    # Open the CSV file
    with open(csv_file_path, 'w', newline='', encoding='utf-8-sig') as file:
        writer = csv.writer(file, delimiter = ';')

        # Write the headers
        writer.writerow(["Phase", "Programme", "Mots Clés"])

        # Retrieve all the keywords
        keywords = []
        for id_kw in keyword_list:
            cursor.execute(f"SELECT motclef FROM MOTSCLEF WHERE idmotclef = '{id_kw}';")
            keyword = cursor.fetchone()
            if keyword is not None:
                keywords.append(keyword[0])

        # Retrieve all the phases
        phases = []
        for id_phase in phase_list:
            cursor.execute(f"SELECT descript FROM PHASE WHERE idphase = '{id_phase}'; ")
            phase = cursor.fetchone()
            if phase is not None:
                phases.append(phase[0])

        # Retrieve all the programs
        progs = []
        for id_prog in prog_list:
            cursor.execute(f"SELECT nomprog FROM PROGRAMME WHERE idprog = '{id_prog}';")
            prog = cursor.fetchone()
            if prog is not None:
                progs.append(prog[0])

        # Write the data to the CSV
        writer.writerow([', '.join(phases), ', '.join(progs), ', '.join(keywords)])
        writer.writerow([])

        # Write the headers for the next section
        writer.writerow(["Nom", "Description", "Fait ?"])

        for idbp in idbp_list:
            # Execute the query to retrieve the text
            query = f"SELECT nombp, descbp, idbp FROM BONNESPRATIQUES WHERE idbp = '{idbp}';"
            cursor.execute(query)
            result = cursor.fetchall()

            # Add the retrieved data to the CSV
            for row in result:
                nombp = str(row[0])
                descbp = str(row[1])
                writer.writerow([nombp, descbp, "\u2610"])
        # Write the current date and user to the CSV
        writer.writerow([])
        writer.writerow([f"Fait le {datetime.datetime.now().strftime('%Y-%m-%d')} par {user}"])

    # Close the database connection
    cursor.close()
    db.close()

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("params", type=str, nargs='+')
    args = parser.parse_args()

    def parse_ids(arg, key):
        # Extract the numbers from the argument
        if key == 'user':
            return arg
        # Otherwise, extract the numbers from the argument
        else:
            return list(map(int, re.findall(r'\d+', arg)))
    params_dict = {}

    # Iterate over the parameters
    for param in args.params:
        # Split the parameter into key and values
        key, values = param.split('[')
        values = values[:-1]

        # Convert the values to a list of integers or a string, depending on the key
        values = parse_ids(values, key)

        # Add the key and values to the dictionary
        params_dict[key] = values
    # Extract the parameters from the dictionary
    idbp_list = params_dict.get('bp', [])
    phase_list = params_dict.get('ph', [])
    prog_list = params_dict.get('pr', [])
    keyword_list = params_dict.get('kw', [])
    user = params_dict.get('user', '')

    generate_csv(idbp_list, prog_list, phase_list, keyword_list, user)

import mariadb
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
import sys
# ...

def generate_pdf(idbp_list):
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
    # Create a SimpleDocTemplate for the pdf
    doc = SimpleDocTemplate("/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.pdf", pagesize=letter)

    # Prepare data for the table
    data = [["Nom", "Description"]]
    for idbp in idbp_list:
        # Execute the query to retrieve the text
        query = f"SELECT nombp, descbp, idbp FROM BONNESPRATIQUES WHERE idbp = {idbp};"
        cursor.execute(query)
        result = cursor.fetchall()

        # Add the retrieved data to the table
        for row in result:
            nombp = str(row[0])
            descbp = str(row[1])
            data.append([nombp, descbp])

    # Calculate the width of each column
    page_width = doc.pagesize[0] * 0.9
    colWidth_id = page_width / 9
    colWidth_desc = 8 * page_width / 9

    # Create a Table with the data and add it to the elements to be added to the pdf
    table = Table(data, colWidths=[colWidth_id, colWidth_desc])
    table.setStyle(TableStyle([('GRID', (0,0), (-1,-1), 1, colors.black)]))  # Add grid to table

    # Add the table to the elements to be added to the pdf
    elements = []
    elements.append(table)

    # Build the pdf
    doc.build(elements)

    # Close the database connection
    cursor.close()
    db.close()

if __name__ == "__main__":
    # Convert the command line arguments to integers
    idbp_list = [int(idbp) for idbp in sys.argv[1:]]

    # Call the function with the idbp list
    generate_pdf(idbp_list)
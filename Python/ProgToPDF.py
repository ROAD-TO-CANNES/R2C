import mariadb
from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
import sys

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

    # Create a PDF file
    c = canvas.Canvas('/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.pdf', pagesize=letter)

    # Set the font and font size for the PDF
    c.setFont("Helvetica", 12)

    # Add the table headers
    c.drawString(50, 750, "ID")
    c.drawString(150, 750, "Description")

    y = 720  # Initial y-coordinate for the table rows

    for idbp in idbp_list:
        # Execute the query to retrieve the text
        query = f"SELECT descbp, idbp FROM BONNESPRATIQUES WHERE idbp = {idbp};"
        cursor.execute(query)
        result = cursor.fetchall()

        # Add the retrieved data to the table
        for row in result:
            idbp = str(row[1])
            descbp = str(row[0])
            c.drawString(50, y, idbp)
            c.drawString(150, y, descbp)
            y -= 20

    # Save the PDF file
    c.save()

    # Close the database connection
    cursor.close()
    db.close()

if __name__ == "__main__":
    # Convert the command line arguments to integers
    idbp_list = [int(idbp) for idbp in sys.argv[1:]]

    # Call the function with the idbp list
    generate_pdf(idbp_list)
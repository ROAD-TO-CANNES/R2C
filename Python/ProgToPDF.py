
import mariadb
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.lib.pagesizes import letter
from reportlab.platypus import Spacer
from reportlab.lib import colors
from reportlab.lib.units import inch
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import Paragraph
import sys
# ...

def generate_pdf(idbp_list, mots_clefs_list, phase_list):
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
    pdfmetrics.registerFont(TTFont('DejaVuSans', 'DejaVuSans.ttf'))
    # Get the default style
    styles = getSampleStyleSheet()

    # Create the bold style
    bold_style = styles['Heading2']
    data1 = [[Paragraph("<b>Phase</b>", bold_style), Paragraph("<b>Mots Clés</b>", bold_style)]]
    for phase in phase_list:
        mots_clefs = ', '.join(mots_clefs_list)
        data1.append([phase, mots_clefs])

    # Prepare data for the table
    bold_style = styles['Heading2']
    data2 = [[Paragraph("<b>Nom</b>", bold_style), Paragraph("<b>Description</b>", bold_style)]]
    for idbp in idbp_list:
        # Execute the query to retrieve the text
        query = f"SELECT nombp, descbp, idbp FROM BONNESPRATIQUES WHERE idbp = {idbp};"
        cursor.execute(query)
        result = cursor.fetchall()

        # Add the retrieved data to the table
        for row in result:
            nombp = str(row[0])
            descbp = str(row[1])
            data2.append([nombp, descbp])

    # Calculate the width of each column
    page_width = doc.pagesize[0] * 0.9
    colWidth_id = page_width / 9
    colWidth_desc = 8 * page_width / 9

    table1 = Table(data1, colWidths=[colWidth_id, colWidth_desc])
    table1.setStyle(TableStyle([('GRID', (0,0), (-1,-1), 1, colors.black),
        ('FONT', (0, 0), (-1, -1), 'DejaVuSans')]))  # Add grid to table
    table1.setStyle(TableStyle([('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey)]))  # Add background color to header

    bold_style = styles['Heading3']
    data2.append([Paragraph("<b>Fait</b>", bold_style), "\u2610"])
    table2 = Table(data2, colWidths=[colWidth_id, colWidth_desc])
    table2.setStyle(TableStyle([('GRID', (0,0), (-1,-1), 1, colors.black),
        ('FONT', (0, 0), (-1, -1), 'DejaVuSans')]))  # Add grid to table
    table2.setStyle(TableStyle([('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey)]))
    # Add the table to the elements to be added to the pdf
    elements = []
    elements.append(table1)
    elements.append(Spacer(1, 0.2 * inch))  # Add some space between the tables
    elements.append(table2)

    # Build the pdf
    doc.build(elements)

    # Close the database connection
    cursor.close()
    db.close()

if __name__ == "__main__":
    # Convert the command line arguments to lists
    idbp_list = [int(idbp) for idbp in sys.argv[1].split() if idbp.isdigit()]
    mots_clefs_list = sys.argv[2].split()
    phase_list = sys.argv[3].split()

    # Call the function with the idbp list, mots_clefs list, and phase list
    generate_pdf(idbp_list, mots_clefs_list, phase_list)
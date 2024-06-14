
import mariadb
import argparse
from datetime import datetime
import re
from reportlab.lib.enums import TA_CENTER
from reportlab.lib.pagesizes import A3
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.platypus import Spacer
from reportlab.lib import colors
from reportlab.lib.units import inch
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.platypus import Paragraph
# Function to insert line breaks at appropriate places
def insert_line_breaks(text, max_length):
    words = text.split(' ')
    lines = []
    current_line = []
    current_length = 0

    for word in words:
        if current_length + len(word) > max_length and current_line:
            lines.append(' '.join(current_line))
            current_line = [word]
            current_length = len(word)
        else:
            current_line.append(word)
            current_length += len(word) + 1  # +1 for the space

    lines.append(' '.join(current_line))
    return '<br/>'.join(lines)

def generate_pdf(idbp_list, prog_list, phase_list, keyword_list, user):
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
    doc = SimpleDocTemplate(
        "/var/www/r2c.uca-project.com/Python/Download/Bonnes_Pratiques.pdf",
        pagesize=A3,  # Use A3 size instead of A4
        rightMargin=72,
        leftMargin=72,
        topMargin=72,
        bottomMargin=18
    )    

    pdfmetrics.registerFont(TTFont('DejaVuSans', 'DejaVuSans.ttf'))
    # Get the default style
    styles = getSampleStyleSheet()
    # Create the bold style
    bold_style = styles['Heading2']
    data1 = [[Paragraph("<b>Phase</b>", bold_style), Paragraph("<b>Programme</b>", bold_style), Paragraph("<b>Mots Clés</b>", bold_style)]]

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

    # Wrap each string in a Paragraph object
    phase_p = Paragraph(', '.join(phases) if phases else "", styles['BodyText'])
    prog_p = Paragraph(', '.join(progs) if progs else "", styles['BodyText'])
    keyword_p = Paragraph(', '.join(keywords) if keywords else "", styles['BodyText'])
     # Calculate the width of each column
    page_width = doc.pagesize[0] * 0.9
    # Check if all lists are empty
    if not phases and not progs and not keywords:
        # Add an empty row
        data1.append([Paragraph("", styles['BodyText']), Paragraph("", styles['BodyText']), Paragraph("", styles['BodyText'])])
    else:
        data1.append([phase_p, prog_p, keyword_p])  # Add data for the third column

    # Prepare data for the table
    bold_style = styles['Heading2']
    data2 = [[Paragraph("<b>Nom</b>", bold_style), Paragraph("<b>Description</b>", bold_style), Paragraph("<b><i>Fait ?</i></b>", bold_style)]]
    for idbp in idbp_list:
        # Execute the query to retrieve the text
        query = f"SELECT nombp, descbp, idbp FROM BONNESPRATIQUES WHERE idbp = '{idbp}';"
        cursor.execute(query)
        result = cursor.fetchall()

        # Add the retrieved data to the table
        for row in result:
            nombp = insert_line_breaks(str(row[0]), 105)
            descbp = insert_line_breaks(str(row[1]), 105)
            nombp_p = Paragraph(nombp, styles['BodyText'])
            descbp_p = Paragraph(descbp, styles['BodyText'])
            data2.append([nombp_p, descbp_p, "\u2610"])            
            
    colWidth2_1 = page_width * 0.25  # First column is smaller
    colWidth2_2 = page_width * 0.65   # Second column is larger
    colWidth2_3 = page_width * 0.1  # Third column is smaller

    colWidth2_12 = page_width * 0.375  
    colWidth2_13 = page_width * 0.375 
    # Adjust the font size
    styles = getSampleStyleSheet()
    styles['BodyText'].fontSize = 8
    styles['Heading3'].fontSize = 10

    # Adjust the padding for the tables
    table1 = Table(data1, colWidths=[colWidth2_1, colWidth2_12, colWidth2_13])
    table1.setStyle(TableStyle([
    ('INNERGRID', (0, 0), (-1, -1), 0.25, colors.black),
    ('FONT', (0, 0), (-1, -1), 'DejaVuSans'),
    ('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey),
    ('BOX', (0, 0), (-1, -1), 0.25, colors.black),
    ('VALIGN', (0, 0), (-1, -1), 'TOP'),
    ('LEFTPADDING', (0, 0), (-1, -1), 3),  # Adjust the left padding
    ('RIGHTPADDING', (0, 0), (-1, -1), 3),  # Adjust the right padding
    ('TOPPADDING', (0, 0), (-1, -1), 3),  # Adjust the top padding
    ('BOTTOMPADDING', (0, 0), (-1, -1), 3),  # Adjust the bottom padding
    ]))

    table2 = Table(data2, colWidths=[colWidth2_1, colWidth2_2, colWidth2_3])
    table2.setStyle(TableStyle([
    ('INNERGRID', (0, 0), (-1, -1), 0.25, colors.black),
    ('FONT', (0, 0), (-1, -1), 'DejaVuSans'),
    ('BACKGROUND', (0, 0), (-1, 0), colors.lightgrey),
    ('BOX', (0, 0), (-1, -1), 0.25, colors.black),
    ('VALIGN', (0, 0), (-1, -1), 'TOP'),
    ('LEFTPADDING', (0, 0), (-1, -1), 3),  # Adjust the left padding
    ('RIGHTPADDING', (0, 0), (-1, -1), 3),  # Adjust the right padding
    ('TOPPADDING', (0, 0), (-1, -1), 3),  # Adjust the top padding
    ('BOTTOMPADDING', (0, 0), (-1, -1), 3),  # Adjust the bottom padding
    ]))
    # Add the table to the elements to be added to the pdf
    elements = []
    elements.append(table1)
    elements.append(Spacer(1, 0.2 * inch))  # Add some space between the tables
    elements.append(table2)

    # Get the current date
    current_date = datetime.now().strftime("%d/%m/%Y à %H:%M:%S")

    # Create the comment
    styles.add(ParagraphStyle(name='Bigger', parent=styles['BodyText'],alignment=TA_CENTER, spaceAfter=12, fontSize=18))
    comment = f"<para><i>Ce document a été créé le {current_date}</i></para>"
    comment_p = Paragraph(comment, styles['Bigger'])

    # Add the comment to the elements to be added to the pdf
    elements.append(Spacer(1, 0.3 * inch))  # Add some space before the comment
    elements.append(comment_p)
    user_comment = f"<para><i>Par {user}</i></para>"
    user_comment_p = Paragraph(user_comment, styles['Bigger'])  
    # Add the user comment to the elements to be added to the pdf
    elements.append(user_comment_p)

    # Build the pdf
    doc.build(elements)

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

    generate_pdf(idbp_list, prog_list, phase_list, keyword_list, user)


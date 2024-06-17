import csv


# Chemin vers le fichier CSV
csv_file = '../../Data_Arbre.csv'



import mysql.connector

db = mysql.connector.connect(
    host="etu0106.projets.isen-ouest.fr",
    user="etu0106",
    password="lepooufn",
    database="etu0106"
)

# faire quelque chose d'utile avec la connexion

db.close()
# Création de la table dans la base de données
# cursor.execute('CREATE TABLE IF NOT EXISTS ma_table (colonne1 TEXT, colonne2 INTEGER, colonne3 REAL)')


# Lecture du fichier CSV et insertion des données dans la base de données
with open(csv_file, 'r') as file:
    csv_reader = csv.reader(file)
    next(csv_reader)  # Ignorer la première ligne si elle contient les en-têtes
    for row in csv_reader:
        selected_columns = (row[16], row[4], row[5], row[6], row[0], row[1], row[19], row[8], row[7], row[10], row[9])  # Select the desired columns from the row
        # cursor.execute('INSERT INTO ma_table VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', selected_columns)
        print(selected_columns)
    # for row in csv_reader:
    #     # cursor.execute('INSERT INTO ma_table VALUES (?, ?, ?)', row)
    #     print(row)

# Valider les modifications et fermer la connexion à la base de données
# conn.commit()
# conn.close()
import csv
import mysql.connector

# Chemin vers le fichier CSV
csv_file = '../Data_Arbre.csv'

# Connexion à la base de données
db = mysql.connector.connect(
    host="localhost",
    user="etu0106",
    password="lepooufn",
    database="etu0106"
)

cursor = db.cursor()

# Fonction pour vérifier si une valeur existe dans une table de référence
def check_foreign_key(table, column, value):
    cursor.execute(f"SELECT COUNT(*) FROM {table} WHERE {column} = %s", (value,))
    return cursor.fetchone()[0] > 0

# Ouvrir le fichier CSV et insérer les données dans la base de données
with open(csv_file, 'r') as file:
    csv_reader = csv.reader(file)
    next(csv_reader)  # Ignorer la première ligne si elle contient les en-têtes
    
    for row in csv_reader:
        selected_columns = (row[16], row[4], row[5], row[6], row[0], row[1], row[19], row[7], row[8], row[10], row[9])  # Sélectionner les colonnes souhaitées

        # Vérifier les clés étrangères
        if not check_foreign_key('fk_arb_etat', 'fk_arb_etat', row[7]):
            print(f"Valeur étrangère non trouvée pour fk_arb_etat: {row[7]}")
            continue
        if not check_foreign_key('fk_stadedev', 'fk_stadedev', row[8]):
            print(f"Valeur étrangère non trouvée pour fk_stadedev: {row[8]}")
            continue
        if not check_foreign_key('fk_pied', 'fk_pied', row[10]):
            print(f"Valeur étrangère non trouvée pour fk_pied: {row[10]}")
            continue
        if not check_foreign_key('fk_port', 'fk_port', row[9]):
            print(f"Valeur étrangère non trouvée pour fk_port: {row[9]}")
            continue

        try:
            query = 'INSERT INTO arbre (espece, haut_tot, haut_tronc, diam_tronc, lat, longi, remarquable, fk_arb_etat, fk_stadedev, fk_pied, fk_port) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)'
            cursor.execute(query, selected_columns)
            db.commit()
        except mysql.connector.Error as err:
            print(f"Erreur : {err}")
            db.rollback()

# Fermer la connexion à la base de données
cursor.close()
db.close()
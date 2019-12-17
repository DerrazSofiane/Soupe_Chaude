import csv
import mysql.connector
from scrapbs4 import scrap


def importtobdd():
    print('Récupération des données \n')
    scrap('carotte')
    scrap('courgette')
    scrap('ail')
    scrap('echalote')
    scrap('poireau')
    scrap('aubergine')
    scrap('citron jaune')
    scrap('pomme de terre')
    scrap('tomate grappe')

    print('\nConnexion à la base')
    mydb = mysql.connector.connect(host='localhost',
                                   user='root',
                                   passwd=''
                                   )
    print('Connecté à la base')

    mycursor = mydb.cursor()
    mycursor.execute("USE soupe")

    # Ouverture des fichiers pour les données à importer
    fichier_csv = open('Leader.csv')
    fichier_csv2 = open('Rungis.csv')

    csv_data = csv.reader(fichier_csv)
    csv_data2 = csv.reader(fichier_csv2)

    print('Importation des données dans la base\n')
    for row in csv_data:
        print('row', row)
        sql = 'INSERT INTO ingredient(nom_ingredient, prix_leader) VALUES("{}", "{}")'.format(
            row[0], row[1])
        print(sql)
        mycursor.execute(sql)

    for row in csv_data2:
        print('row', row)
        sql = "UPDATE ingredient SET prix_rungis = %s WHERE nom_ingredient = %s"
        val = (row[1], row[0])
        print(sql)
        mycursor.execute(sql, val)

    mydb.commit()
    mycursor.close()
    print("\nImportation terminée")


importtobdd()

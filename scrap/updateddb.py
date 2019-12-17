import csv
import mysql.connector
import os.path
from scrapbs4 import scrap
from script_metadonnees import metadonnees

file_exists = os.path.isfile('Leader.csv')
file_exists2 = os.path.isfile('Rungis.csv')

# données brutes insérées en mode "append", suppression des anciennes données pour éviter les doublons
# optimisation : mettre les données sales dans un dossier et delete le
# dossier au lieu de suppr un par un (avec shutil.rmtree)
filesale_exists = os.path.isfile('LeaderSale1.txt')
filesale_exists2 = os.path.isfile('RungisSale1.txt')
filesale_exists3 = os.path.isfile('LeaderSale2.txt')
filesale_exists4 = os.path.isfile('RungisSale2.txt')
filesale_exists5 = os.path.isfile('LeaderSale3.txt')
filesale_exists6 = os.path.isfile('RungisSale3.txt')

if filesale_exists and filesale_exists2 and filesale_exists3 and filesale_exists4 and filesale_exists5 and filesale_exists6:
    os.remove('LeaderSale1.txt')
    os.remove('RungisSale1.txt')
    os.remove('LeaderSale2.txt')
    os.remove('RungisSale2.txt')
    os.remove('LeaderSale3.txt')
    os.remove('RungisSale3.txt')


# si la récupération des données se fait avec les fichiers existants, les
# nouvelles données vont être ajoutées en doublon car ils sont insérées
# dans le csv avec l'option "append"
if file_exists and file_exists2:
    print('Suppression des anciens fichiés')
    os.remove('Leader.csv')
    os.remove('Rungis.csv')
    open('Leader.csv', 'w')
    open('Rungis.csv', 'w')
else:
    open('Leader.csv', 'w')
    open('Rungis.csv', 'w')

print('Récupération des nouvelles données \n')
scrap('carotte')
scrap('courgette')
scrap('ail')
scrap('echalote')
scrap('poireau')
scrap('aubergine')
scrap('citron jaune')
scrap('pomme de terre')
scrap('tomate grappe')

mydb = mysql.connector.connect(host='localhost',
                               user='root',
                               passwd=''
                               )

mycursor = mydb.cursor()
mycursor.execute("USE soupe")
print('\nConnecté à la base')

# Ouverture des nouveaux fichiers créés à partir du scrap
fichier_csv = open('Leader.csv')
fichier_csv2 = open('Rungis.csv')

csv_data = csv.reader(fichier_csv)
csv_data2 = csv.reader(fichier_csv2)

print('Mise à jour de la base de donnée\n')
for row in csv_data:
    print('row', row)
    sql = "UPDATE ingredient SET prix_leader = %s WHERE nom_ingredient = %s"
    val = (row[1], row[0])
    print(sql)
    mycursor.execute(sql, val)

for row in csv_data2:
    print('row', row)
    sql = "UPDATE ingredient SET prix_rungis = %s WHERE nom_ingredient = %s"
    val = (row[1], row[0])
    print(sql)
    mycursor.execute(sql, val)

mydb.commit()
mycursor.close()
print("\nMise à jour terminée")

metadonnees()

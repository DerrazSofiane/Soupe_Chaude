import datetime
import json
import os
import numpy

dossier_src = "C:/wamp64/www/soupe/scrap"
dossier_nettoyee = dossier_src.replace(
    "donnees_a_nettoyer", "donnees_nettoyees")
donnees_meta = "C:/wamp64/www/soupe/scrap/metadonne/meta.json"

current_time = datetime.datetime.now().strftime("%y-%m-%d_%H-%M")
liste_meta = []


def convert(o):
    if isinstance(o, numpy.int64):
        return int(o)
    raise TypeError


def recherche_fichiers(dossier):
    listeFichiers = os.listdir(dossier)
    return listeFichiers


def metadonnees():
    print('Enregistrement des métadonnées...')
    for fichier_nettoyee in fichiers_nettoyees:
        with open(donnees_meta, 'r') as f:
            try:
                data = json.load(f)
                for dt in data:
                    for key in dt.items():
                        if key == "name":
                            if dt[key] == fichier_nettoyee:
                                pass
                            else:
                                meta = {"name": fichier_nettoyee,
                                        "source": dossier_nettoyee,
                                        "date": current_time}
                                liste_meta.append(meta)
            except BaseException:
                meta = {"name": fichier_nettoyee,
                        "source": dossier_nettoyee,
                        "date": current_time}
                liste_meta.append(meta)

    with open(donnees_meta, 'a') as f:
        f.write(json.dumps(liste_meta, default=convert, indent=4))
    print('Enregistrement des métadonnées terminé')


fichiers_nettoyees = recherche_fichiers(dossier_nettoyee)

metadonnees()

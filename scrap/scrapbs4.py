from bs4 import BeautifulSoup
import requests
import pandas as pd

open('Leader.csv', 'w')
open('Rungis.csv', 'w')


def scrap(legume: str):
    ####
    # Scraping du site leader price
    ####
    url = "https://www.leaderdrive.fr/magasin/leader-price-drive-ifs/search/?search=" + legume + "+g"
    if legume == 'aubergine':
        url = "https://www.leaderdrive.fr/magasin/leader-price-drive-ifs/search/?search=" + legume + "+bio"

    response = requests.get(url)
    content = BeautifulSoup(response.content, 'lxml')

    # sauvegarde des données à l'état brute
    df = pd.DataFrame(data=content)
    df.to_csv(r'LeaderSale1.txt', mode='a', index=False)

    ###
    # Nettoyage
    ###
    # selection de la liste
    prix = content.find_all("div", attrs={"class": "product-price__number"})
    df = pd.DataFrame(data=prix)

    # sauvegarde du deuxième état brute
    df.to_csv(r'LeaderSale2.txt', mode='a', index=False)

    # traitement des prix de la liste
    # s'il y a plus d'un élément sur la liste
    if len(prix) > 1:
        # traitement du format du prix
        prix1 = prix[0]
        prixint = prix1.find(
            "span", attrs={
                "class": "product-price__integer"}).text
        prixfloat = prix1.find(
            "span", attrs={
                "class": "product-price__decimal"}).text
        prixfinal = float(prixint + '.' + prixfloat)

        prix2 = prix[1]
        prixint = prix2.find(
            "span", attrs={
                "class": "product-price__integer"}).text
        prixfloat = prix2.find(
            "span", attrs={
                "class": "product-price__decimal"}).text
        prixfinal2 = float(prixint + '.' + prixfloat)

        # sauvegarde du troisième état brute
        df = pd.DataFrame(data=[prix1, prix2])
        df.to_csv(r'LeaderSale3.txt', mode='a', index=False)

        ###
        # Suppression des doublons
        ###
        if prixfinal < prixfinal2:
            d = {'nom_ingredient': legume, 'prix_leader': [prixfinal]}
            df = pd.DataFrame(data=d)

            # sauvegarde des données propres dans un csv
            df.to_csv('Leader.csv', mode='a', header=False, index=False)
            print(legume + 'Leader' + ' done')
        else:
            d = {'nom_ingredient': legume, 'prix_leader': [prixfinal2]}
            df = pd.DataFrame(data=d)
            df.to_csv('Leader.csv', mode='a', header=False, index=False)
            print(legume + 'Leader' + ' done')

    # si il n'y a qu'un élément sur la liste
    else:
        prix1 = prix[0]
        prixint = prix1.find(
            "span", attrs={
                "class": "product-price__integer"}).text
        prixfloat = prix1.find(
            "span", attrs={
                "class": "product-price__decimal"}).text
        prixfinal = float(prixint + '.' + prixfloat)
        # sauvegarde du troisième état brute
        df = pd.DataFrame(data=[prix1])
        df.to_csv(r'LeaderSale3.txt', mode='a', index=False)

        d = {'nom_ingredient': legume, 'prix_leader': [prixfinal]}
        df = pd.DataFrame(data=d)
        # sauvegarde des données propres dans un csv
        df.to_csv('Leader.csv', mode='a', header=False, index=False)
        print(legume + 'Leader' + ' done')

    ####
    # Scraping du site rungis
    ####
    url = "http://www.rungischezvous.com/recherche?controller=search&orderby=position&orderway=desc&search_query=" + \
        legume + "&submit_search="

    response = requests.get(url)
    content = BeautifulSoup(response.content, 'lxml')

    # sauvegarde des données à l'état brute
    df = pd.DataFrame(data=content)
    df.to_csv('RungisSale1.txt', mode='a', header=False, index=False)

    ###
    # Nettoyage
    ###
    # selection de la liste
    prix = content.find_all("div", attrs={"class": "content_price"})
    df = pd.DataFrame(data=prix)

    # sauvegarde du deuxième état brute
    df.to_csv('RungisSale2.txt', mode='a', header=False, index=False)

    if len(prix) > 1:
        # traitement du format du prix
        prix1 = prix[0]
        prixfinal = prix1.find(
            "span", attrs={
                "class": "price-list price product-price"}).text
        prixfinal = prixfinal.replace(',', '.')
        prixfinal = float(prixfinal.replace(' €', ''))

        prix2 = prix[1]
        prixfinal2 = prix2.find(
            "span", attrs={
                "class": "price-list price product-price"}).text
        prixfinal2 = prixfinal2.replace(',', '.')
        prixfinal2 = float(prixfinal2.replace(' €', ''))

        # sauvegarde du troisième état brute
        df = pd.DataFrame(data=[prix1, prix2])
        df.to_csv('RungisSale3.txt', mode='a', header=False, index=False)

        if prixfinal < prixfinal2:
            d = {'nom_ingredient': legume, 'prix_rungis': [prixfinal]}
            df = pd.DataFrame(data=d)

            # sauvegarde des données propres dans un csv
            df.to_csv('Rungis.csv', mode='a', header=False, index=False)
            print(legume + 'Rungis' + ' done')
        else:
            d = {'nom_ingredient': legume, 'prix_rungis': [prixfinal2]}
            df = pd.DataFrame(data=d)

            # sauvegarde des données propres dans un csv
            df.to_csv('Rungis.csv', mode='a', header=False, index=False)
            print(legume + 'Rungis' + ' done')
    else:
        prix1 = prix[0]
        prixfinal = prix1.find(
            "span", attrs={
                "class": "price-list price product-price"}).text
        prixfinal = prixfinal.replace(',', '.')
        prixfinal = float(prixfinal.replace(' €', ''))

        # sauvegarde du troisième état brute
        df = pd.DataFrame(data=prix1)
        df.to_csv('RungisSale3.txt', mode='a', header=False, index=False)

        # sauvegarde des données propres dans un csv
        d = {'nom_ingredient': legume, 'prix_rungis': [prixfinal]}
        df = pd.DataFrame(data=d)
        df.to_csv('Rungis.csv', mode='a', header=False, index=False)
        print(legume + 'Rungis' + ' done')

import pandas as pd
import plotly.express as px
import plotly.graph_objects as go
import pandas as pd
import sklearn as sk
import plotly as plt
from sklearn.cluster import KMeans
import sys

def mapcluster(nb_clusters):
    data = pd.read_csv("/var/www/etu0106/projet_web/assets/csv/Data_Arbre.csv")
    data_taille = pd.read_csv("/var/www/etu0106/projet_web/assets/csv/data_taille.csv")
    
    # initalisation du modèle
    kmeans = KMeans(n_clusters=nb_clusters)

    # rentre les données dans le modèle
    kmeans.fit(data_taille)

    # recuperation des labels des clusters
    kmeans_labels = kmeans.labels_

    # ajout des colonnes cluster et coordonnées
    data_taille['cluster'] = kmeans_labels
    data_taille['Longitude'], data_taille['Latitude'] = data['longitude'], data['latitude']
    data_taille['hauteur_tot'] = data['haut_tot'].astype(str) + ' m'
    
    # affichage de la carte
    fig = px.scatter_mapbox(
        data_taille,
        lat='Latitude',
        lon='Longitude',
        color='cluster',
        mapbox_style='open-street-map',
        title='Clustering des arbres à Saint-Quentin',
        hover_data={'hauteur_tot': True},
        zoom=12
    )
    fig.write_html('../map.html')
    # fig.show()

if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python script.py <nb_clusters>")
        sys.exit(1)
    
    try:
        nb_clusters = int(sys.argv[1])
    except ValueError:
        print("Le nombre de clusters doit être un entier.")
        sys.exit(1)
        
mapcluster(nb_clusters)
import pandas as pd
import plotly.express as px
import plotly.graph_objects as go
import pandas as pd
import sklearn as sk
import plotly as plt
from sklearn.cluster import KMeans

def mapcluster(nb_clusters):
    data = pd.read_csv("assets/csv/Data_Arbre.csv")
    data_taille = pd.read_csv("assets/csv/data_taille.csv")
    
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
    fig.write_html('map.html')

nb_clusters = int(input("Entrez le nombre de clusters : "))
mapcluster(nb_clusters)
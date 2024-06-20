import pandas as pd
import json
from io import StringIO
import joblib
import sys
# selection des colonnes necessaires
# data = file[['fk_arb_etat', 'latitude', 'tronc_diam', 'haut_tronc', 'haut_tot']].copy()

def predict_risque(input_json):
    
    input_data = pd.read_json(StringIO(input_json))
    
    input_data.rename(columns={'lat': 'latitude'}, inplace=True)
    input_data.rename(columns={'diam_tronc': 'tronc_diam'}, inplace=True)
    
    
    # charger l'encodeur
    encoder = joblib.load('pkl/ordinal_encoder_besoin3_web.pkl')
    
    # encoder les données
    data_to_encode = ['fk_arb_etat']
    input_data.loc[:, data_to_encode] = encoder.fit_transform(input_data[data_to_encode])
    
    
    # creer les nouvelles données a predire
    new_X = input_data.drop(columns=['fk_arb_etat'])


    # effectuer les predictions
    reg_model = joblib.load('pkl/regression_model_besoin3_web.pkl')
    prediction = reg_model.predict(new_X)
    prediction = 100*prediction.round(3)
    
    return prediction

# Exemple d'utilisation
if __name__ == "__main__":
    # Récupérer input_json depuis la ligne de commande
    if len(sys.argv) != 2:
        print("Usage: python script.py '[{\"fk_arb_etat\": \"EN PLACE\", \"latitude\": \"48.8566\", \"tronc_diam\": \"20.0\", \"haut_tronc\": \"27.0\", \"haut_tot\": \"34.0\"}]'")
        sys.exit(1)
    
    input_json = sys.argv[1]

# input_json = '[{"fk_arb_etat": "EN PLACE", "latitude": "48.8566", "tronc_diam": "20.0", "haut_tronc": "27.0", "haut_tot": "34.0"}]'
    
output = predict_risque(input_json)
print(output)   

    
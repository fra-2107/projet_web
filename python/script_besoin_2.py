import pandas as pd
import joblib
import json
from sklearn.preprocessing import OrdinalEncoder, MinMaxScaler
from io import StringIO
import sys

def predict_age_from_json(input_json):
    # Charger les encodeurs et le modèle
    encoders = {}
    for col in ['remarquable', 'fk_port', 'fk_pied']:
        encoders[col] = joblib.load(f'/var/www/etu0116/projet_web/python/pkl/{col}_encoder.pkl')
        
    scaler_X = joblib.load('/var/www/etu0116/projet_web/python/pkl/x_scaler.pkl')
    scaler_y = joblib.load('/var/www/etu0116/projet_web/python/pkl/y_scaler.pkl')
    model = joblib.load('/var/www/etu0116/projet_web/python/pkl/regressor_model_besoin2.pkl')
    
    # Charger les données d'entrée depuis le JSON
    input_data = pd.read_json(StringIO(input_json))
    
    # Vérifier et convertir les colonnes catégorielles
    for col in ['remarquable', 'fk_port', 'fk_pied']:
        if col in input_data.columns:
            input_data[col] = input_data[col].astype(str)
        else:
            input_data[col] = "unknown"  # ou une autre valeur par défaut appropriée
            
    # Encodage des colonnes catégorielles
    for col in ['remarquable', 'fk_port', 'fk_pied']:
        if col in input_data.columns:
            unique_values = encoders[col].categories_[0]
            input_data[col] = input_data[col].apply(lambda x: x if x in unique_values else unique_values[0])
            encoded_column = encoders[col].transform(input_data[[col]])
            input_data[col] = encoded_column.astype(int)
    
    # Remplir les colonnes manquantes avec une valeur par défaut
    if 'fk_prec_estim' not in input_data.columns:
        input_data['fk_prec_estim'] = 0  # Remplacez 0 par une valeur appropriée si nécessaire

    # Colonnes requises par le modèle
    required_columns = ['haut_tot', 'haut_tronc', 'tronc_diam', 'clc_nbr_diag', 'remarquable', 'fk_prec_estim', 'fk_port', 'fk_pied']
    
    # Ajouter les colonnes manquantes avec une valeur par défaut
    for col in required_columns:
        if col not in input_data.columns:
            input_data[col] = 0  # ou une autre valeur par défaut appropriée
    
    # Retirer les colonnes supplémentaires non utilisées
    input_data = input_data[required_columns]
    
    # Normaliser les données
    X_scaled = scaler_X.transform(input_data)
    
    # Faire des prédictions
    y_pred_scaled = model.predict(X_scaled)
    
    # Inverser la normalisation des prédictions
    y_pred = scaler_y.inverse_transform(y_pred_scaled.reshape(-1, 1)).flatten()
    
    # Ajouter les prédictions à la DataFrame originale
    input_data['age_estim'] = y_pred
    
    # Convertir les résultats au format JSON
    result_json = input_data[['haut_tot', 'haut_tronc', 'tronc_diam', "fk_prec_estim", 'age_estim']].to_json(orient='records')
    return result_json

# Exemple d'utilisation
if __name__ == "__main__":
    # Récupérer input_json depuis la ligne de commande
    if len(sys.argv) != 2:
        print("Usage: python script.py '[{\"haut_tot\": 34.0, \"haut_tronc\": 27.0, \"tronc_diam\": 20.0, \"fk_prec_estim\": 10}]'")
        sys.exit(1)
    
    input_json = sys.argv[1]
    
output_json = predict_age_from_json(input_json)
print(output_json)
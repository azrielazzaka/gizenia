import pandas as pd
import tensorflow as tf
import pickle
import os
from sklearn.neighbors import NearestNeighbors
from pymongo import MongoClient

# Variabel Global
df = None
food_classifier_model = None
kmeans_model = None
scaler = None
db_connection = None
class_names = ['bakso', 'bebek_betutu', 'gado_gado', 'gudeg', 'nasi_goreng', 'pempek', 'rawon', 'rendang', 'sate', 'soto']

def init_engine():
    global df, food_classifier_model, kmeans_model, scaler, db_connection
    print("⏳ Menghubungkan ke MongoDB Atlas & Memuat Model AI...")
    
    try:
        # 1. KONEKSI DATABASE
        uri = "mongodb+srv://admin:admin123@nutrisiclusster.cwyqt00.mongodb.net/nutrition_app?retryWrites=true&w=majority&appName=nutrisiclusster"
        client = MongoClient(uri) 
        db_connection = client["nutrisi_ai_db"] 
        collection = db_connection["food_menus"] 

        # 2. MUAT K-MEANS & SCALER
        # Ambil path direktori file ai_model.py saat ini
        base_dir = os.path.dirname(os.path.abspath(__file__))
        
        # Load Scaler
        scaler_path = os.path.join(base_dir, 'scaler.pkl')
        with open(scaler_path, 'rb') as f:
            scaler = pickle.load(f)
            
        # Load K-Means Model
        kmeans_path = os.path.join(base_dir, 'kmeans_model.pkl')
        with open(kmeans_path, 'rb') as f:
            kmeans_model = pickle.load(f)
            
        print("✅ Model K-Means & Scaler Berhasil Dimuat!")

        # 3. INISIALISASI TENSORFLOW MODEL (JURUS PAMUNGKAS)
        try:
            from tensorflow.keras.layers import Dense

            # Kita buat kelas Dense palsu yang tugasnya cuma membuang 'quantization_config'
            class SafeDense(Dense):
                def __init__(self, **kwargs):
                    kwargs.pop('quantization_config', None) # Buang penyebab error
                    super().__init__(**kwargs)

            # Muat model dengan mengganti Dense bawaan ke SafeDense buatan kita
            food_classifier_model = tf.keras.models.load_model(
                'food_model.h5', 
                compile=False,
                custom_objects={'Dense': SafeDense}
            )
            print("Model TensorFlow Berhasil Dimuat! ✅ ")
            
        except Exception as tf_error:
            print(f"❌ Masih Error pada Model AI: {tf_error}")

    except Exception as e:
        # Ini adalah penutup untuk try paling atas (Koneksi DB dll)
        print(f"❌ Error Fatal pada Engine: {e}")
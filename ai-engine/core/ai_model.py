import pandas as pd
import tensorflow as tf
from sklearn.neighbors import NearestNeighbors
from pymongo import MongoClient

# Variabel Global
df = None
knn_model = None
food_classifier_model = None
db_connection = None
class_names = ['bakso', 'bebek_betutu', 'gado_gado', 'gudeg', 'nasi_goreng', 'pempek', 'rawon', 'rendang', 'sate', 'soto']

def init_engine():
    global df, knn_model, food_classifier_model, db_connection
    print("⏳ Menghubungkan ke MongoDB Atlas & Memuat Model AI...")
    
    try:
        # 1. KONEKSI DATABASE
        uri = "mongodb+srv://admin:admin123@nutrisiclusster.cwyqt00.mongodb.net/nutrition_app?retryWrites=true&w=majority&appName=nutrisiclusster"
        client = MongoClient(uri) 
        db_connection = client["nutrisi_ai_db"] 
        collection = db_connection["food_menus"] 

        # 2. INISIALISASI KNN MODEL
        cursor = collection.find({})
        menus_data = list(cursor)

        if len(menus_data) > 0:
            df = pd.DataFrame(menus_data)
            df['_id'] = df['_id'].astype(str)
            for col in ['calories', 'protein', 'fat', 'carbohydrates']:
                df[col] = pd.to_numeric(df.get(col, 0), errors='coerce').fillna(0)

            features = df[['calories', 'protein', 'fat', 'carbohydrates']]
            knn_model = NearestNeighbors(n_neighbors=6, algorithm='auto')
            knn_model.fit(features)
            print(f"✅ Model KNN Siap.")
        else:
            print("⚠️ Database Kosong.")

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
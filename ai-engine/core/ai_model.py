import pandas as pd
import tensorflow as tf
from sklearn.neighbors import NearestNeighbors
from pymongo import MongoClient

# Variabel Global untuk menyimpan data agar bisa diakses file lain
df = None
knn_model = None

# --- TAMBAHAN UNTUK CAMERA AI ---
food_classifier_model = None
db_connection = None
class_names = ['bakso', 'bebek_betutu', 'gado_gado', 'gudeg', 'nasi_goreng', 'pempek', 'rawon', 'rendang', 'sate', 'soto']

def init_engine():
    global df, knn_model, food_classifier_model, db_connection
    print("⏳ Menghubungkan ke MongoDB Atlas (Cloud) & Memuat Semua Model AI...")
    try:
        # 1. KONEKSI DATABASE
        uri = "mongodb+srv://admin:admin123@nutrisiclusster.cwyqt00.mongodb.net/nutrition_app?retryWrites=true&w=majority&appName=nutrisiclusster"
        client = MongoClient(uri) 
        db_connection = client["nutrisi_ai_db"] 
        collection = db_connection["food_menus"] 

        # 2. INISIALISASI KNN MODEL (Untuk Rekomendasi)
        cursor = collection.find({})
        menus_data = list(cursor)

        if len(menus_data) > 0:
            df = pd.DataFrame(menus_data)
            df['_id'] = df['_id'].astype(str)

            df['calories'] = pd.to_numeric(df.get('calories', 0), errors='coerce').fillna(0)
            df['protein'] = pd.to_numeric(df.get('protein', 0), errors='coerce').fillna(0)
            df['fat'] = pd.to_numeric(df.get('fat', 0), errors='coerce').fillna(0)
            df['carbohydrates'] = pd.to_numeric(df.get('carbohydrates', 0), errors='coerce').fillna(0)
            df['serving_size_g'] = pd.to_numeric(df.get('serving_size_g', 100), errors='coerce').fillna(100)

            features = df[['calories', 'protein', 'fat', 'carbohydrates']]
            knn_model = NearestNeighbors(n_neighbors=6, algorithm='auto')
            knn_model.fit(features)
            
            print(f"✅ Model KNN Siap dengan {len(df)} menu.")
        else:
            print("⚠️ Peringatan: Database MongoDB kosong! Tambahkan data via Laravel terlebih dahulu.")

        # 3. INISIALISASI TENSORFLOW MODEL (Untuk Deteksi Gambar)
        food_classifier_model = tf.keras.models.load_model('food_model.h5')
        print("✅ Model TensorFlow (Deteksi Makanan) Berhasil Dimuat!")

    except Exception as e:
        print(f"❌ Error MongoDB / AI: {e}")
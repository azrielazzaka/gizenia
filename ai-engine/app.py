from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
from sklearn.neighbors import NearestNeighbors
from pymongo import MongoClient

app = Flask(__name__)
CORS(app)

print("⏳ Menghubungkan ke MongoDB Atlas (Cloud) & Memuat Dataset...")
try:
    # 1. KONEKSI KE MONGODB ATLAS
    # Menggunakan URI aslimu
    uri = "mongodb+srv://admin:admin123@nutrisiclusster.cwyqt00.mongodb.net/nutrition_app?retryWrites=true&w=majority&appName=nutrisiclusster"
    client = MongoClient(uri) 
    
    # 2. PILIH DATABASE & COLLECTION SESUAI PERMINTAANMU
    db = client["nutrisi_ai_db"] 
    collection = db["food_menus"] 

    # 3. AMBIL SEMUA DATA DARI DATABASE
    cursor = collection.find({})
    menus_data = list(cursor)

    if len(menus_data) > 0:
        # UBAH DATA MONGODB MENJADI PANDAS DATAFRAME
        df = pd.DataFrame(menus_data)
        
        # Ubah ObjectId bawaan MongoDB menjadi string agar tidak error
        df['_id'] = df['_id'].astype(str)

        # Pastikan kolom gizi terbaca sebagai angka
        df['calories'] = pd.to_numeric(df.get('calories', 0), errors='coerce').fillna(0)
        df['protein'] = pd.to_numeric(df.get('protein', 0), errors='coerce').fillna(0)
        df['fat'] = pd.to_numeric(df.get('fat', 0), errors='coerce').fillna(0)
        df['carbohydrates'] = pd.to_numeric(df.get('carbohydrates', 0), errors='coerce').fillna(0)
        df['serving_size_g'] = pd.to_numeric(df.get('serving_size_g', 100), errors='coerce').fillna(100)

        # 4. LATIH MODEL MACHINE LEARNING (K-Nearest Neighbors)
        features = df[['calories', 'protein', 'fat', 'carbohydrates']]
        knn_model = NearestNeighbors(n_neighbors=6, algorithm='auto')
        knn_model.fit(features)
        
        print(f"✅ MongoDB Atlas Terhubung! Model AI Siap dengan {len(df)} menu.")
    else:
        print("⚠️ Peringatan: Database MongoDB kosong! AI tidak punya data untuk dipelajari. Tambahkan data via Laravel terlebih dahulu.")

except Exception as e:
    print(f"❌ Error MongoDB / AI: {e}")


@app.route('/', methods=['GET'])
def home():
    return jsonify({"status": "online", "message": "GIZENIA AI Engine Active (MongoDB Connected)!"}), 200

# ENDPOINT REKOMENDASI AI
@app.route('/api/predict/recommendation', methods=['POST'])
def get_recommendation():
    try:
        data = request.get_json() or {}

        # 🔁 SUPPORT DUA FORMAT (AMAN)
        if 'current' in data:
            target_cal = data['current'].get('calories', 0)
            target_pro = data['current'].get('protein', 0)
            target_fat = data['current'].get('fat', 0)
            target_carbs = data['current'].get('carbs', 0)
        else:
            target_cal = data.get('target_calories', 0)
            target_pro = data.get('target_protein', 0)
            target_fat = data.get('target_fat', 0)
            target_carbs = data.get('target_carbs', 0)

        user_target = pd.DataFrame([[
            target_cal, target_pro, target_fat, target_carbs
        ]], columns=['calories', 'protein', 'fat', 'carbohydrates'])

        # ❗ Jika model belum siap
        if 'knn_model' not in globals():
            return jsonify({
                "status": "success",
                "target_diminta": user_target.iloc[0].to_dict(),
                "rekomendasi": []
            }), 200

        distances, indices = knn_model.kneighbors(user_target, n_neighbors=6)

        recommended_menus = []
        for i, idx in enumerate(indices[0]):
            menu = df.iloc[idx]
            match_score = round(max(0, 100 - (distances[0][i] / 5)), 1)

            recommended_menus.append({
                "id": menu['_id'],
                "name": menu.get('name', 'Menu Tanpa Nama'),
                "category": menu.get('category', 'Umum'),
                "calories": float(menu['calories']),
                "protein": float(menu['protein']),
                "fat": float(menu['fat']),
                "carbohydrates": float(menu['carbohydrates']),
                "image": menu.get('image', ''),
                "serving_size_g": float(menu['serving_size_g']),
                "match_score": match_score
            })

        return jsonify({
            "status": "success",
            "target_diminta": user_target.iloc[0].to_dict(),
            "rekomendasi": recommended_menus
        }), 200

    except Exception as e:
        return jsonify({
            "status": "error",
            "message": str(e)
        }), 500
    
# ==========================================
# ENDPOINT EVALUATOR "ISI PIRINGKU"
# ==========================================
@app.route('/api/predict/evaluation', methods=['POST'])
def evaluate_meal():
    try:
        data = request.get_json()
        if not data or 'foods' not in data: 
            return jsonify({"error": "Data makanan tidak ditemukan"}), 400
            
        foods = data['foods']
        
        # 1. Hitung Total Nutrisi Piring Ini
        total_cal = sum(float(f.get('calories', 0)) for f in foods)
        total_pro = sum(float(f.get('protein', 0)) for f in foods)
        total_fat = sum(float(f.get('fat', 0)) for f in foods)
        total_carbs = sum(float(f.get('carbohydrates', 0)) for f in foods)

        # 2. Hitung Persentase Makronutrisi (Kalori dari masing-masing makro)
        # 1g Karbo = 4 kkal | 1g Protein = 4 kkal | 1g Lemak = 9 kkal
        cal_from_carbs = total_carbs * 4
        cal_from_pro = total_pro * 4
        cal_from_fat = total_fat * 9
        
        total_macro_cal = cal_from_carbs + cal_from_pro + cal_from_fat
        
        # Hindari pembagian dengan nol
        if total_macro_cal == 0:
            return jsonify({"status": "error", "message": "Piring kosong atau nutrisi 0"}), 400

        pct_carbs = (cal_from_carbs / total_macro_cal) * 100
        pct_pro = (cal_from_pro / total_macro_cal) * 100
        pct_fat = (cal_from_fat / total_macro_cal) * 100

        # 3. RULE-BASED CLASSIFICATION (Standar Gizi Ideal)
        # Idealnya: Karbo (45-65%), Protein (10-35%), Lemak (20-35%)
        verdict = "Seimbang"
        warnings = []

        if pct_carbs > 65:
            verdict = "Tinggi Karbohidrat"
            warnings.append("Porsi karbohidratmu melebihi batas ideal. Kurangi sedikit nasi/mie dan perbanyak lauk pauk.")
        elif pct_carbs < 40:
            warnings.append("Karbohidrat agak rendah. Pastikan kamu punya cukup energi untuk aktivitas hari ini.")

        if pct_pro < 15:
            if verdict == "Seimbang": verdict = "Kurang Protein"
            warnings.append("Proteinmu sangat kurang. Coba tambahkan telur, tempe, tahu, atau daging.")
        elif pct_pro > 40:
            verdict = "Tinggi Protein"
            warnings.append("Sangat bagus untuk pembentukan otot, tapi pastikan kamu juga banyak minum air putih.")

        if pct_fat > 35:
            if verdict == "Seimbang": verdict = "Tinggi Lemak"
            warnings.append("Kandungan lemak terlalu tinggi. Kurangi makanan yang digoreng atau bersantan.")

        if not warnings:
            warnings.append("Komposisi piringmu sangat luar biasa dan seimbang! Pertahankan!")

        # 4. Susun Jawaban AI
        return jsonify({
            "status": "success",
            "summary": {
                "total_calories": round(total_cal),
                "total_protein": round(total_pro, 1),
                "total_fat": round(total_fat, 1),
                "total_carbs": round(total_carbs, 1)
            },
            "percentages": {
                "carbs": round(pct_carbs, 1),
                "protein": round(pct_pro, 1),
                "fat": round(pct_fat, 1)
            },
            "evaluation": {
                "verdict": verdict,
                "messages": warnings
            }
        }), 200

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
from flask import Blueprint, request, jsonify
import numpy as np
import os
from datetime import datetime
from PIL import Image

camera_ai_bp = Blueprint('camera_ai', __name__)

@camera_ai_bp.route('/predict', methods=['POST'])
def predict():
    # Import mesin AI (hanya saat tombol dipencet)
    from core.ai_model import food_classifier_model, class_names, db_connection

    if 'image' not in request.files:
        return jsonify({'error': 'No image uploaded'}), 400

    file = request.files['image']
    img_path = "temp_prediction.jpg"
    file.save(img_path)

    try:
        # Preprocessing Gambar menggunakan PIL
        img = Image.open(img_path).convert('RGB')
        
        # ✅ UKURAN HARUS 224x224 (Sesuai permintaan model Anda)
        img = img.resize((224, 224)) 
        
        img_array = np.array(img)
        img_array = np.expand_dims(img_array, axis=0) / 255.0

        # Bebaskan file dari kuncian Windows
        img.close()

        # ✅ LANGSUNG SUAPKAN GAMBAR KE MODEL (Tidak perlu VGG16 lagi!)
        preds = food_classifier_model.predict(img_array)
        
        # Ekstrak hasil dengan aman
        hasil_prediksi = np.squeeze(preds)
        predicted_index = int(np.argmax(hasil_prediksi))
        predicted_class = str(class_names[predicted_index])
        confidence = float(hasil_prediksi[predicted_index])

        result = {
            "makanan_terdeteksi": predicted_class.upper(),
            "confidence": confidence,
            "nutrisi": None
        }

        # Cari nutrisi di MongoDB
        if db_connection is not None:
            query = predicted_class.replace('_', ' ').lower().strip()
            
            food_data = db_connection['food_menus'].find_one(
                {"name": {"$regex": query, "$options": "i"}}
            )

            if food_data:
                result["nutrisi"] = {
                    "nama_dataset": food_data.get('name', ''),
                    "kalori": food_data.get('calories', 0),
                    "protein": food_data.get('protein', 0), 
                    "lemak": food_data.get('fat', 0),
                    "karbohidrat": food_data.get('carbohydrates', 0)
                }

        # Hapus file gambar sementara
        if os.path.exists(img_path):
            os.remove(img_path)

        return jsonify(result), 200

    except Exception as e:
        import traceback
        traceback.print_exc()
        if os.path.exists(img_path):
            os.remove(img_path)
        return jsonify({'error': str(e)}), 500
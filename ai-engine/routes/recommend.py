from flask import Blueprint, request, jsonify
import core.ai_model as ai
import pandas as pd

recommend_bp = Blueprint('recommend', __name__)

@recommend_bp.route('/api/predict/recommendation', methods=['POST'])
def get_recommendation():
    try:
        data = request.get_json()
        if not data: 
            return jsonify({"error": "Data kosong"}), 400
            
        weight = float(data.get('weight', 60))
        height = float(data.get('height', 165))
        age = int(data.get('age', 20))
        gender = data.get('gender', 'male')
        activity = data.get('activity', 'moderate')

        if gender == 'male':
            bmr = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age)
        else:
            bmr = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age)

        activity_multipliers = {
            'sedentary': 1.2, 'lightly': 1.375, 'moderate': 1.55, 
            'active': 1.725, 'very_active': 1.9
        }
        
        # PERBAIKAN: Dibagi 3 untuk mendapatkan target per SEKALI MAKAN
        target_calories_daily = bmr * activity_multipliers.get(activity, 1.2)
        target_calories_meal = target_calories_daily / 3 

        # Makronutrisi dihitung dari target 1x makan
        target_protein = (target_calories_meal * 0.15) / 4
        target_fat = (target_calories_meal * 0.30) / 9
        target_carbs = (target_calories_meal * 0.55) / 4

        # Sesuaikan dengan nama kolom saat K-Means di-training
        user_features = pd.DataFrame([[
            target_calories_meal, target_protein, target_fat, target_carbs
        ]], columns=['calories', 'proteins', 'fat', 'carbohydrate'])
        
        scaled_features = ai.scaler.transform(user_features)
        cluster_id = int(ai.kmeans_model.predict(scaled_features)[0])

        cluster_names = {
            0: 'Rendah Kalori / Diet',
            1: 'Tinggi Protein (Pertumbuhan)',
            2: 'Tinggi Karbohidrat (Padat Energi)',
            3: 'Keseimbangan Makro (Standard)'
        }
        cluster_name = cluster_names.get(cluster_id, 'Kategori Umum')

        return jsonify({
            "status": "success",
            "cluster_id": cluster_id,
            "cluster_name": cluster_name,
            "nutrisi_target": {
                "calories": round(target_calories_meal, 1),
                "protein": round(target_protein, 1),
                "fat": round(target_fat, 1),
                "carbohydrates": round(target_carbs, 1)
            }
        }), 200

    except Exception as e:
        return jsonify({"error": str(e)}), 500
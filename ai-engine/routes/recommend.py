from flask import Blueprint, request, jsonify
import core.ai_model as ai

recommend_bp = Blueprint('recommend', __name__)

@recommend_bp.route('/api/predict/recommendation', methods=['POST'])
def get_recommendation():
    try:
        data = request.get_json()
        if not data: return jsonify({"error": "Data kosong"}), 400
            
        user_target = [[
            data.get('target_calories', 0),
            data.get('target_protein', 0),
            data.get('target_fat', 0),
            data.get('target_carbs', 0)
        ]]

        if ai.knn_model is None or ai.df is None:
            return jsonify({"status": "success", "target_diminta": user_target[0], "rekomendasi": []}), 200

        distances, indices = ai.knn_model.kneighbors(user_target)

        recommended_menus = []
        for i in range(len(indices[0])):
            idx = indices[0][i]
            menu = ai.df.iloc[idx]
            match_score = round(max(0, 100 - (distances[0][i] / 5)), 1) 

            recommended_menus.append({
                "id": menu['_id'], 
                "name": menu.get('name', 'Menu Tanpa Nama'),
                "category": menu.get('category', 'Umum'),
                "calories": float(menu['calories']),
                "protein": float(menu['protein']),
                "fat": float(menu['fat']),
                "carbohydrates": float(menu['carbohydrates']),
                "image": str(menu.get('image', '')),
                "serving_size_g": float(menu['serving_size_g']),
                "match_score": match_score
            })

        return jsonify({
            "status": "success",
            "target_diminta": user_target[0],
            "rekomendasi": recommended_menus
        }), 200
        
    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500
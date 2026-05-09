from flask import Blueprint, app, request, jsonify

evaluate_bp = Blueprint('evaluate', __name__)

@evaluate_bp.route('/api/predict/evaluation', methods=['POST'])
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
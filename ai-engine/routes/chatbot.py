from flask import Blueprint, request, jsonify
import re
import core.ai_model as ai

chatbot_bp = Blueprint('chatbot', __name__)

@chatbot_bp.route('/api/chat', methods=['POST'])
def chat_bot():
    try:
        data = request.get_json()
        if not data or 'message' not in data:
            return jsonify({"error": "Pesan tidak ditemukan"}), 400
            
        user_msg = data['message'].lower().strip()
        bot_reply = ""

        # 1. JIKA USER MENJAWAB "YA" ATAU "MAU"
        if user_msg in ['ya', 'mau', 'boleh', 'mau dong', 'iya', 'y', 'ok', 'oke', 'sip', 'coba', 'bisa', 'bisa dong']:
            bot_reply = "Sip! Karena saya melayani banyak orang, saya suka lupa angka kalorimu tadi 😅.<br><br>Tolong ketik perintahnya dengan format:<br>**rekomendasi [angka kalori]**<br><br>Contoh: *rekomendasi 1898*"
            return jsonify({"status": "success", "reply": bot_reply}), 200

        # 2. FITUR REKOMENDASI MENU BERDASARKAN KALORI (Sistem Baru!)
        rekomendasi_match = re.search(r'rekomendasi.*?(\d+)', user_msg)
        if rekomendasi_match:
            if ai.df is not None and len(ai.df) > 0:
                target_tdee = float(rekomendasi_match.group(1))
                # Asumsi 1 porsi makan utama = 35% dari total kalori harian
                target_porsi = target_tdee * 0.35 
                
                # Logika pintar: Cari 3 menu yang kalorinya paling mendekati target_porsi
                ai.df['selisih'] = abs(ai.df['calories'] - target_porsi)
                top_menus = ai.df.sort_values(by='selisih').head(3)
                
                bot_reply = f"Target 1x makan utama kamu adalah sekitar **{round(target_porsi)} kkal**.<br><br>Ini 3 rekomendasi menu terbaik dari database GIZENIA:<br>"
                for index, row in top_menus.iterrows():
                    bot_reply += f"🍲 **{row['name']}** ({row['calories']} kkal)<br>"
                
                bot_reply += "<br>Silakan Login ke 'Pelacak Nutrisi' untuk info gizi lengkapnya! ✨"
                return jsonify({"status": "success", "reply": bot_reply}), 200

        # 3. FITUR CARI MAKANAN DARI DATABASE (SMART SEARCH)
        if any(word in user_msg for word in ['berapa', 'cek', 'cari', 'kalori']):
            if ai.df is not None:
                matched_food = None
                for index, row in ai.df.iterrows():
                    if row['name'].lower() in user_msg:
                        matched_food = row
                        break
                
                if matched_food is not None:
                    bot_reply = f"Menurut database GIZENIA, **{matched_food['name']}** mengandung:<br>" \
                                f"• Kalori: {matched_food['calories']} kkal<br>" \
                                f"• Protein: {matched_food['protein']}g<br>" \
                                f"• Lemak: {matched_food['fat']}g<br>" \
                                f"• Karbo: {matched_food['carbohydrates']}g<br>" \
                                f"Data ini berdasarkan porsi standar ({matched_food['serving_size_g']}g). 🌱"
                    return jsonify({"status": "success", "reply": bot_reply}), 200

        # 4. FITUR KALKULATOR KALORI (BERAT & TINGGI)
        berat_match = re.search(r'berat(?: badan)?.*?(\d+)', user_msg)
        tinggi_match = re.search(r'tinggi(?: badan)?.*?(\d+)', user_msg)
        if berat_match and tinggi_match:
            berat = float(berat_match.group(1))
            tinggi = float(tinggi_match.group(1))
            tdee = ((10 * berat) + (6.25 * tinggi) - (5 * 25) + 5) * 1.375
            bot_reply = f"Dengan berat {berat}kg dan tinggi {tinggi}cm, kebutuhan harianmu sekitar **{round(tdee)} kkal**.<br><br>Ingin saya rekomendasikan menu yang cocok?"
            return jsonify({"status": "success", "reply": bot_reply}), 200

        # 5. JAWABAN UMUM (FALLBACK)
        if any(word in user_msg for word in ['halo', 'hai', 'siang', 'pagi', 'malam', 'siapa', 'kamu', 'nama', 'bisa apa']):
            bot_reply = "Halo! Saya GIZENIA AI. Saya bisa mencarikan info gizi makanan atau menghitung kebutuhan kalorimu. Mau coba?"
        elif "siapa" in user_msg:
            bot_reply = "Saya adalah asisten cerdas GIZENIA yang terhubung ke MongoDB Atlas untuk membantumu hidup lebih sehat!"
        else:
            bot_reply = "Maaf, saya tidak menemukan makanan tersebut. Coba sebutkan nama makanan lain, atau hitung kalori dengan format: 'berat 50 tinggi 160'."

        return jsonify({"status": "success", "reply": bot_reply}), 200

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500
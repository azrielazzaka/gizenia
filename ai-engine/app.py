from flask import Flask, jsonify
from flask_cors import CORS

# Import Komponen yang sudah kita pecah
from core.ai_model import init_engine
from routes.recommend import recommend_bp
from routes.evaluate import evaluate_bp
from routes.chatbot import chatbot_bp

app = Flask(__name__)
CORS(app)

# Nyalakan Koneksi MongoDB dan Model AI
init_engine()

# Daftarkan semua Blueprint (Rute)
app.register_blueprint(recommend_bp)
app.register_blueprint(evaluate_bp)
app.register_blueprint(chatbot_bp)

# Rute Default (Cek Status Server)
@app.route('/', methods=['GET'])
def home():
    return jsonify({"status": "online", "message": "GIZENIA AI Engine Active (Modular Version)!"}), 200

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
from flask import Flask, request, jsonify
import pickle
import os

app = Flask(__name__)

# Load the toxic detection model
model = None

def load_model():
    global model
    try:
        # Try loading from the current directory first
        if os.path.exists("toxic_pipeline.pkl"):
            with open("toxic_pipeline.pkl", "rb") as f:
                model = pickle.load(f)
            print("✅ Model loaded from current directory")
        # Try loading from main toxic classification folder
        elif os.path.exists("c:/Users/dovie/OneDrive/Desktop/Việt anh/Machine Learning/Toxic Comment Classification/toxic_pipeline.pkl"):
            with open("c:/Users/dovie/OneDrive/Desktop/Việt anh/Machine Learning/Toxic Comment Classification/toxic_pipeline.pkl", "rb") as f:
                model = pickle.load(f)
            print("✅ Model loaded from main directory")
        else:
            print("❌ Model file not found")
            return False
        return True
    except Exception as e:
        print(f"❌ Error loading model: {e}")
        return False

@app.route('/health', methods=['GET'])
def health_check():
    if model is None:
        return jsonify({'status': 'error', 'message': 'Model not loaded'}), 500
    return jsonify({'status': 'ok', 'message': 'API is running'}), 200

@app.route('/predict', methods=['POST'])
def predict():
    try:
        if model is None:
            return jsonify({'error': 'Model not loaded'}), 500
            
        data = request.get_json()
        if not data or 'text' not in data:
            return jsonify({'error': 'No text provided'}), 400
            
        text = data['text']
        if not text.strip():
            return jsonify({'error': 'Empty text'}), 400
            
        # Make prediction
        prediction = model.predict([text])[0]
        probability = model.predict_proba([text])[0]
        
        result = {
            'text': text,
            'is_toxic': bool(prediction),
            'prediction': int(prediction),
            'probability': {
                'not_toxic': float(probability[0]),
                'toxic': float(probability[1])
            }
        }
        
        return jsonify(result), 200
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    print("🚀 Starting Toxic Comment Detection API...")
    if load_model():
        print("🎯 Model loaded successfully!")
        print("🌐 API running on http://localhost:5001")
        app.run(host='0.0.0.0', port=5001, debug=False)
    else:
        print("💥 Failed to load model. Exiting...")
from flask import Flask, jsonify
import dask.dataframe as dd
from sqlalchemy import create_engine
import os
import traceback
import time

app = Flask(__name__)

# Database config
DB_HOST = os.getenv('DB_HOST', 'db')
DB_PORT = os.getenv('DB_PORT', '5432')
DB_NAME = os.getenv('DB_NAME', 'uas_db')
DB_USER = os.getenv('DB_USER', 'postgres')
DB_PASS = os.getenv('DB_PASS', 'password')

DB_URL = f"postgresql://{DB_USER}:{DB_PASS}@{DB_HOST}:{DB_PORT}/{DB_NAME}"

def wait_for_db(max_retries=30, delay=2):
    """Wait for database to be ready"""
    for i in range(max_retries):
        try:
            engine = create_engine(DB_URL)
            conn = engine.connect()
            conn.close()
            print(f"Database connection successful!")
            return True
        except Exception as e:
            print(f"Waiting for database... attempt {i+1}/{max_retries}")
            time.sleep(delay)
    return False

@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'healthy', 'service': 'dask-service'})

@app.route('/process', methods=['GET'])
def process_data():
    try:
        # Dask parallel processing
        ddf = dd.read_sql_table(
            table_name='energy_consumption',
            con=DB_URL,
            index_col='id',
            npartitions=4  # Parallel processing dengan 4 partisi
        )
        
        # Computasi paralel
        pdf = ddf.compute()
        
        # Hitung statistik paralel
        stats = {
            'total_records': len(pdf),
            'avg_power': float(pdf['power_watt'].mean()),
            'max_energy': float(pdf['energy_kwh'].max()),
            'total_partitions': ddf.npartitions,
            'peak_consumption': len(pdf[pdf['is_peak_hours'] == True])
        }
        
        data = pdf.to_dict(orient='records')
        
        return jsonify({
            'status': 'success',
            'parallel_stats': stats,
            'data': data
        })
    
    except Exception as e:
        error_trace = traceback.format_exc()
        print(f"Error: {str(e)}")
        print(f"Traceback: {error_trace}")
        return jsonify({
            'status': 'error',
            'message': str(e),
            'traceback': error_trace
        }), 500

if __name__ == '__main__':
    print("Starting Dask service...")
    print(f"Database URL: postgresql://{DB_USER}:***@{DB_HOST}:{DB_PORT}/{DB_NAME}")
    
    if wait_for_db():
        print("Starting Flask server...")
        app.run(host='0.0.0.0', port=5000, debug=True)
    else:
        print("Failed to connect to database after multiple retries")
        exit(1)

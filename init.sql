-- Tabel: energy_consumption (Smart Grid Monitoring)
-- Tabel unik: monitoring konsumsi energi listrik real-time

CREATE TABLE IF NOT EXISTS energy_consumption (
    id SERIAL PRIMARY KEY,
    meter_id VARCHAR(50) NOT NULL,
    region VARCHAR(100),
    voltage DECIMAL(6,2),
    current_amp DECIMAL(6,2),
    power_watt DECIMAL(10,2),
    energy_kwh DECIMAL(12,4),
    frequency_hz DECIMAL(5,2),
    power_factor DECIMAL(4,2),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_peak_hours BOOLEAN DEFAULT FALSE,
    grid_status VARCHAR(20) DEFAULT 'normal'
);

-- Tabel sessions untuk Laravel
CREATE TABLE IF NOT EXISTS sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload TEXT NOT NULL,
    last_activity INTEGER NOT NULL
);

CREATE INDEX IF NOT EXISTS sessions_user_id_index ON sessions(user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_index ON sessions(last_activity);

-- Tabel cache untuk Laravel
CREATE TABLE IF NOT EXISTS cache (
    key VARCHAR(255) PRIMARY KEY,
    value TEXT NOT NULL,
    expiration INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS cache_locks (
    key VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INTEGER NOT NULL
);

-- Insert sample data (20 rows untuk demo parallel processing)
INSERT INTO energy_consumption (meter_id, region, voltage, current_amp, power_watt, energy_kwh, frequency_hz, power_factor, is_peak_hours, grid_status) VALUES
('MTR-JKT-001', 'Jakarta Selatan', 220.5, 5.2, 1142.60, 1250.5000, 50.0, 0.95, TRUE, 'normal'),
('MTR-JKT-002', 'Jakarta Utara', 219.8, 4.8, 1055.04, 980.2500, 49.8, 0.92, TRUE, 'normal'),
('MTR-BDG-001', 'Bandung Kota', 221.2, 3.5, 774.20, 650.7500, 50.1, 0.88, FALSE, 'normal'),
('MTR-BDG-002', 'Lembang', 220.0, 2.8, 616.00, 420.3000, 50.0, 0.90, FALSE, 'stable'),
('MTR-SBY-001', 'Surabaya Pusat', 218.5, 6.5, 1420.25, 1580.9000, 49.5, 0.93, TRUE, 'warning'),
('MTR-SBY-002', 'Surabaya Timur', 219.2, 7.2, 1578.24, 1720.4500, 49.7, 0.91, TRUE, 'overload'),
('MTR-DPS-001', 'Denpasar', 220.8, 4.2, 927.36, 780.6000, 50.2, 0.94, FALSE, 'normal'),
('MTR-MDN-001', 'Medan', 217.5, 5.8, 1261.50, 1150.8000, 49.3, 0.89, TRUE, 'normal'),
('MTR-PLM-001', 'Palembang', 221.5, 6.0, 1329.00, 1380.2000, 50.3, 0.96, TRUE, 'normal'),
('MTR-MKS-001', 'Makassar', 219.5, 4.5, 987.75, 890.4500, 49.9, 0.87, FALSE, 'normal'),
('MTR-JKT-003', 'Jakarta Barat', 220.2, 8.5, 1871.70, 2100.3000, 50.0, 0.97, TRUE, 'overload'),
('MTR-JKT-004', 'Jakarta Timur', 218.8, 5.5, 1203.40, 1320.1500, 49.6, 0.94, TRUE, 'normal'),
('MTR-BDG-003', 'Cimahi', 220.5, 3.2, 705.60, 580.9000, 50.1, 0.91, FALSE, 'stable'),
('MTR-SBY-003', 'Sidoarjo', 219.0, 4.0, 876.00, 750.4000, 49.8, 0.90, FALSE, 'normal'),
('MTR-DPS-002', 'Badung', 221.0, 3.8, 839.80, 720.2500, 50.2, 0.93, FALSE, 'normal'),
('MTR-MDN-002', 'Deli Serdang', 218.0, 5.0, 1090.00, 980.6000, 49.4, 0.88, TRUE, 'normal'),
('MTR-PLM-002', 'Ogan Ilir', 220.0, 5.5, 1210.00, 1250.7500, 50.0, 0.95, TRUE, 'normal'),
('MTR-MKS-002', 'Gowa', 219.8, 4.2, 923.16, 820.3000, 49.9, 0.92, FALSE, 'normal'),
('MTR-JKT-005', 'Jakarta Pusat', 221.2, 6.8, 1504.16, 1650.9000, 50.1, 0.96, TRUE, 'warning'),
('MTR-BDG-004', 'Bandung Barat', 220.3, 2.9, 638.87, 450.5000, 50.0, 0.89, FALSE, 'stable');

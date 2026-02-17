<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS - Parallel Processing Dask | Energy Monitoring</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 10px;
        }
        .subtitle {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        .stat-value {
            font-size: 2em;
            font-weight: bold;
        }
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 0.9em;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: 600;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .badge-peak { background: #e74c3c; color: white; }
        .badge-normal { background: #27ae60; color: white; }
        .badge-warning { background: #f39c12; color: white; }
        .badge-overload { background: #c0392b; color: white; }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #7f8c8d;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚡ Smart Grid Energy Monitoring</h1>
        <p class="subtitle">UAS - Energy Monitoring System with PostgreSQL</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total_records'] }}</div>
                <div class="stat-label">Total Records</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['avg_power'], 0) }} W</div>
                <div class="stat-label">Avg Power</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['max_energy'], 0) }}</div>
                <div class="stat-label">Max Energy (kWh)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ number_format($stats['total_energy'], 0) }}</div>
                <div class="stat-label">Total Energy (kWh)</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $stats['peak_consumption'] }}</div>
                <div class="stat-label">Peak Hours</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Meter ID</th>
                    <th>Region</th>
                    <th>Voltage (V)</th>
                    <th>Current (A)</th>
                    <th>Power (W)</th>
                    <th>Energy (kWh)</th>
                    <th>Frequency</th>
                    <th>Power Factor</th>
                    <th>Peak Hours</th>
                    <th>Grid Status</th>
                    <th>Recorded At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td><strong>{{ $row->meter_id }}</strong></td>
                    <td>{{ $row->region }}</td>
                    <td>{{ $row->voltage }}</td>
                    <td>{{ $row->current_amp }}</td>
                    <td>{{ number_format($row->power_watt, 2) }}</td>
                    <td>{{ number_format($row->energy_kwh, 4) }}</td>
                    <td>{{ $row->frequency_hz }} Hz</td>
                    <td>{{ $row->power_factor }}</td>
                    <td>
                        @if($row->is_peak_hours)
                            <span class="badge badge-peak">PEAK</span>
                        @else
                            <span class="badge badge-normal">Normal</span>
                        @endif
                    </td>
                    <td>
                        @if($row->grid_status == 'normal')
                            <span class="badge badge-normal">Normal</span>
                        @elseif($row->grid_status == 'warning')
                            <span class="badge badge-warning">Warning</span>
                        @elseif($row->grid_status == 'overload')
                            <span class="badge badge-overload">Overload</span>
                        @else
                            <span class="badge badge-normal">Stable</span>
                        @endif
                    </td>
                    <td>{{ $row->recorded_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Generated with Laravel + PostgreSQL | 
            <a href="/api/list">View JSON</a></p>
        </div>
    </div>
</body>
</html>

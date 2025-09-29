<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeviceController extends Controller
{
    /**
     * Get current device status for dashboard
     */
    public function getStatus(Request $request)
    {
        $sensorId = $request->get('sensor_id', 'UROMED_001');
        
        // Get cached sensor status
        $sensorStatus = Cache::get('sensor_status_' . $sensorId, [
            'last_ping' => now()->subMinutes(10),
            'ip_address' => null,
            'firmware_version' => 'unknown',
            'battery_level' => 0,
            'signal_strength' => 0,
            'status' => 'offline'
        ]);

        // Determine if device is online (last ping within 5 minutes)
        $isOnline = now()->diffInMinutes($sensorStatus['last_ping']) <= 5;
        
        return response()->json([
            'success' => true,
            'data' => [
                'sensor_id' => $sensorId,
                'connected' => $isOnline,
                'status' => $isOnline ? 'online' : 'offline',
                'last_ping' => $sensorStatus['last_ping'],
                'battery_level' => $sensorStatus['battery_level'],
                'signal_strength' => $sensorStatus['signal_strength'],
                'firmware_version' => $sensorStatus['firmware_version'],
                'ip_address' => $sensorStatus['ip_address']
            ]
        ]);
    }

    /**
     * Get device logs for debugging
     */
    public function getLogs(Request $request)
    {
        $sensorId = $request->get('sensor_id', 'UROMED_001');
        $limit = $request->get('limit', 50);
        
        // Get logs from cache or database
        $logs = Cache::get('sensor_logs_' . $sensorId, []);
        
        return response()->json([
            'success' => true,
            'data' => array_slice($logs, -$limit)
        ]);
    }
}
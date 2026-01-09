<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client; 
use App\Models\ScanLog; // <--- Nhớ dòng này để gọi Database
use Illuminate\Support\Facades\Log;

class PlantController extends Controller
{
    // 1. Hiển thị giao diện (kèm danh sách Log)
    public function index()
    {
        // Lấy 5 dòng log mới nhất từ Database
        $logs = ScanLog::orderBy('created_at', 'desc')->paginate(5);
        
        return view('plant_detection', [
            'logs' => $logs
        ]);
    }

    // 2. Xử lý gửi ảnh thủ công (Giữ nguyên logic cũ, chỉ thêm logs)
    public function detectDisease(Request $request)
    {
        // Validate ảnh
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $image = $request->file('image');
        $imagePath = $image->getPathname();
        $originalName = $image->getClientOriginalName();

        // Gửi sang Python API
        $client = new Client();
        $pythonApiUrl = 'http://127.0.0.1:5000/predict'; 

        // Lấy lại danh sách Log để hiển thị bên dưới (dù đang xử lý ảnh)
        $logs = ScanLog::orderBy('created_at', 'desc')->paginate(5);

        try {
            $response = $client->request('POST', $pythonApiUrl, [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $originalName
                    ]
                ]
            ]);

            $body = $response->getBody();
            $result = json_decode($body, true);

            if (isset($result['success']) && $result['success'] == true) {
                // Lưu ảnh upload thủ công vào public để hiển thị
                $storedPath = $image->store('plants', 'public');

                return view('plant_detection', [
                    'prediction' => $result['prediction'],
                    'confidence' => $result['confidence'],
                    'image_url'  => $storedPath,
                    'logs'       => $logs // <--- Truyền thêm logs vào
                ]);
            } else {
                return back()->with('error', 'AI không nhận diện được.')->with('logs', $logs);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối Python Server: ' . $e->getMessage())->with('logs', $logs);
        }
    }
}
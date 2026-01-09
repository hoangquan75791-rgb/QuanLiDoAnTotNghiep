<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Giám sát & Chẩn đoán Bệnh Cây</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header { font-weight: bold; text-transform: uppercase; }
        .img-preview { max-height: 300px; object-fit: contain; }
    </style>
</head>
<body class="bg-light">

<div class="container py-4">
    
    <div class="text-center mb-4">
        <h1 class="fw-bold text-success">🌱 HỆ THỐNG GIÁM SÁT CÂY TRỒNG THÔNG MINH</h1>
        <p class="text-muted">Tích hợp AI chẩn đoán & IoT giám sát thời gian thực</p>
    </div>

    <div class="row">
        <div class="col-md-12 mb-5">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    🔍 Kiểm tra thủ công (Upload ảnh)
                </div>
                <div class="card-body text-center">
                    
                    <form action="{{ route('plant.detect') }}" method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <div class="input-group">
                            <input type="file" name="image" class="form-control" required>
                            <button class="btn btn-primary" type="submit">🚀 Phân Tích Ngay</button>
                        </div>
                    </form>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(isset($prediction))
                        <div class="mt-4 p-3 border rounded bg-white">
                            <h4>📸 Kết Quả Phân Tích Ảnh Vừa Chọn</h4>
                            <img src="{{ asset('storage/' . $image_url) }}" class="img-fluid rounded img-preview mb-3">
                            
                            @if(str_contains(strtolower($prediction), 'healthy'))
                                <div class="alert alert-success">
                                    <h3>🌿 Cây Khỏe Mạnh</h3>
                                    <p>Độ tin cậy: <strong>{{ $confidence }}%</strong></p>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <h3>⚠️ Phát hiện bệnh: {{ $prediction }}</h3>
                                    <p>Độ tin cậy: <strong>{{ $confidence }}%</strong></p>
                                    <hr>
                                    <strong>Khuyến nghị:</strong> Cần cách ly cây và kiểm tra độ ẩm đất.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span>📡 Nhật Ký Hoạt Động ESP32-CAM (IoT Logs)</span>
                    <a href="{{ route('plant.index') }}" class="btn btn-sm btn-outline-light">🔄 Làm mới danh sách</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>Thời gian chụp</th>
                                    <th>Ảnh thực tế</th>
                                    <th>AI Chẩn đoán</th>
                                    <th>Độ tin cậy</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($logs) && count($logs) > 0)
                                    @foreach($logs as $log)
                                    <tr>
                                        <td>#{{ $log->id }}</td>
                                        <td>{{ $log->created_at->format('H:i:s d/m/Y') }}</td>
                                        <td>
                                            <img src="{{ asset($log->image_path) }}" width="80" class="rounded border">
                                        </td>
                                        <td class="fw-bold text-primary">{{ $log->disease_name }}</td>
                                        <td>{{ number_format($log->confidence, 1) }}%</td>
                                        <td>
                                            @if(str_contains(strtolower($log->disease_name), 'healthy'))
                                                <span class="badge bg-success">An Toàn</span>
                                            @elseif(str_contains(strtolower($log->disease_name), 'khong xac dinh'))
                                                <span class="badge bg-secondary">Chưa rõ</span>
                                            @else
                                                <span class="badge bg-danger">Cảnh Báo</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-muted">Chưa có dữ liệu từ ESP32...</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        @if(isset($logs))
                            {{ $logs->links() }} 
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
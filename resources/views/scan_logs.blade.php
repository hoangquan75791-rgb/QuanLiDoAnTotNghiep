<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhật Ký Quét IoT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta http-equiv="refresh" content="5"> </head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="text-center mb-4">📜 Nhật Ký Hoạt Động ESP32-CAM</h2>
        
        <div class="card shadow">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Thời gian</th>
                            <th>Ảnh Chụp</th>
                            <th>Bệnh Dự Đoán</th>
                            <th>Độ Tin Cậy</th>
                            <th>Trạng Thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->created_at }}</td>
                            <td>
                                <img src="{{ asset($log->image_path) }}" width="100" class="rounded border">
                            </td>
                            <td class="fw-bold">{{ $log->disease_name }}</td>
                            <td>{{ number_format($log->confidence, 2) }}%</td>
                            <td>
                                @if(str_contains(strtolower($log->disease_name), 'healthy'))
                                    <span class="badge bg-success">An Toàn</span>
                                @else
                                    <span class="badge bg-danger">Cảnh Báo</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
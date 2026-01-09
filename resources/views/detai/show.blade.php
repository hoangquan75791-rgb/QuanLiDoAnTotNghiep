<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quản lý Đồ án: {{ $deTai->ten_de_tai }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Thông báo -->
            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- CỘT TRÁI: Thông tin & Chấm điểm -->
                <div class="space-y-6">
                    <!-- 1. Thông tin cơ bản -->
                    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Thông tin chung</h3>
                        <p class="text-gray-600 dark:text-gray-300"><strong>Sinh viên:</strong> {{ $deTai->sinhVien->name ?? 'Chưa có' }}</p>
                        <p class="text-gray-600 dark:text-gray-300"><strong>Mô tả:</strong> {{ $deTai->mo_ta }}</p>
                    </div>

                    <!-- 2. Chấm điểm -->
                    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Chấm điểm</h3>
                        <form action="{{ route('giangvien.chamdiem', $deTai->id) }}" method="POST">
                            @csrf
                            <div class="flex gap-2">
                                <input type="number" step="0.1" min="0" max="10" name="diem_so" 
                                       value="{{ $deTai->diem_so }}" 
                                       class="border-gray-300 rounded-md w-full" 
                                       placeholder="Nhập điểm (0-10)">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Lưu
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- 3. Xử lý Yêu cầu (Gia hạn / Phúc khảo) -->
                    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Yêu cầu từ Sinh viên</h3>
                        
                        @if($deTai->yeuCaus->isEmpty())
                            <p class="text-gray-500">Không có yêu cầu nào.</p>
                        @else
                            <ul class="space-y-4">
                                @foreach($deTai->yeuCaus as $yeuCau)
                                    <li class="border p-3 rounded bg-gray-50 dark:bg-gray-700">
                                        <p class="font-bold text-red-500 uppercase">{{ $yeuCau->loai_yeu_cau }}</p>
                                        <p class="text-sm">{{ $yeuCau->ly_do }}</p>
                                        @if($yeuCau->loai_yeu_cau == 'gia_han')
                                            <p class="text-sm text-blue-600">Xin gia hạn đến: {{ $yeuCau->ngay_gia_han_mong_muon }}</p>
                                        @endif
                                        <p class="text-xs mt-1">Trạng thái: <strong>{{ $yeuCau->trang_thai }}</strong></p>

                                        @if($yeuCau->trang_thai == 'cho_duyet')
                                            <form action="{{ route('giangvien.xulyyeucau', $yeuCau->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="text" name="phan_hoi" placeholder="Lý do/Phản hồi" class="w-full text-sm mb-2 border-gray-300 rounded">
                                                <div class="flex gap-2">
                                                    <button name="chap_nhan" value="1" class="bg-green-500 text-white px-2 py-1 rounded text-xs">Chấp nhận</button>
                                                    <button name="tu_choi" value="1" class="bg-red-500 text-white px-2 py-1 rounded text-xs">Từ chối</button>
                                                </div>
                                            </form>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Phản hồi: {{ $yeuCau->phan_hoi_nguoi_duyet }}</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- CỘT PHẢI: Nhận xét & Lịch sử nộp bài -->
                <div class="space-y-6">
                    <!-- 4. Khu vực Nhận xét -->
                    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Trao đổi & Nhận xét</h3>
                        
                        <!-- Danh sách nhận xét cũ -->
                        <div class="max-h-60 overflow-y-auto mb-4 space-y-3">
                            @foreach($deTai->nhanXets as $nx)
                                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded">
                                    <p class="text-sm text-gray-800 dark:text-white">{{ $nx->noi_dung }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $nx->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>

                        <!-- Form đăng nhận xét mới -->
                        <form action="{{ route('giangvien.nhanxet', $deTai->id) }}" method="POST">
                            @csrf
                            <textarea name="noi_dung" rows="3" class="w-full border-gray-300 rounded-md" placeholder="Viết nhận xét..."></textarea>
                            <button type="submit" class="mt-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">
                                Gửi Nhận xét
                            </button>
                        </form>
                    </div>

                    <!-- 5. Lịch sử bài nộp (Code cũ của bạn) -->
                    <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            File đã nộp
                        </h3>
                        <ul class="space-y-2">
                            @forelse ($deTai->baiNops as $baiNop)
                                <li class="p-3 bg-gray-50 dark:bg-gray-700 rounded border flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-sm">{{ $baiNop->tieu_de }}</p>
                                        <p class="text-xs text-gray-500">{{ $baiNop->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <a href="{{ Storage::url($baiNop->file_path) }}" target="_blank" class="text-blue-500 text-sm hover:underline">Tải về</a>
                                </li>
                            @empty
                                <p class="text-gray-500">Chưa có bài nộp nào.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

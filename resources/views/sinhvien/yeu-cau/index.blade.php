<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gửi Yêu cầu (Gia hạn / Phúc khảo)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Thông báo -->
            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Form Gửi Yêu cầu -->
                <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tạo yêu cầu mới</h3>
                    
                    <form method="POST" action="{{ route('sinhvien.yeucau.store') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Loại yêu cầu -->
                        <div>
                            <x-input-label for="loai_yeu_cau" value="Loại yêu cầu" />
                            <select id="loai_yeu_cau" name="loai_yeu_cau" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm" onchange="toggleNgayGiaHan()">
                                <option value="gia_han">Xin gia hạn nộp bài</option>
                                <option value="phuc_khao">Yêu cầu phúc khảo điểm</option>
                            </select>
                        </div>

                        <!-- Ngày gia hạn (Ẩn hiện bằng JS đơn giản) -->
                        <div id="div_ngay_gia_han">
                            <x-input-label for="ngay_gia_han" value="Gia hạn đến ngày" />
                            <input type="date" id="ngay_gia_han" name="ngay_gia_han" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Lý do -->
                        <div>
                            <x-input-label for="noi_dung" value="Lý do / Nội dung chi tiết" />
                            <textarea id="noi_dung" name="noi_dung" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                        </div>

                        <x-primary-button>Gửi yêu cầu</x-primary-button>
                    </form>
                </div>

                <!-- Lịch sử yêu cầu -->
                <div class="p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Lịch sử yêu cầu</h3>
                    
                    <ul class="space-y-4">
                        @forelse ($lichSuYeuCau as $yc)
                            <li class="border p-3 rounded bg-gray-50 dark:bg-gray-700">
                                <div class="flex justify-between">
                                    <span class="font-bold uppercase {{ $yc->loai_yeu_cau == 'gia_han' ? 'text-blue-600' : 'text-purple-600' }}">
                                        {{ $yc->loai_yeu_cau == 'gia_han' ? 'Xin gia hạn' : 'Phúc khảo' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $yc->created_at->format('d/m/Y') }}</span>
                                </div>
                                
                                <p class="text-sm mt-1"><strong>Lý do:</strong> {{ $yc->noi_dung }}</p>
                                
                                @if($yc->ngay_gia_han)
                                    <p class="text-sm text-gray-600">Xin đến: {{ \Carbon\Carbon::parse($yc->ngay_gia_han)->format('d/m/Y') }}</p>
                                @endif

                                <div class="mt-2 pt-2 border-t flex justify-between items-center">
                                    <span class="text-xs font-bold 
                                        {{ $yc->trang_thai == 'cho_duyet' ? 'text-yellow-500' : '' }}
                                        {{ $yc->trang_thai == 'da_duyet' || $yc->trang_thai == 'chap_nhan' ? 'text-green-500' : '' }}
                                        {{ $yc->trang_thai == 'tu_choi' ? 'text-red-500' : '' }}">
                                        {{ $yc->trang_thai == 'cho_duyet' ? 'Đang chờ duyệt' : ($yc->trang_thai == 'tu_choi' ? 'Đã từ chối' : 'Đã chấp nhận') }}
                                    </span>
                                    
                                    @if($yc->phan_hoi)
                                        <span class="text-xs text-gray-500 italic">Phản hồi: {{ $yc->phan_hoi }}</span>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <p class="text-gray-500">Bạn chưa gửi yêu cầu nào.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleNgayGiaHan() {
            const loai = document.getElementById('loai_yeu_cau').value;
            const divNgay = document.getElementById('div_ngay_gia_han');
            if (loai === 'gia_han') {
                divNgay.style.display = 'block';
            } else {
                divNgay.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
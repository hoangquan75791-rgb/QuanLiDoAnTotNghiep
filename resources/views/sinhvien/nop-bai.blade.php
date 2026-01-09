<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nộp Bài
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Form nộp bài -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Nộp file cho đề tài: {{ $deTai->ten_de_tai }}
                    </h3>

                    @if (session('success'))
                        <div class="mt-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mt-4 text-sm text-red-600 dark:text-red-400">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- === CẬP NHẬT GIAI ĐOẠN 11 - TASK 2 === -->
                    @if ($isSubmissionOpen)
                        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                            @if($deadline)
                                <span>📅 Hạn chót nộp bài: {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>

                        <!-- Form này bắt buộc phải có: enctype="multipart/form-data" để tải file -->
                        <form method="POST" action="{{ route('sinhvien.nopbai.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf

                            <!-- Tiêu đề -->
                            <div>
                                <x-input-label for="tieu_de" value="Tiêu đề (VD: Báo cáo tuần 1, Nộp file final...)" />
                                <x-text-input id="tieu_de" name="tieu_de" type="text" class="mt-1 block w-full" required />
                            </div>

                            <!-- Chọn File -->
                            <div>
                                <x-input-label for="file_nop" value="Chọn file (PDF, ZIP, DOCX - Tối đa 20MB)" />
                                <input id="file_nop" name="file_nop" type="file" class="mt-1 block w-full text-gray-900 dark:text-gray-100" required>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Nộp Bài</x-primary-button>
                            </div>
                        </form>
                    @else
                        <!-- HẾT HẠN -->
                        <div class="mt-6 p-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-200">
                            <p class="font-bold">Đã hết hạn nộp bài.</p>
                            <p>Thời gian nộp bài đã kết thúc vào lúc: {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }}</p>
                            <p class="mt-2 text-sm">Bạn không thể tải thêm file mới. Vui lòng liên hệ Giảng viên nếu có sự cố.</p>
                        </div>
                    @endif
                    <!-- === KẾT THÚC CẬP NHẬT === -->

                </div>
            </div>

            <!-- Lịch sử nộp bài -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Lịch sử nộp bài & Nhận xét
                </h3>
                <ul class="mt-6 space-y-6">
                    @forelse ($danhSachBaiNop as $baiNop)
                        <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                            <div class="flex justify-between items-center">
                                <p class="font-semibold dark:text-white">{{ $baiNop->tieu_de }}</p>
                                <a href="{{ Storage::url($baiNop->file_path) }}" 
                                   target="_blank" 
                                   class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">
                                    Tải file
                                </a>
                            </div>
                            <p class="text-sm dark:text-gray-400">Nộp lúc: {{ $baiNop->created_at->format('d/m/Y H:i') }}</p>

                            <!-- Hiển thị các nhận xét đã có -->
                            <div class="mt-4 ml-4 border-l-4 border-gray-300 dark:border-gray-500 pl-4 space-y-3">
                                @forelse ($baiNop->nhanXets as $nhanXet)
                                    <div class="bg-white dark:bg-gray-800 p-3 rounded-md shadow">
                                        <p class="text-gray-800 dark:text-gray-200">{{ $nhanXet->noi_dung }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <!-- Hiển thị tên Giảng viên đã nhận xét -->
                                            - {{ $nhanXet->giangVien->name }} ({{ $nhanXet->created_at->format('d/m/Y H:i') }})
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Chưa có nhận xét nào cho bài nộp này.</p>
                                @endforelse
                            </div>
                            
                        </li>
                    @empty
                        <p class="dark:text-gray-300">Bạn chưa nộp file nào.</p>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>

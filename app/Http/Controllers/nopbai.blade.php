<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nộp Bài
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

                    <form method="POST" action="{{ route('sinhvien.nopbai.store') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="tieu_de" value="Tiêu đề (VD: Báo cáo tuần 1, Nộp file final...)" />
                            <x-text-input id="tieu_de" name="tieu_de" type="text" class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="file_nop" value="Chọn file (PDF, ZIP, DOCX - Tối đa 20MB)" />
                            <input id="file_nop" name="file_nop" type="file" class="mt-1 block w-full text-gray-900 dark:text-gray-100" required>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Nộp Bài</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Lịch sử nộp bài
                </h3>
                <ul class="mt-6 space-y-4">
                    @forelse ($danhSachBaiNop as $baiNop)
                        <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <p class="font-semibold dark:text-white">{{ $baiNop->tieu_de }}</p>
                            <p class="text-sm dark:text-gray-400">Nộp lúc: {{ $baiNop->created_at->format('d/m/Y H:i') }}</p>
                            
                            <a href="{{ Storage::url($baiNop->file_path) }}" 
                               target="_blank" 
                               class="mt-2 inline-block text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">
                                Tải file
                            </a>
                        </li>
                    @empty
                        <p class="dark:text-gray-300">Bạn chưa nộp file nào.</p>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
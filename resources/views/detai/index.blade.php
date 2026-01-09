<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quản lý Đề tài
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Thêm đề tài mới
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

                    <form method="POST" action="{{ route('detai.store') }}" class="mt-6 space-y-6">
                        @csrf <div>
                            <x-input-label for="ten_de_tai" value="Tên Đề tài" />
                            <x-text-input id="ten_de_tai" name="ten_de_tai" type="text" class="mt-1 block w-full" :value="old('ten_de_tai')" required autofocus />
                        </div>

                        <div>
                            <x-input-label for="mo_ta" value="Mô tả ngắn" />
                            <textarea id="mo_ta" name="mo_ta" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('mo_ta') }}</textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>Lưu Đề tài</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Danh sách đề tài của bạn
                    </h3>
                </div>
                
                <div class="mt-2 mb-4 text-sm text-gray-600 dark:text-gray-400">
                    @if(isset($registration_deadline) && $registration_deadline)
                        <span class="mr-4">📅 Hạn đăng ký: {{ \Carbon\Carbon::parse($registration_deadline)->format('d/m/Y H:i') }}</span>
                    @endif
                    @if(isset($submission_deadline) && $submission_deadline)
                        <span>📅 Hạn nộp bài: {{ \Carbon\Carbon::parse($submission_deadline)->format('d/m/Y H:i') }}</span>
                    @endif
                </div>

                <ul class="mt-6 space-y-4">
                    @forelse ($ds_detai as $deTai)
                        <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow">
                            
                            <a href="{{ route('detai.show', $deTai) }}" 
                               class="text-xl font-semibold dark:text-white hover:text-indigo-500 dark:hover:text-indigo-400">
                                {{ $deTai->ten_de_tai }}
                            </a>

                            <p class="mt-2 text-gray-600 dark:text-gray-300">{{ $deTai->mo_ta }}</p>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Trạng thái: <strong>{{ $deTai->trang_thai }}</strong>
                            </p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Sinh viên thực hiện: 
                                <strong>
                                    {{ $deTai->sinhVien ? $deTai->sinhVien->name : 'Chưa có SV đăng ký' }}
                                </strong>
                            </p>
                        </li>
                    @empty
                        <li class="text-gray-500 dark:text-gray-400">
                            Bạn chưa tạo đề tài nào.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>

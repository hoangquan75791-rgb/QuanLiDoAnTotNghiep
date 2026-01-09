<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Đăng ký Đề tài
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($deTaiCuaToi)
                        <h3 class="text-lg font-medium">Đề tài của bạn:</h3>
                        <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <p class="text-xl font-semibold dark:text-white">{{ $deTaiCuaToi->ten_de_tai }}</p>
                            <p class="mt-2 dark:text-gray-300">{{ $deTaiCuaToi->mo_ta }}</p>
                            <p class="mt-2 text-sm dark:text-gray-400">
                                Giảng viên hướng dẫn: 
                                <strong>{{ $deTaiCuaToi->giangVienHuongDan->name }}</strong>
                            </p>
                            <p class="mt-1 text-sm dark:text-gray-400">
                                Trạng thái: <strong>{{ $deTaiCuaToi->trang_thai }}</strong>
                            </p>

                            @if ($deTaiCuaToi->diem_so !== null)
                                <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100">Kết quả cuối cùng</h4>
                                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $deTaiCuaToi->diem_so }}
                                    </p>
                                </div>
                            @else
                                <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Đề tài của bạn chưa được chấm điểm.</p>
                                </div>
                            @endif

                            </div>

                    @else
                        <h3 class="text-lg font-medium">Chọn một đề tài để đăng ký:</h3>
                        <ul class="mt-6 space-y-4">
                            @forelse ($deTaiSanCo as $deTai)
                                <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg flex justify-between items-center">
                                    <div>
                                        <p class="text-xl font-semibold dark:text-white">{{ $deTai->ten_de_tai }}</p>
                                        <p class="mt-2 text-sm dark:text-gray-400">
                                            Giảng viên: 
                                            <strong>{{ $deTai->giangVienHuongDan->name }}</strong>
                                        </p>
                                    </div>
                                    
                                    <form method="POST" action="{{ route('sinhvien.dangky.store') }}">
                                        @csrf
                                        <input type="hidden" name="de_tai_id" value="{{ $deTai->id }}">
                                        <x-primary-button>
                                            Đăng ký
                                        </x-primary-button>
                                    </form>
                                </li>
                            @empty
                                <p class="dark:text-gray-300">Hiện tại không có đề tài nào mở để đăng ký.</p>
                            @endforelse
                        </ul>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

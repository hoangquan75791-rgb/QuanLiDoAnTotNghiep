@use Illuminate\Support\Str;
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Đăng ký Đề tài') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hiển thị thông báo (nếu có) -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
                    {{ session('error') }}
                </div>
            @endif


            <!-- KIỂM TRA XEM SINH VIÊN ĐÃ ĐĂNG KÝ CHƯA -->
            @if($doAnDaDangKy)
                <!-- NẾU ĐÃ CÓ ĐỒ ÁN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4">Bạn đã đăng ký Đề tài</h3>
                        <p class="text-xl font-medium text-indigo-700">{{ $doAnDaDangKy->ten_de_tai }}</p>
                        <p class="mt-2 text-gray-600">
                            Giảng viên hướng dẫn: 
                            <!-- Gọi quan hệ 'giangVienHuongDan' mà ta đã tạo trong Model -->
                            <strong>{{ $doAnDaDangKy->giangVienHuongDan->name }}</strong>
                        </p>
                        <p class="mt-4">Trạng thái: 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Đã đăng ký
                            </span>
                        </p>
                    </div>
                </div>
            @else
                <!-- NẾU CHƯA CÓ ĐỒ ÁN -> HIỂN THỊ DANH SÁCH -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="font-bold text-lg mb-4">Các đề tài có thể đăng ký</h3>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Đề tài</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giảng viên hướng dẫn</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Hành động</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ds_detai_moi as $detai)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $detai->ten_de_tai }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($detai->mo_ta, 70) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $detai->giangVienHuongDan->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <!-- Form Đăng ký (cho mỗi nút) -->
                                            <form method="POST" action="{{ route('detai.dangky', $detai->id) }}">
                                                @csrf
                                                <x-primary-button>
                                                    {{ __('Đăng ký') }}
                                                </x-primary-button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Hiện không có đề tài nào "Mới tạo" để đăng ký.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
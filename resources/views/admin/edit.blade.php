<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Chỉnh sửa Người dùng: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="max-w-xl">
                        
                        @if ($errors->any())
                            <div class="mb-4 text-sm text-red-600 dark:text-red-400">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('PATCH') <div>
                                <x-input-label for="name" value="Tên" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            </div>

                            <div>
                                <x-input-label for="ma_so" value="Mã số (MSSV/MGV)" />
                                <x-text-input id="ma_so" name="ma_so" type="text" class="mt-1 block w-full" :value="old('ma_so', $user->ma_so)" required />
                            </div>

                            <div>
                                <x-input-label for="vai_tro" value="Vai trò" />
                                <select id="vai_tro" name="vai_tro" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="sinh_vien" @selected(old('vai_tro', $user->vai_tro) == 'sinh_vien')>
                                        Sinh viên
                                    </option>
                                    <option value="giang_vien" @selected(old('vai_tro', $user->vai_tro) == 'giang_vien')>
                                        Giảng viên
                                    </option>
                                    <option value="quan_tri_vien" @selected(old('vai_tro', $user->vai_tro) == 'quan_tri_vien')>
                                        Quản trị viên
                                    </option>
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="lop" value="Lớp (nếu là Sinh viên)" />
                                <x-text-input id="lop" name="lop" type="text" class="mt-1 block w-full" :value="old('lop', $user->lop)" />
                            </div>
                            
                            <div>
                                <x-input-label for="chuyen_mon" value="Chuyên môn (nếu là Giảng viên)" />
                                <x-text-input id="chuyen_mon" name="chuyen_mon" type="text" class="mt-1 block w-full" :value="old('chuyen_mon', $user->chuyen_mon)" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Lưu thay đổi</x-primary-button>
                                <a href="{{ route('admin.dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">Hủy</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
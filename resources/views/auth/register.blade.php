<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="ma_so" value="Mã Số (MSSV hoặc Mã GV)" />
            <x-text-input id="ma_so" class="block mt-1 w-full" type="text" name="ma_so" :value="old('ma_so')" required />
            <x-input-error :messages="$errors->get('ma_so')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="vai_tro" value="Bạn là" />
            <select name="vai_tro" id="vai_tro" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="sinh_vien" @if(old('vai_tro') == 'sinh_vien') selected @endif>Sinh viên</option>
                <option value="giang_vien" @if(old('vai_tro') == 'giang_vien') selected @endif>Giảng viên</option>
            </select>
            <x-input-error :messages="$errors->get('vai_tro')" class="mt-2" />
        </div>

        <div class="mt-4" id="lop_div">
            <x-input-label for="lop" value="Lớp (Dành cho Sinh viên)" />
            <x-text-input id="lop" class="block mt-1 w-full" type="text" name="lop" :value="old('lop')" />
            <x-input-error :messages="$errors->get('lop')" class="mt-2" />
        </div>

        <div class="mt-4" id="chuyen_mon_div">
            <x-input-label for="chuyen_mon" value="Chuyên Môn (Dành cho Giảng viên)" />
            <x-text-input id="chuyen_mon" class="block mt-1 w-full" type="text" name="chuyen_mon" :value="old('chuyen_mon')" />
            <x-input-error :messages="$errors->get('chuyen_mon')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        // Lấy các đối tượng DOM
        const vaiTroSelect = document.getElementById('vai_tro');
        const lopDiv = document.getElementById('lop_div');
        const chuyenMonDiv = document.getElementById('chuyen_mon_div');

        // Hàm để cập nhật giao diện
        function toggleFields() {
            const selectedRole = vaiTroSelect.value;

            if (selectedRole === 'sinh_vien') {
                lopDiv.style.display = 'block'; // Hiện ô Lớp
                chuyenMonDiv.style.display = 'none'; // Ẩn ô Chuyên Môn
            } else if (selectedRole === 'giang_vien') {
                lopDiv.style.display = 'none'; // Ẩn ô Lớp
                chuyenMonDiv.style.display = 'block'; // Hiện ô Chuyên Môn
            } else {
                // Trường hợp dự phòng (nếu có)
                lopDiv.style.display = 'none';
                chuyenMonDiv.style.display = 'none';
            }
        }

        // Thêm sự kiện 'change' vào dropdown
        vaiTroSelect.addEventListener('change', toggleFields);

        // Chạy hàm này 1 lần khi tải trang để set trạng thái ban đầu
        // (Rất quan trọng khi trang tải lại do lỗi validation)
        document.addEventListener('DOMContentLoaded', toggleFields);
    </script>
    </x-guest-layout>

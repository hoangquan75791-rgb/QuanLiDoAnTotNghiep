<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Quản lý Thời gian (Deadlines)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hiển thị thông báo (nếu vừa cập nhật) -->
            @if (session('status') === 'settings-updated')
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 shadow sm:rounded-lg">
                    Đã cập nhật thời gian thành công!
                </div>
            @endif
            
            <!-- Hiển thị lỗi validation (nếu có) -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 shadow sm:rounded-lg">
                    <strong>Rất tiếc! Đã có lỗi xảy ra.</strong>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Thiết lập các mốc thời gian
                        </h3>
                    
                        <form method="POST" action="{{ route('admin.settings.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <!-- Hạn Đăng ký Đề tài -->
                            <div>
                                <x-input-label for="registration_deadline" value="Hạn chót Đăng ký Đề tài" />
                                <x-text-input id="registration_deadline" name="registration_deadline" 
                                              type="datetime-local" 
                                              class="mt-1 block w-full" 
                                              :value="old('registration_deadline', $registration_deadline)" />
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Sau thời gian này, sinh viên sẽ không thể đăng ký đề tài mới.
                                </p>
                            </div>

                            <!-- Hạn Nộp bài cuối cùng -->
                            <div>
                                <x-input-label for="submission_deadline" value="Hạn chót Nộp bài cuối cùng" />
                                <x-text-input id="submission_deadline" name="submission_deadline" 
                                              type="datetime-local" 
                                              class="mt-1 block w-full" 
                                              :value="old('submission_deadline', $submission_deadline)" />
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Sau thời gian này, sinh viên sẽ không thể nộp bài.
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>Lưu Cài đặt</x-primary-button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
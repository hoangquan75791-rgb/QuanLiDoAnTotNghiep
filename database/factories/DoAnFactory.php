<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; // <-- Thêm dòng này

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoAn>
 */
class DoAnFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Định nghĩa các trường dữ liệu giả
            'ten_de_tai' => $this->faker->sentence(6), // Tạo 1 câu 6 từ
            'mo_ta' => $this->faker->paragraph(2),      // Tạo 1 đoạn văn 2 câu
            'trang_thai' => 'moi_tao',

            // Gán cho một Giảng viên giả
            'giang_vien_id' => User::factory()->create(['vai_tro' => 'giang_vien']),
            
            // Mặc định là chưa có sinh viên
            'sinh_vien_id' => null, 
        ];
    }
}

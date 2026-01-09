<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\DoAn;

class DangKyDeTaiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bài test "Happy Path":
     * Xác minh Sinh viên có thể đăng ký một đề tài CÒN TRỐNG.
     */
    public function test_sinh_vien_co_the_dang_ky_de_tai_con_trong(): void
    {
        // ----- GIAI ĐOẠN 1: SETUP (CHUẨN BỊ) -----

        // Tạo 1 Giảng viên và 1 Sinh viên
        $giangVien = User::factory()->create(['vai_tro' => 'giang_vien']);
        $sinhVien = User::factory()->create(['vai_tro' => 'sinh_vien']);

        // Tạo 1 đề tài CÒN TRỐNG (chưa có sinh_vien_id)
        $deTaiTrong = DoAn::factory()->create([
            'giang_vien_id' => $giangVien->id,
            'sinh_vien_id' => null,
            'trang_thai' => 'moi_tao',
        ]);

        // ----- GIAI ĐOẠN 2: ACTION (HÀNH ĐỘNG) -----

        // Giả lập Sinh viên này đăng nhập và gửi request POST
        $response = $this->actingAs($sinhVien)
                         ->post(route('sinhvien.dangky.store'), [
                             'de_tai_id' => $deTaiTrong->id,
                         ]);

        // ----- GIAI ĐOẠN 3: ASSERT (KIỂM TRA) -----

        // 1. Kiểm tra CSDL xem đề tài đã được gán cho sinh viên này chưa
        $this->assertDatabaseHas('do_an', [
            'id' => $deTaiTrong->id,
            'sinh_vien_id' => $sinhVien->id,
            'trang_thai' => 'da_dang_ky',
        ]);

        // 2. Kiểm tra xem đã chuyển hướng về trang đăng ký
        $response->assertRedirect(route('sinhvien.dangky.index'));

        // 3. Kiểm tra xem có thông báo thành công
        $response->assertSessionHas('success');
    }

    /**
     * Bài test "Defect Path 1":
     * Xác minh Sinh viên KHÔNG THỂ đăng ký đề tài ĐÃ CÓ NGƯỜI CHỌN.
     */
    public function test_sinh_vien_khong_the_dang_ky_de_tai_da_co_nguoi_chon(): void
    {
        // ----- GIAI ĐOẠN 1: SETUP (CHUẨN BỊ) -----

        // Tạo 1 Giảng viên, SinhVien1 (đã đăng ký), và SinhVien2 (muốn đăng ký)
        $giangVien = User::factory()->create(['vai_tro' => 'giang_vien']);
        $sinhVien1 = User::factory()->create(['vai_tro' => 'sinh_vien']);
        $sinhVien2 = User::factory()->create(['vai_tro' => 'sinh_vien']);

        // Tạo 1 đề tài ĐÃ ĐƯỢC $sinhVien1 đăng ký
        $deTaiDaChon = DoAn::factory()->create([
            'giang_vien_id' => $giangVien->id,
            'sinh_vien_id' => $sinhVien1->id, // Đã có người đăng ký
            'trang_thai' => 'da_dang_ky',
        ]);

        // ----- GIAI ĐOẠN 2: ACTION (HÀNH ĐỘNG) -----

        // Giả lập $sinhVien2 cố gắng đăng ký đề tài $deTaiDaChon
        $response = $this->actingAs($sinhVien2)
                         ->post(route('sinhvien.dangky.store'), [
                             'de_tai_id' => $deTaiDaChon->id,
                         ]);

        // ----- GIAI ĐOẠN 3: ASSERT (KIỂM TRA) -----

        // 1. Kiểm tra xem đề tài vẫn thuộc về $sinhVien1
        $this->assertDatabaseHas('do_an', [
            'id' => $deTaiDaChon->id,
            'sinh_vien_id' => $sinhVien1->id,
        ]);

        // 2. Kiểm tra xem $sinhVien2 KHÔNG được gán vào
        $this->assertDatabaseMissing('do_an', [
            'id' => $deTaiDaChon->id,
            'sinh_vien_id' => $sinhVien2->id,
        ]);

        // 3. Kiểm tra xem có thông báo lỗi
        $response->assertSessionHas('error');
    }

    /**
     * Bài test "Defect Path 2":
     * Xác minh Sinh viên ĐÃ CÓ ĐỀ TÀI không thể đăng ký thêm.
     */
    public function test_sinh_vien_da_dang_ky_khong_the_dang_ky_them(): void
    {
        // ----- GIAI ĐOẠN 1: SETUP (CHUẨN BỊ) -----
        $giangVien = User::factory()->create(['vai_tro' => 'giang_vien']);
        $sinhVien = User::factory()->create(['vai_tro' => 'sinh_vien']);

        // Tạo 2 đề tài: 1 cái $sinhVien đã đăng ký, 1 cái còn trống
        $deTaiDaDangKy = DoAn::factory()->create([
            'giang_vien_id' => $giangVien->id,
            'sinh_vien_id' => $sinhVien->id, // Đã đăng ký
            'trang_thai' => 'da_dang_ky',
        ]);
        
        $deTaiTrongKhac = DoAn::factory()->create([
            'giang_vien_id' => $giangVien->id,
            'sinh_vien_id' => null, // Còn trống
            'trang_thai' => 'moi_tao',
        ]);

        // ----- GIAI ĐOẠN 2: ACTION (HÀNH ĐỘNG) -----

        // Giả lập $sinhVien (đã có đề tài) cố gắng đăng ký 1 đề tài khác
        $response = $this->actingAs($sinhVien)
                         ->post(route('sinhvien.dangky.store'), [
                             'de_tai_id' => $deTaiTrongKhac->id,
                         ]);

        // ----- GIAI ĐOẠN 3: ASSERT (KIỂM TRA) -----

        // 1. Kiểm tra xem đề tài thứ 2 ($deTaiTrongKhac) VẪN CÒN TRỐNG
        $this->assertDatabaseHas('do_an', [
            'id' => $deTaiTrongKhac->id,
            'sinh_vien_id' => null,
        ]);

        // 2. Kiểm tra xem sinh viên vẫn đang giữ đề tài cũ
        $this->assertDatabaseHas('do_an', [
            'id' => $deTaiDaDangKy->id,
            'sinh_vien_id' => $sinhVien->id,
        ]);

        // 3. Kiểm tra xem có thông báo lỗi
        $response->assertSessionHas('error');
    }
}

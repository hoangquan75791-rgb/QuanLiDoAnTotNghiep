<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // Rất quan trọng: Tự động reset CSDL
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User; // Import Model User
use App\Models\DoAn; // Import Model DoAn

class DeTaiControllerTest extends TestCase
{
    /**
     * Trait này sẽ tự động chạy "migrate" CSDL trong bộ nhớ
     * trước mỗi bài test, đảm bảo CSDL luôn sạch.
     */
    use RefreshDatabase;

    /**
     * Bài test "Happy Path":
     * Xác minh Giảng viên đã đăng nhập có thể tạo đề tài thành công.
     */
    public function test_giang_vien_co_the_tao_de_tai_thanh_cong(): void
    {
        // ----- GIAI ĐOẠN 1: SETUP (CHUẨN BỊ) -----
        
        // Tạo một user Giảng viên giả
        $giangVien = User::factory()->create([
            'vai_tro' => 'giang_vien',
        ]);

        // Chuẩn bị dữ liệu form sẽ được gửi đi
        $deTaiData = [
            'ten_de_tai' => 'Đề tài Test Mới',
            'mo_ta' => 'Đây là mô tả cho đề tài test.',
        ];

        // ----- GIAI ĐOẠN 2: ACTION (HÀNH ĐỘNG) -----
        
        // Giả lập việc:
        // 1. Đăng nhập với tư cách là $giangVien (actingAs)
        // 2. Gửi một request POST tới route 'detai.store'
        // 3. Gửi kèm dữ liệu $deTaiData
        $response = $this->actingAs($giangVien)
                         ->post(route('detai.store'), $deTaiData);

        // ----- GIAI ĐOẠN 3: ASSERT (KIỂM TRA) -----

        // 1. Kiểm tra xem CSDL (bảng 'do_an') CÓ chứa bản ghi
        //    khớp với dữ liệu chúng ta đã gửi
        $this->assertDatabaseHas('do_an', [
            'ten_de_tai' => 'Đề tài Test Mới',
            'mo_ta' => 'Đây là mô tả cho đề tài test.',
            'giang_vien_id' => $giangVien->id, // Quan trọng: kiểm tra đúng là của GV này
            'trang_thai' => 'moi_tao'
        ]);

        // 2. Kiểm tra xem có ĐÚNG 1 bản ghi trong bảng 'do_an'
        $this->assertDatabaseCount('do_an', 1);

        // 3. Kiểm tra xem hệ thống đã chuyển hướng (redirect) về
        //    đúng trang index sau khi tạo thành công
        $response->assertRedirect(route('detai.index'));

        // 4. (Tùy chọn) Kiểm tra xem session có chứa thông báo thành công không
        $response->assertSessionHas('success', 'Đã tạo đề tài thành công!');
    }

    /**
     * Bài test "Defect Path":
     * Xác minh Sinh viên (hoặc user không có quyền) KHÔNG THỂ tạo đề tài.
     */
    public function test_sinh_vien_khong_the_tao_de_tai(): void
    {
        // ----- GIAI ĐOẠN 1: SETUP (CHUẨN BỊ) -----
        
        // Tạo một user giả với vai trò là 'sinh_vien'
        $sinhVien = User::factory()->create([
            'vai_tro' => 'sinh_vien',
        ]);

        // Chuẩn bị dữ liệu form (giống như trước)
        $deTaiData = [
            'ten_de_tai' => 'Đề tài của Sinh viên',
            'mo_ta' => 'Một nỗ lực tạo đề tài trái phép.',
        ];

        // ----- GIAI ĐOẠN 2: ACTION (HÀNH ĐỘNG) -----
        
        // Giả lập việc:
        // 1. Đăng nhập với tư cách là $sinhVien (actingAs)
        // 2. Gửi một request POST tới route 'detai.store'
        $response = $this->actingAs($sinhVien)
                         ->post(route('detai.store'), $deTaiData);

        // ----- GIAI ĐOẠN 3: ASSERT (KIỂM TRA) -----

        // 1. (Quan trọng) Kiểm tra xem hệ thống đã trả về lỗi "Forbidden" (403)
        //    (Code của chúng ta không có logic phân quyền ở Controller, 
        //     nhưng chúng ta giả định View 'navigation' sẽ không hiển thị link,
        //     và nếu user cố tình truy cập, chúng ta nên chặn. 
        //     Tạm thời, chúng ta sẽ test lỗi 403, dù code hiện tại có thể chưa làm vậy)
        
        // *** LƯU Ý: NẾU TEST NÀY FAIL (ví dụ: trả về 302 thay vì 403)
        //     ĐÓ LÀ VÌ CHÚNG TA CHƯA VIẾT MIDDLEWARE PHÂN QUYỀN. 
        //     Hiện tại, chúng ta chỉ đang ẩn link trên View.
        
        // $response->assertForbidden(); // Cách test lý tưởng (nếu có Middleware)

        // Cách test thực tế hơn với code hiện tại:
        // Controller (DeTaiController@store) không có phân quyền, 
        // nên nó sẽ LƯU đề tài, BẤT KỂ vai trò là gì. 
        // Đây là một LỖ HỔNG BẢO MẬT mà test sẽ phát hiện.
        
        // Test đúng phải là:
        $this->assertDatabaseMissing('do_an', [
            'ten_de_tai' => 'Đề tài của Sinh viên',
        ]);

        // Test của bạn CÓ THỂ SẼ FAIL ở đây, và đó là điều TỐT.
        // Nó cho thấy bạn cần thêm logic phân quyền vào hàm store()
    }
}

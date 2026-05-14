# Online Exam System

Hệ thống kiểm tra trực tuyến (PHP + MySQL), hỗ trợ ba vai trò: **quản trị viên**, **giáo viên** và **học sinh**. Phù hợp chạy trên XAMPP/WAMP hoặc bất kỳ máy chủ PHP + MariaDB/MySQL nào.

## Tính năng chính

| Vai trò | Mô tả ngắn |
|--------|------------|
| **Admin** | Quản lý giáo viên, học sinh, toàn bộ đề kiểm tra; sửa metadata đề; trang đăng nhập `login_admin.php`; cài đặt tài khoản admin (`admin/settings.php`). |
| **Giáo viên** | Tạo/sửa câu hỏi và đề thi **chỉ của mình**; xem kết quả theo đề của mình; quản lý danh sách học sinh **do chính mình thêm** (không thấy/sửa/xóa HS của GV khác). |
| **Học sinh** | Xem đề đang mở, làm bài, xem kết quả và tin nhắn. |

## Phân quyền dữ liệu

- Bảng `exm_list` có cột `teacher_id`: mỗi đề gắn với giáo viên tạo ra.
- Bảng `student` có cột `teacher_id`: học sinh do GV nào tạo thì chỉ GV đó quản lý trong giao diện giáo viên. Học sinh do admin tạo có `teacher_id` rỗng (NULL).
- Khi mở ứng dụng, `config.php` tự kiểm tra và thêm các cột trên nếu cơ sở dữ liệu cũ chưa có (không cần import lại toàn bộ SQL thủ công).

## Yêu cầu hệ thống

- PHP 7.x trở lên (khuyến nghị 8.x)
- MySQL hoặc MariaDB
- Extension `mysqli`

## Cài đặt nhanh

1. **Clone** repository vào thư mục web server (ví dụ `htdocs` của XAMPP):

   ```bash
   git clone https://github.com/SnowmanNguyen/Online_Exam_System.git
   cd Online_Exam_System
   ```

2. **Tạo database** tên `db_eval` (hoặc đổi tên trong `config.php` cho khớp).

3. **Import schema** (tùy chọn nếu DB trống):

   ```text
   db/db_eval.sql
   ```

4. Sửa **`config.php`** nếu cần: `$hostname`, `$username`, `$password`, `$database`.

5. Truy cập trang chủ đăng nhập tương ứng vai trò, ví dụ:

   - Học sinh: `login_student.php`
   - Giáo viên: `login_teacher.php`
   - Admin: `login_admin.php`

## Tài khoản mặc định (admin)

Sau lần đăng nhập admin đầu tiên, hệ thống có thể tự tạo user mặc định nếu bảng `admin` trống:

- **Username:** `admin`  
- **Password:** `admin`  

Nên đổi ngay trong **Admin → Cài đặt**.

## Cấu trúc thư mục (rút gọn)

```text
admin/           # Giao diện quản trị
students/        # Giao diện học sinh
teachers/        # Giao diện giáo viên
db/              # Script SQL mẫu
config.php       # Kết nối DB + migration nhẹ cột teacher_id
login_*.php      # Các trang đăng nhập
```

## Bảo mật & lưu ý

- Mật khẩu đang dùng **MD5** (đơn giản cho môi trường học tập). Trên môi trường thật, nên nâng cấp lên `password_hash()` / bcrypt và HTTPS.
- Dữ liệu cũ không có `teacher_id` trên đề hoặc học sinh sẽ không hiện trong panel giáo viên cho đến khi admin gán tay trong DB hoặc tạo lại bản ghi.

## Tác giả

**Snowman Nguyen** — dự án cá nhân, toàn bộ mã nguồn và tài liệu do một người phát triển và duy trì.

- GitHub: [@SnowmanNguyen](https://github.com/SnowmanNguyen)  
- Repository: [Online_Exam_System](https://github.com/SnowmanNguyen/Online_Exam_System)  
- © 2026

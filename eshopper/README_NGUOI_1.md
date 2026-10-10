# Kiều — Người 1: Đăng nhập, phân quyền và quản lý đơn hàng

## Bắt đầu ngay — không cần đợi người khác

Bộ nền đã có model, migration, Seeder, Auth và layout. **Không chạy make:model -m để tạo lại những bảng đã có.** Mở file hiện có rồi tiếp tục phần của bạn. Màn hình foundation chỉ đọc dữ liệu mẫu; mutation route hiện trả 501 để không giả báo thao tác thành công.

Dùng đơn mẫu của User A/B để làm CN06 ngay. Auth đã có để bạn rà, thử và hoàn thiện; OrderService đã có checkout/transition để cùng Anh Tú review. Thay route adminOrders/order/unfinished bằng controller nghiệp vụ của bạn theo ROUTES.md.


**Mã thành viên:** Kiều · **Họ tên:** Kiều · **MSSV/GitHub:** Chưa điền

## 1. Bạn làm chức năng gì?

Bạn làm ba phần: (1) đăng ký, đăng nhập, đăng xuất; (2) phân biệt Admin và khách mua hàng; (3) CN06 quản lý đơn. Bạn còn điều phối ghép code của nhóm và hướng dẫn cài dự án.

Ví dụ kết quả: khách tạo tài khoản và mua hàng; Admin xem đơn, xác nhận và chuyển sang giao hàng; khách chỉ xem đơn của mình. Khách nhập đường dẫn quản trị trực tiếp vẫn bị chặn.

## 2. Cần chuẩn bị gì trước khi làm?

Dùng VS Code để sửa code, terminal PowerShell để chạy Laravel, MySQL và công cụ quản lý CSDL để xem dữ liệu, trình duyệt để kiểm tra, GitHub Desktop hoặc Git để gửi code. Đọc [hướng dẫn chạy](README.md), [schema chung](SCHEMA.md) và [quy trình Git](README_GIT_WORKFLOW.md).

Nhận layout Bootstrap 4 từ Châu và schema từ Danh. Phần đơn hàng cần Order, OrderItem và dữ liệu đơn do Anh Tú tạo. Làm Auth trước, làm CN06 sau khi Anh Tú có đơn mẫu. Chưa có đơn thì dùng Seeder demo thống nhất với Anh Tú, không tự đặt tên trường khác.

Branch của bạn: `feature/tv1-auth-orders`. Tạo branch từ main mới nhất; tên branch là quy ước, chưa tự tạo trên GitHub. Các đường dẫn bên dưới tính từ thư mục Laravel `laravel-eshopper/eshopper/` đã chọn cho bộ nền này.

## 3. Những file bạn cần làm

Tên file là gợi ý thiết kế; nếu nhóm đã có file tương đương thì sửa file đó, không tạo hai hệ thống song song.

| File/thư mục | Dùng để làm gì? |
|---|---|
| `app/Http/Controllers/AuthController.php` | Xử lý đăng ký, đăng nhập, đăng xuất |
| `app/Http/Middleware/…; bootstrap/app.php` | Kiểm tra và đăng ký role |
| `database/migrations/…; database/seeders/…` | Bổ sung role và tài khoản demo |
| `app/Http/Controllers/Admin/OrderController.php` | Admin xem và xử lý đơn |
| `app/Http/Controllers/OrderController.php` | Khách xem đơn của chính mình |
| `app/Services/OrderService.php` | Phối hợp Anh Tú về trạng thái và hoàn kho |
| `resources/views/auth/, admin/orders/, orders/` | Các trang cần tạo |
| `routes/web.php; README.md; ENVIRONMENT.md` | Điều phối route và cài đặt |

## 4. Làm từng bước như thế nào?

Các bước có model/migration/seed/Auth/layout đã được làm trong bộ nền: đọc và kiểm tra file hiện có, không tạo lại. Tiếp tục bước controller/form/validation/kiểm thử còn thiếu. Tất cả thành viên dùng seed để bắt đầu cùng lúc.

### Bước 1: Chuẩn bị dự án chung

**Bạn làm:** Dự án chung đã chốt: laravel-eshopper/eshopper/. Kiểm tra PHP và Composer, cấu hình MySQL theo README gốc; chạy trang welcome trên máy mình. Ghi phiên bản thật vào ENVIRONMENT.md. Mời nhóm clone cùng repository; mỗi người cấu hình .env trên máy riêng.

**Xong bước này khi:** Cả nhóm biết thư mục nào là dự án chính và có thể mở trang Laravel.

### Bước 2: Chuẩn bị tài khoản và role

**Bạn làm:** Kiểm tra migration users hiện có trước khi tạo mới. Nếu chưa có role, tạo migration bổ sung role với mặc định user. Thống nhất chỉ có admin/user. Seeder tạo một Admin và hai User thử; mật khẩu dùng Hash::make. Đăng ký công khai luôn gán role=user tại máy chủ.

**Xong bước này khi:** Có tài khoản demo và không thể tự chọn Admin trên form đăng ký.

### Bước 3: Làm đăng ký

**Bạn làm:** Tạo AuthController và trang auth/register.blade.php. Form có tên, email, mật khẩu và xác nhận mật khẩu. Validate email duy nhất, mật khẩu confirmed; băm mật khẩu rồi lưu User. Form POST có @csrf, hiện @error và giữ old() cho tên/email; không giữ mật khẩu.

**Xong bước này khi:** Email trùng bị báo lỗi; tài khoản mới có role user.

### Bước 4: Làm đăng nhập và đăng xuất

**Bạn làm:** Trang login nhận email/password. Dùng Auth::attempt; thành công regenerate session và chuyển tới trang phù hợp role; sai thì hiện lỗi. Giới hạn thử đăng nhập bằng throttle. Logout phải dùng POST: Auth::logout, invalidate session và regenerateToken.

**Xong bước này khi:** Đăng nhập đúng vào được; sai có thông báo; logout mất quyền truy cập.

### Bước 5: Chặn truy cập sai vai trò

**Bạn làm:** Tạo middleware kiểm tra role và đăng ký alias trong bootstrap/app.php theo cấu trúc dự án. Route quản trị phải có auth và role admin; giỏ, checkout, đơn cá nhân cần auth và role user. Không chỉ ẩn nút trên giao diện: middleware phải chặn request ở máy chủ.

**Xong bước này khi:** User mở /admin/products trực tiếp cũng không vào được.

### Bước 6: Làm danh sách và chi tiết đơn

**Bạn làm:** Tạo Admin/OrderController và views admin/orders/index, show. Admin xem mọi đơn, tìm mã đơn, lọc trạng thái và phân trang. Tạo OrderController cho khách và views orders/index, show; truy vấn qua đơn của User hiện tại. Chi tiết dùng dữ liệu chụp trong order_items, không lấy giá sản phẩm mới.

**Xong bước này khi:** Admin xem được các đơn; User A không đọc được đơn User B bằng cách đổi ID.

### Bước 7: Làm chuyển trạng thái và hủy

**Bạn làm:** Dùng bảng trạng thái ở SCHEMA.md: pending → confirmed/cancelled; confirmed → shipping/cancelled; shipping → completed. User chỉ hủy pending của mình; Admin hủy pending/confirmed. Với Anh Tú thống nhất OrderService: transaction khóa đơn và biến thể theo thứ tự ID, kiểm tra trạng thái rồi hoàn kho một lần và chuyển cancelled. Gửi hủy lại không tăng kho thêm. Không xóa đơn để hủy.

**Xong bước này khi:** Đổi trạng thái hợp lệ; hủy lặp không làm sai kho; không hủy đơn shipping.

### Bước 8: Ghép và thử lại

**Bạn làm:** Review PR của từng người, ghép lần lượt phần phụ thuộc trước. Chạy route:list, migration/seeder và luồng mua hàng. Cài lại trên máy khác với database demo trống; cập nhật README bằng lệnh đã chạy thật. Ghi lỗi còn lại thay vì tự ghi hoàn thành.

**Xong bước này khi:** Có một bản source chung và hướng dẫn người khác cài lại được.

## 5. Tạo file và chạy kiểm tra bằng cách nào?

Mở terminal tại thư mục Laravel, không phải thư mục gốc chứa hai dự án. Ví dụ tạo controller/model/request:

```powershell
php artisan make:controller TenController
php artisan make:model TenModel -m
php artisan make:request TenRequest
php artisan route:list
php artisan migrate
php artisan serve
```

Thay tên mẫu bằng tên ở mục 3; chỉ chạy lệnh tạo nếu file chưa có. `-m` tạo thêm migration, không tự điền các cột hoặc quan hệ. Các file Blade tự tạo bằng VS Code trong resources/views. Tra SCHEMA.md để điền trường và FK chính xác. Migration mới cần được nhóm rà trước khi chạy; không dùng migrate:fresh trên database có dữ liệu cần giữ.

Hiểu luồng cơ bản trước khi viết: **route** nhận đường dẫn → **controller** nhận yêu cầu → **request** kiểm tra dữ liệu → **model/service** xử lý MySQL/nghiệp vụ → **view Blade** hiển thị. Validation phải ở máy chủ, không chỉ JavaScript.

## 6. Tự thử những tình huống nào?

| Mã | Tình huống | Mong đợi | Trạng thái |
|---|---|---|---|
| T01 | Đăng ký, đăng nhập đúng/sai, đăng xuất | Role user mặc định; mật khẩu băm; đăng xuất mất quyền | Chưa thử |
| T02 | User mở URL Admin và gửi yêu cầu sửa dữ liệu | Máy chủ chặn; dữ liệu không thay đổi | Chưa thử |
| T15 | Admin đổi pending tới confirmed, shipping, completed | Chỉ chuyển trạng thái hợp lệ; User thấy cập nhật | Chưa thử |
| T16 | Hủy pending; gửi lại hủy; hủy đơn đã shipping | Hoàn kho đúng một lần; đơn shipping không được hủy | Chưa thử |
| T17 | User B xem hoặc hủy đơn của User A | Không đọc hoặc sửa được đơn người khác | Chưa thử |
| T19 | Cài bộ source trên máy khác với CSDL demo trống | Migrate và seed chạy; ảnh hiển thị; luồng mua hàng chạy | Chưa thử |


Tất cả đang Chưa thử. Với mỗi ca, ghi thao tác, kết quả thực tế, ngày, người thử, commit và ảnh. Riêng thử cạnh tranh kho T14 phải thực hiện hai yêu cầu gần đồng thời; thử lần lượt không chứng minh xử lý đồng thời.

## 7. Viết báo cáo và giải thích phần mình

Bạn viết: **Mở đầu; Chương 1; mục 3.4, 4.1, 4.3, 4.4.6; README và bộ nộp**.

Mỗi chức năng trình bày theo thứ tự: mục đích → người dùng → dữ liệu nhập → cách xử lý → dữ liệu lưu → lỗi bị chặn → ảnh/kết quả kiểm thử. Chọn đoạn code quan trọng và giải thích vì sao cần, không dán toàn bộ source.

Ví dụ cách nói: “Phần em phụ trách là đăng nhập, phân quyền và quản lý đơn hàng. Em nhận dữ liệu từ …, kiểm tra …, sau đó lưu/đọc ở bảng …. Khi người dùng nhập sai hoặc không có quyền, hệ thống …. Em đã kiểm tra bằng ca …, kết quả thực tế là ….” Điền theo code và kết quả thật.

## 8. Khi nào được bàn giao cho nhóm?

- [ ] Các bước trên đã thực hiện hoặc phần chưa xong đã ghi rõ.
- [ ] Ca thử T01–T02, T15–T17, T19 có kết quả thực tế; lỗi còn lại được liệt kê.
- [ ] Code khớp schema, role, route và layout chung.
- [ ] Có `Kiều_Bao_cao.docx`, ảnh và `BAN_GIAO_Kiều.md` theo [mẫu](BAN_GIAO_MAU.md).
- [ ] Đã commit/push branch và tạo PR về main, kèm cách chạy và phụ thuộc.

Đọc [Git workflow](README_GIT_WORKFLOW.md) để gửi code. Không tự ghi Đạt khi chưa thử; không đưa .env hoặc mật khẩu thật vào Git.

## 9. Học lại kiến thức nào / nhờ AI thế nào?

Ôn MVC, route, Blade, migration, Eloquent và validation; thêm Auth/middleware cho Kiều, quan hệ/upload cho Danh/Châu, transaction/khóa/token cho Anh Tú, truy vấn/lọc/phân trang/giỏ cho Nhựt. Hai tài liệu không đối chiếu số Lab nên chưa thể gán Lab chính xác.

Nếu nhờ AI hỗ trợ, gửi README này, SCHEMA.md, composer.json và code liên quan đang có. Yêu cầu làm từng bước, giữ tên bảng/FK/quy tắc của nhóm; sau mỗi bước tự chạy và giải thích được code. File README hướng dẫn công việc, không phải bộ code hoàn chỉnh.

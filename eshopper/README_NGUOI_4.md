# Châu — Người 4: Quản lý danh mục và chuyển EShopper sang Blade

## Bắt đầu ngay — không cần đợi người khác

Bộ nền đã có model, migration, Seeder, Auth và layout. **Không chạy make:model -m để tạo lại những bảng đã có.** Mở file hiện có rồi tiếp tục phần của bạn. Màn hình foundation chỉ đọc dữ liệu mẫu; mutation route hiện trả 501 để không giả báo thao tác thành công.

Category và seed đã có; layout/store/admin, partials và asset EShopper cũng đã có. Làm CRUD danh mục, nâng giao diện từ layout nền. Đọc dữ liệu mẫu để kiểm tra chặn xóa ngay, không đợi Danh.


**Mã thành viên:** Châu · **Họ tên:** Châu · **MSSV/GitHub:** Chưa điền

## 1. Bạn làm chức năng gì?

Bạn làm CN01 quản lý danh mục và phần giao diện dùng chung. Admin thêm/sửa/xóa danh mục; các trang Laravel sử dụng header/menu/footer thống nhất từ EShopper.

Ví dụ: tạo danh mục “Áo thun”, Danh chọn danh mục này khi thêm sản phẩm; khách thấy danh mục trên menu. Danh mục còn sản phẩm không được xóa.

## 2. Cần chuẩn bị gì trước khi làm?

Dùng VS Code để sửa code, terminal PowerShell để chạy Laravel, MySQL và công cụ quản lý CSDL để xem dữ liệu, trình duyệt để kiểm tra, GitHub Desktop hoặc Git để gửi code. Đọc [hướng dẫn chạy](README.md), [schema chung](SCHEMA.md) và [quy trình Git](README_GIT_WORKFLOW.md).

Nhận dự án chung/Auth từ Kiều, chốt Category với Danh. Dùng template EShopper gốc và giữ LICENSE/credit. Bạn cung cấp khung giao diện cho mọi người; mỗi người tự nối dữ liệu chức năng của mình vào view.

Branch của bạn: `feature/tv4-categories-layout`. Tạo branch từ main mới nhất; tên branch là quy ước, chưa tự tạo trên GitHub. Các đường dẫn bên dưới tính từ thư mục Laravel `laravel-eshopper/eshopper/` đã chọn cho bộ nền này.

## 3. Những file bạn cần làm

Tên file là gợi ý thiết kế; nếu nhóm đã có file tương đương thì sửa file đó, không tạo hai hệ thống song song.

| File/thư mục | Dùng để làm gì? |
|---|---|
| `public/template/eshopper/` | CSS, JS, thư viện và ảnh template |
| `resources/views/layouts/; partials/` | Khung trang và phần chung |
| `app/Models/Category.php; database/migrations/…categories…` | Bảng và quan hệ danh mục |
| `app/Http/Controllers/Admin/CategoryController.php` | CRUD danh mục |
| `app/Http/Requests/…CategoryRequest.php` | Validation |
| `resources/views/admin/categories/` | Các màn hình danh mục |

## 4. Làm từng bước như thế nào?

Các bước có model/migration/seed/Auth/layout đã được làm trong bộ nền: đọc và kiểm tra file hiện có, không tạo lại. Tiếp tục bước controller/form/validation/kiểm thử còn thiếu. Tất cả thành viên dùng seed để bắt đầu cùng lúc.

### Bước 1: Mở template và nhận diện phần chung

**Bạn làm:** Giải nén template, mở index/shop/detail/cart/checkout HTML bằng trình duyệt hoặc VS Code Live Server. Nhận diện header, menu, footer và vùng nội dung. Lưu nguồn/phiên bản/giấy phép, không dùng mail/contact.php cho đơn hàng.

**Xong bước này khi:** Biết trang gốc nào dùng cho từng chức năng và giữ nguồn template.

### Bước 2: Đưa CSS/JS/ảnh vào Laravel

**Bạn làm:** Chép css, js, lib, img vào public/template/eshopper/. Đổi đường dẫn bằng asset(). Dùng Bootstrap 4 theo template; không nạp thêm Bootstrap 5/Tailwind vào cùng layout hoặc nạp thư viện trùng.

**Xong bước này khi:** Ảnh/CSS/JS hoạt động trong trang Laravel.

### Bước 3: Tạo layout Blade dùng chung

**Bạn làm:** Tạo layouts/store.blade.php và layouts/admin.blade.php, partials header/footer/menu. Layout đặt @yield(content), trang con dùng @extends/@section. Thử một trang trước, rồi giao layout cho Kiều/Danh/Anh Tú/Nhựt. Liên kết dùng route() sau khi route được Kiều ghép; không gọi tên route chưa tồn tại.

**Xong bước này khi:** Các trang dùng một layout, sửa header một nơi là cập nhật toàn bộ.

### Bước 4: Tạo Category và bảng danh mục

**Bạn làm:** Kiểm tra file hiện có, tạo categories với name/slug unique, description nullable. Category hasMany Product. Đồng bộ FK products.category_id với Danh. Không tạo lại migration nếu bảng đã do nhóm tạo.

**Xong bước này khi:** Danh mục liên kết thật với sản phẩm trong MySQL.

### Bước 5: Làm CRUD danh mục

**Bạn làm:** Tạo Admin/CategoryController, Store/UpdateCategoryRequest và views admin/categories. Làm index/show/create/edit; form POST/PATCH có CSRF. Validate tên/slug duy nhất, bỏ qua ID hiện tại khi sửa. Xóa bằng DELETE, chặn nếu còn product; hiện thông báo dễ hiểu.

**Xong bước này khi:** Thêm/xem/sửa/xóa được danh mục trống, danh mục có sản phẩm bị chặn xóa.

### Bước 6: Nối menu và kiểm tra giao diện

**Bạn làm:** Menu đọc danh mục thật; phối hợp Danh/Nhựt để không hiện dữ liệu ngoài phạm vi đang bán. Dùng @error/old() và thông báo thành công. Kiểm tra Shop/Detail/Cart/Checkout ở khoảng 375px, menu và nút không tràn; bỏ hoặc ẩn nút giả ngoài phạm vi như coupon/wishlist.

**Xong bước này khi:** Giao diện dùng được trên điện thoại và không có nút gây hiểu nhầm.

### Bước 7: Ghép báo cáo

**Bạn làm:** Nhận TVx_Bao_cao.docx và ảnh của từng người, ghép một Word theo khung gốc, cập nhật heading/caption/mục lục. Không viết thay kết quả chưa được thử; giữ mã CN và schema nhất quán.

**Xong bước này khi:** Có một báo cáo chung, ảnh thật và phần đóng góp rõ ràng.

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
| T03 | Thêm, xem, sửa, xóa danh mục chưa có sản phẩm | Đủ CRUD; dữ liệu MySQL và menu khớp | Chưa thử |
| T04 | Tên trùng hoặc xóa danh mục còn sản phẩm | Validation hoặc thông báo chặn xóa; giữ quan hệ | Chưa thử |
| T18 | Thử Shop, Detail, Cart, Checkout ở độ rộng khoảng 375 px | Menu, nút, lỗi form và bảng dùng được; không tràn trang | Chưa thử |


Tất cả đang Chưa thử. Với mỗi ca, ghi thao tác, kết quả thực tế, ngày, người thử, commit và ảnh. Riêng thử cạnh tranh kho T14 phải thực hiện hai yêu cầu gần đồng thời; thử lần lượt không chứng minh xử lý đồng thời.

## 7. Viết báo cáo và giải thích phần mình

Bạn viết: **Mục 2.4.1, 3.3, 4.4.1, 4.6; ghép và định dạng Word**.

Mỗi chức năng trình bày theo thứ tự: mục đích → người dùng → dữ liệu nhập → cách xử lý → dữ liệu lưu → lỗi bị chặn → ảnh/kết quả kiểm thử. Chọn đoạn code quan trọng và giải thích vì sao cần, không dán toàn bộ source.

Ví dụ cách nói: “Phần em phụ trách là quản lý danh mục và chuyển eshopper sang blade. Em nhận dữ liệu từ …, kiểm tra …, sau đó lưu/đọc ở bảng …. Khi người dùng nhập sai hoặc không có quyền, hệ thống …. Em đã kiểm tra bằng ca …, kết quả thực tế là ….” Điền theo code và kết quả thật.

## 8. Khi nào được bàn giao cho nhóm?

- [ ] Các bước trên đã thực hiện hoặc phần chưa xong đã ghi rõ.
- [ ] Ca thử T03–T04, T18 có kết quả thực tế; lỗi còn lại được liệt kê.
- [ ] Code khớp schema, role, route và layout chung.
- [ ] Có `Châu_Bao_cao.docx`, ảnh và `BAN_GIAO_Châu.md` theo [mẫu](BAN_GIAO_MAU.md).
- [ ] Đã commit/push branch và tạo PR về main, kèm cách chạy và phụ thuộc.

Đọc [Git workflow](README_GIT_WORKFLOW.md) để gửi code. Không tự ghi Đạt khi chưa thử; không đưa .env hoặc mật khẩu thật vào Git.

## 9. Học lại kiến thức nào / nhờ AI thế nào?

Ôn MVC, route, Blade, migration, Eloquent và validation; thêm Auth/middleware cho Kiều, quan hệ/upload cho Danh/Châu, transaction/khóa/token cho Anh Tú, truy vấn/lọc/phân trang/giỏ cho Nhựt. Hai tài liệu không đối chiếu số Lab nên chưa thể gán Lab chính xác.

Nếu nhờ AI hỗ trợ, gửi README này, SCHEMA.md, composer.json và code liên quan đang có. Yêu cầu làm từng bước, giữ tên bảng/FK/quy tắc của nhóm; sau mỗi bước tự chạy và giải thích được code. File README hướng dẫn công việc, không phải bộ code hoàn chỉnh.

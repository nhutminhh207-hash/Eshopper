# Nhựt — Người 5: Tìm/lọc sản phẩm, giỏ hàng và tổng hợp kiểm thử

## Bắt đầu ngay — không cần đợi người khác

Bộ nền đã có model, migration, Seeder, Auth và layout. **Không chạy make:model -m để tạo lại những bảng đã có.** Mở file hiện có rồi tiếp tục phần của bạn. Màn hình foundation chỉ đọc dữ liệu mẫu; mutation route hiện trả 501 để không giả báo thao tác thành công.

Có 15 sản phẩm, 30 biến thể và giỏ mẫu để bạn làm Shop/Cart ngay. CartService đã có add/update/remove; nối vào controller/request/view. Bộ lọc cùng biến thể và quyền giỏ cần do bạn thử.


**Mã thành viên:** Nhựt · **Họ tên:** Nhựt · **MSSV/GitHub:** Chưa điền

## 1. Bạn làm chức năng gì?

Bạn làm CN03 tìm kiếm/lọc/phân trang và CN04 giỏ hàng. Khách tìm áo, chọn đúng size/màu rồi thêm vào giỏ; sửa số lượng và xóa hàng được.

Ví dụ: cùng áo nhưng M/đen và L/trắng là hai dòng giỏ. Thêm M/đen lần nữa cộng số lượng vào dòng M/đen.

## 2. Cần chuẩn bị gì trước khi làm?

Dùng VS Code để sửa code, terminal PowerShell để chạy Laravel, MySQL và công cụ quản lý CSDL để xem dữ liệu, trình duyệt để kiểm tra, GitHub Desktop hoặc Git để gửi code. Đọc [hướng dẫn chạy](README.md), [schema chung](SCHEMA.md) và [quy trình Git](README_GIT_WORKFLOW.md).

Nhận Product/Variant/Category và seed từ Danh, layout từ Châu, Auth từ Kiều. Gửi CartItem và hợp đồng khóa giỏ cho Anh Tú để đặt hàng; không đổi tên variant_id giữa chừng.

Branch của bạn: `feature/tv5-shop-cart`. Tạo branch từ main mới nhất; tên branch là quy ước, chưa tự tạo trên GitHub. Các đường dẫn bên dưới tính từ thư mục Laravel `laravel-eshopper/eshopper/` đã chọn cho bộ nền này.

## 3. Những file bạn cần làm

Tên file là gợi ý thiết kế; nếu nhóm đã có file tương đương thì sửa file đó, không tạo hai hệ thống song song.

| File/thư mục | Dùng để làm gì? |
|---|---|
| `app/Http/Controllers/StorefrontController.php` | Danh sách, chi tiết, tìm/lọc |
| `app/Http/Requests/ShopFilterRequest.php` | Kiểm tra bộ lọc |
| `resources/views/shop/index.blade.php; show.blade.php` | Shop và detail |
| `app/Models/CartItem.php; database/migrations/…cart_items…` | Giỏ theo biến thể |
| `app/Http/Controllers/CartController.php; …CartRequest.php` | Thêm/sửa/xóa và validation |
| `resources/views/cart/index.blade.php` | Hiển thị giỏ và tổng tiền |
| `KIEM_THU.md; bảng kết quả tổng hợp` | Tổng hợp kết quả thật |

## 4. Làm từng bước như thế nào?

Các bước có model/migration/seed/Auth/layout đã được làm trong bộ nền: đọc và kiểm tra file hiện có, không tạo lại. Tiếp tục bước controller/form/validation/kiểm thử còn thiếu. Tất cả thành viên dùng seed để bắt đầu cùng lúc.

### Bước 1: Làm danh sách và chi tiết sản phẩm

**Bạn làm:** Tạo StorefrontController, shop/index và shop/show. Lấy sản phẩm đang bán từ MySQL cùng biến thể đang bán. Chi tiết cho chọn size/màu thật, hiện giá/kho của biến thể đã chọn. Giá danh sách có thể lấy thấp nhất và ghi “Từ”.

**Xong bước này khi:** Khách xem dữ liệu thật, không phải sản phẩm demo tĩnh.

### Bước 2: Làm tìm và lọc

**Bạn làm:** Form GET nhận từ khóa, danh mục, khoảng giá, size, màu; validate qua ShopFilterRequest. Dùng whereHas variants với tất cả điều kiện size/màu/giá trong cùng một truy vấn quan hệ. Nếu sản phẩm chỉ có M/đỏ và L/đen, lọc M/đen phải không trả sản phẩm đó.

**Xong bước này khi:** Kết hợp các bộ lọc trả đúng biến thể, không ghép điều kiện từ hai biến thể khác nhau.

### Bước 3: Làm phân trang

**Bạn làm:** Dùng paginate(12)->withQueryString(); hiển thị links() với kiểu Bootstrap 4 của dự án. Dùng hơn 12 sản phẩm seed để thử. Giữ query khi chuyển trang và hiện thông báo khi không có kết quả.

**Xong bước này khi:** Trang 2 vẫn giữ từ khóa, size/màu và khoảng giá đã chọn.

### Bước 4: Tạo bảng giỏ

**Bạn làm:** Tạo CartItem và cart_items với user_id, variant_id, quantity; unique user_id+variant_id. Khai báo belongsTo User và ProductVariant với FK variant_id. Giỏ yêu cầu role user, không dùng cho Admin.

**Xong bước này khi:** Giỏ nằm trong MySQL, mỗi dòng ứng với đúng biến thể.

### Bước 5: Làm thêm vào giỏ

**Bạn làm:** Nhận variant_id và quantity, validate quantity nguyên ít nhất 1. Trong transaction khóa User cùng cách Anh Tú dùng; kiểm tra product/variant đang bán và tổng số lượng sau cộng không vượt stock. Lấy user_id từ người đăng nhập; cùng biến thể thì cộng dồn, khác biến thể thì thêm dòng.

**Xong bước này khi:** Thêm hai lần cộng đúng và không vượt kho.

### Bước 6: Làm xem/sửa/xóa giỏ

**Bạn làm:** Tạo CartController và cart/index. Truy vấn giỏ qua User hiện tại, không lấy CartItem theo ID rồi sửa cho mọi tài khoản. Sửa/xóa dùng POST/PATCH/DELETE, CSRF và cùng khóa User. Tính giá/tổng ở máy chủ, không tin giá gửi từ trình duyệt. Giỏ chưa giữ chỗ kho, nên checkout phải kiểm tra lại.

**Xong bước này khi:** User B không sửa giỏ User A; số lượng 0/âm/vượt kho bị từ chối.

### Bước 7: Bàn giao checkout và tổng hợp thử

**Bạn làm:** Nút đặt hàng dẫn tới route checkout của Anh Tú. Gửi model/quan hệ giỏ và quy tắc khóa. Thu kết quả T01–T19 từ từng người, ghi thực tế/Đạt/Không đạt, ngày/người/commit/ảnh; viết Chương 5 và tổng hợp slide từ phần thật đã hoàn thành.

**Xong bước này khi:** Anh Tú đọc đúng giỏ; nhóm có bảng kiểm thử và slide chung.

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
| T08 | Tìm tên; kết hợp danh mục giá size màu; không có kết quả | Kết quả đúng; size và màu phải khớp cùng biến thể | Chưa thử |
| T09 | Seed hơn 12 sản phẩm; lọc rồi chuyển trang | Có phân trang thật; giữ mọi tham số lọc | Chưa thử |
| T10 | Thêm cùng biến thể hai lần; thêm size khác; sửa và xóa | Cộng dồn cùng biến thể; tách size khác; tổng tiền đúng | Chưa thử |
| T11 | Số lượng 0, âm, vượt kho; đổi ID dòng giỏ của User khác | Từ chối; không sửa giỏ người khác | Chưa thử |


Tất cả đang Chưa thử. Với mỗi ca, ghi thao tác, kết quả thực tế, ngày, người thử, commit và ảnh. Riêng thử cạnh tranh kho T14 phải thực hiện hai yêu cầu gần đồng thời; thử lần lượt không chứng minh xử lý đồng thời.

## 7. Viết báo cáo và giải thích phần mình

Bạn viết: **Mục 2.4.3, 2.4.4, 4.4.3, 4.4.4, 4.5; Chương 5 và slide**.

Mỗi chức năng trình bày theo thứ tự: mục đích → người dùng → dữ liệu nhập → cách xử lý → dữ liệu lưu → lỗi bị chặn → ảnh/kết quả kiểm thử. Chọn đoạn code quan trọng và giải thích vì sao cần, không dán toàn bộ source.

Ví dụ cách nói: “Phần em phụ trách là tìm/lọc sản phẩm, giỏ hàng và tổng hợp kiểm thử. Em nhận dữ liệu từ …, kiểm tra …, sau đó lưu/đọc ở bảng …. Khi người dùng nhập sai hoặc không có quyền, hệ thống …. Em đã kiểm tra bằng ca …, kết quả thực tế là ….” Điền theo code và kết quả thật.

## 8. Khi nào được bàn giao cho nhóm?

- [ ] Các bước trên đã thực hiện hoặc phần chưa xong đã ghi rõ.
- [ ] Ca thử T08–T11 có kết quả thực tế; lỗi còn lại được liệt kê.
- [ ] Code khớp schema, role, route và layout chung.
- [ ] Có `Nhựt_Bao_cao.docx`, ảnh và `BAN_GIAO_Nhựt.md` theo [mẫu](BAN_GIAO_MAU.md).
- [ ] Đã commit/push branch và tạo PR về main, kèm cách chạy và phụ thuộc.

Đọc [Git workflow](README_GIT_WORKFLOW.md) để gửi code. Không tự ghi Đạt khi chưa thử; không đưa .env hoặc mật khẩu thật vào Git.

## 9. Học lại kiến thức nào / nhờ AI thế nào?

Ôn MVC, route, Blade, migration, Eloquent và validation; thêm Auth/middleware cho Kiều, quan hệ/upload cho Danh/Châu, transaction/khóa/token cho Anh Tú, truy vấn/lọc/phân trang/giỏ cho Nhựt. Hai tài liệu không đối chiếu số Lab nên chưa thể gán Lab chính xác.

Nếu nhờ AI hỗ trợ, gửi README này, SCHEMA.md, composer.json và code liên quan đang có. Yêu cầu làm từng bước, giữ tên bảng/FK/quy tắc của nhóm; sau mỗi bước tự chạy và giải thích được code. File README hướng dẫn công việc, không phải bộ code hoàn chỉnh.

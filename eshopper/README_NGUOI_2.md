# Danh — Người 2: Quản lý sản phẩm, size/màu và thiết kế CSDL

## Bắt đầu ngay — không cần đợi người khác

Bộ nền đã có model, migration, Seeder, Auth và layout. **Không chạy make:model -m để tạo lại những bảng đã có.** Mở file hiện có rồi tiếp tục phần của bạn. Màn hình foundation chỉ đọc dữ liệu mẫu; mutation route hiện trả 501 để không giả báo thao tác thành công.

Danh mục và 15 sản phẩm/30 biến thể đã có trong seed. Làm CRUD từ dữ liệu này; model và schema đã được tạo. Sau khi sửa sản phẩm, giữ hợp đồng variants(), SKU, giá và kho để Nhựt/Anh Tú dùng.


**Mã thành viên:** Danh · **Họ tên:** Danh · **MSSV/GitHub:** Chưa điền

## 1. Bạn làm chức năng gì?

Bạn làm CN02: Admin thêm, xem, sửa, xóa sản phẩm; mỗi sản phẩm có nhiều biến thể size/màu, giá và kho riêng. Bạn tổng hợp thiết kế CSDL để phần giỏ và đặt hàng dùng đúng dữ liệu.

Ví dụ: “Áo thun A” có M/đen giá 150.000đ kho 10 và L/trắng giá 160.000đ kho 5. Đây là hai biến thể của cùng sản phẩm.

## 2. Cần chuẩn bị gì trước khi làm?

Dùng VS Code để sửa code, terminal PowerShell để chạy Laravel, MySQL và công cụ quản lý CSDL để xem dữ liệu, trình duyệt để kiểm tra, GitHub Desktop hoặc Git để gửi code. Đọc [hướng dẫn chạy](README.md), [schema chung](SCHEMA.md) và [quy trình Git](README_GIT_WORKFLOW.md).

Nhận danh mục từ Châu, Auth/quyền Admin từ Kiều và layout từ Châu. Chốt schema với mọi người trước khi viết migration. Bạn điều phối thiết kế, nhưng chủ module tự viết bảng của mình; tránh tạo hai migration cùng tạo một bảng.

Branch của bạn: `feature/tv2-products`. Tạo branch từ main mới nhất; tên branch là quy ước, chưa tự tạo trên GitHub. Các đường dẫn bên dưới tính từ thư mục Laravel `laravel-eshopper/eshopper/` đã chọn cho bộ nền này.

## 3. Những file bạn cần làm

Tên file là gợi ý thiết kế; nếu nhóm đã có file tương đương thì sửa file đó, không tạo hai hệ thống song song.

| File/thư mục | Dùng để làm gì? |
|---|---|
| `app/Models/Product.php; ProductVariant.php` | Quan hệ sản phẩm và biến thể |
| `database/migrations/…; database/seeders/…` | Bảng, FK, unique và dữ liệu demo |
| `app/Http/Controllers/Admin/ProductController.php` | CRUD sản phẩm |
| `app/Http/Requests/…ProductRequest.php` | Validation form |
| `resources/views/admin/products/` | Danh sách, chi tiết và form |
| `SCHEMA.md; design/ERD.drawio` | Schema và sơ đồ CSDL |

## 4. Làm từng bước như thế nào?

Các bước có model/migration/seed/Auth/layout đã được làm trong bộ nền: đọc và kiểm tra file hiện có, không tạo lại. Tiếp tục bước controller/form/validation/kiểm thử còn thiếu. Tất cả thành viên dùng seed để bắt đầu cùng lúc.

### Bước 1: Chốt bảy bảng và quan hệ

**Bạn làm:** Đọc SCHEMA.md, vẽ ERD gồm users, categories, products, product_variants, cart_items, orders, order_items. Chốt variant_id cho giỏ/chi tiết đơn, giá VND nguyên đồng và tên trạng thái. Rà khóa ngoại và thứ tự migration cùng Kiều/Anh Tú/Châu/Nhựt.

**Xong bước này khi:** Mọi người dùng chung tên bảng, trường và quan hệ.

### Bước 2: Tạo Product và ProductVariant

**Bạn làm:** Kiểm tra model/migration đã tồn tại trước. Tạo products với category_id; product_variants với product_id, sku, size, color, price, stock, is_active theo schema. Thêm FK, unique sku và unique product_id+size+color. Khai báo Product belongsTo Category/hasMany ProductVariant; ProductVariant belongsTo Product.

**Xong bước này khi:** Một sản phẩm có nhiều size/màu và FK thật trong MySQL.

### Bước 3: Làm danh sách sản phẩm Admin

**Bạn làm:** Tạo Admin/ProductController và view admin/products/index. Truy vấn kèm category/variants để hiển thị tên, danh mục, giá, kho và trạng thái; thêm tìm kiếm/phân trang. Nhờ Kiều ghép route dưới middleware Admin.

**Xong bước này khi:** Admin xem dữ liệu thật thay vì danh sách HTML demo.

### Bước 4: Làm thêm mới

**Bạn làm:** Tạo form nhập tên, danh mục, mô tả, ảnh và các dòng size/màu/SKU/giá/kho. ProductRequest validate danh mục tồn tại, SKU duy nhất, size/màu không trùng, giá dương, kho nguyên không âm và file ảnh đúng loại/dung lượng quy định. Sau khi hợp lệ, lưu product và variants trong một transaction.

**Xong bước này khi:** Thêm một sản phẩm có ít nhất hai biến thể; nhập sai hiện lỗi rõ trường.

### Bước 5: Làm sửa và upload ảnh

**Bạn làm:** Nạp dữ liệu thật vào form; dùng PATCH/PUT và @method, @csrf. Lưu ảnh qua storage disk public, ghi image_path, hiển thị qua liên kết storage đã tạo. Khi sửa SKU, unique phải bỏ qua đúng ID biến thể hiện tại. Rà biến thể bị bỏ khỏi form: nếu có giỏ/đơn tham chiếu thì chặn xóa hoặc chuyển ẩn.

**Xong bước này khi:** Sửa được sản phẩm, ảnh và kho mà không mất dữ liệu đang được tham chiếu.

### Bước 6: Làm xóa hoặc ẩn đúng quy tắc

**Bạn làm:** Sản phẩm thử chưa có giỏ/đơn tham chiếu: xóa biến thể rồi sản phẩm trong transaction. Sản phẩm đã được tham chiếu: giữ lịch sử, thông báo chặn xóa và cho ẩn is_active=false. Không cascade xóa order_items.

**Xong bước này khi:** Có CRUD thật trên dữ liệu thử; đơn cũ vẫn giữ thông tin.

### Bước 7: Cấp dữ liệu cho nhóm

**Bạn làm:** Seeder tạo hơn 12 sản phẩm để Nhựt thử phân trang; có biến thể kho 0, 1 và nhiều. Gửi Nhựt quan hệ và cách lấy biến thể đang bán, gửi Anh Tú cấu trúc giá/kho. Commit ERD và schema nếu có thay đổi.

**Xong bước này khi:** Nhựt tìm/giỏ và Anh Tú checkout dùng được dữ liệu của bạn.

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
| T05 | CRUD sản phẩm và hai biến thể khác size màu | Đủ CRUD; từng biến thể có SKU, giá, kho riêng | Chưa thử |
| T06 | SKU trùng, giá âm, kho âm, size màu trùng, category_id sai | Từ chối ở máy chủ và hiện lỗi đúng trường | Chưa thử |
| T07 | Xóa sản phẩm chưa phát sinh giỏ hoặc đơn; thử xóa sản phẩm có đơn | Xóa được bản thử chưa có tham chiếu; bản có đơn phải giữ và ẩn | Chưa thử |


Tất cả đang Chưa thử. Với mỗi ca, ghi thao tác, kết quả thực tế, ngày, người thử, commit và ảnh. Riêng thử cạnh tranh kho T14 phải thực hiện hai yêu cầu gần đồng thời; thử lần lượt không chứng minh xử lý đồng thời.

## 7. Viết báo cáo và giải thích phần mình

Bạn viết: **Mục 2.4.2, 3.2, 4.2, 4.4.2; ERD và từ điển dữ liệu**.

Mỗi chức năng trình bày theo thứ tự: mục đích → người dùng → dữ liệu nhập → cách xử lý → dữ liệu lưu → lỗi bị chặn → ảnh/kết quả kiểm thử. Chọn đoạn code quan trọng và giải thích vì sao cần, không dán toàn bộ source.

Ví dụ cách nói: “Phần em phụ trách là quản lý sản phẩm, size/màu và thiết kế csdl. Em nhận dữ liệu từ …, kiểm tra …, sau đó lưu/đọc ở bảng …. Khi người dùng nhập sai hoặc không có quyền, hệ thống …. Em đã kiểm tra bằng ca …, kết quả thực tế là ….” Điền theo code và kết quả thật.

## 8. Khi nào được bàn giao cho nhóm?

- [ ] Các bước trên đã thực hiện hoặc phần chưa xong đã ghi rõ.
- [ ] Ca thử T05–T07 có kết quả thực tế; lỗi còn lại được liệt kê.
- [ ] Code khớp schema, role, route và layout chung.
- [ ] Có `Danh_Bao_cao.docx`, ảnh và `BAN_GIAO_Danh.md` theo [mẫu](BAN_GIAO_MAU.md).
- [ ] Đã commit/push branch và tạo PR về main, kèm cách chạy và phụ thuộc.

Đọc [Git workflow](README_GIT_WORKFLOW.md) để gửi code. Không tự ghi Đạt khi chưa thử; không đưa .env hoặc mật khẩu thật vào Git.

## 9. Học lại kiến thức nào / nhờ AI thế nào?

Ôn MVC, route, Blade, migration, Eloquent và validation; thêm Auth/middleware cho Kiều, quan hệ/upload cho Danh/Châu, transaction/khóa/token cho Anh Tú, truy vấn/lọc/phân trang/giỏ cho Nhựt. Hai tài liệu không đối chiếu số Lab nên chưa thể gán Lab chính xác.

Nếu nhờ AI hỗ trợ, gửi README này, SCHEMA.md, composer.json và code liên quan đang có. Yêu cầu làm từng bước, giữ tên bảng/FK/quy tắc của nhóm; sau mỗi bước tự chạy và giải thích được code. File README hướng dẫn công việc, không phải bộ code hoàn chỉnh.

# Anh Tú — Người 3: Đặt hàng COD và xử lý tồn kho

## Bắt đầu ngay — không cần đợi người khác

Bộ nền đã có model, migration, Seeder, Auth và layout. **Không chạy make:model -m để tạo lại những bảng đã có.** Mở file hiện có rồi tiếp tục phần của bạn. Màn hình foundation chỉ đọc dữ liệu mẫu; mutation route hiện trả 501 để không giả báo thao tác thành công.

Giỏ mẫu của hai User có sẵn. Dùng OrderService::checkout và CheckoutRequest đã cung cấp; nhiệm vụ của bạn là GET/POST checkout, session token/giá và giao diện/kiểm thử. Đọc CONTRACTS.md trước khi nối service, không nhận quotedPrices từ form.


**Mã thành viên:** Anh Tú · **Họ tên:** Anh Tú · **MSSV/GitHub:** Chưa điền

## 1. Bạn làm chức năng gì?

Bạn làm CN05: biến giỏ hàng thành đơn hàng thật. Khách nhập người nhận, số điện thoại, địa chỉ rồi đặt COD. Hệ thống lưu đơn, lưu từng dòng hàng, trừ kho và xóa giỏ sau thành công.

Ví dụ: giỏ có hai áo M/đen; đặt thành công tạo một đơn pending, kho biến thể giảm 2. Bấm gửi lại cùng lượt đặt không tạo đơn thứ hai.

## 2. Cần chuẩn bị gì trước khi làm?

Dùng VS Code để sửa code, terminal PowerShell để chạy Laravel, MySQL và công cụ quản lý CSDL để xem dữ liệu, trình duyệt để kiểm tra, GitHub Desktop hoặc Git để gửi code. Đọc [hướng dẫn chạy](README.md), [schema chung](SCHEMA.md) và [quy trình Git](README_GIT_WORKFLOW.md).

Nhận giỏ CartItem và luồng sửa giỏ từ Nhựt, ProductVariant từ Danh, User/Auth từ Kiều, layout từ Châu. Thống nhất với Kiều về OrderService, trạng thái và hủy/hoàn kho. Chưa có giỏ thật thì chỉ chuẩn bị form/service, không coi checkout đã hoàn thành.

Branch của bạn: `feature/tv3-checkout`. Tạo branch từ main mới nhất; tên branch là quy ước, chưa tự tạo trên GitHub. Các đường dẫn bên dưới tính từ thư mục Laravel `laravel-eshopper/eshopper/` đã chọn cho bộ nền này.

## 3. Những file bạn cần làm

Tên file là gợi ý thiết kế; nếu nhóm đã có file tương đương thì sửa file đó, không tạo hai hệ thống song song.

| File/thư mục | Dùng để làm gì? |
|---|---|
| `app/Models/Order.php; OrderItem.php` | Quan hệ đơn và dòng hàng |
| `database/migrations/…orders…; …order_items…` | Schema và token unique |
| `app/Http/Controllers/CheckoutController.php` | Hiển thị form và nhận yêu cầu |
| `app/Http/Requests/CheckoutRequest.php` | Validation giao hàng |
| `app/Services/CheckoutService.php hoặc OrderService.php` | Transaction, token và tồn kho; chốt một thiết kế với Kiều |
| `resources/views/checkout/index.blade.php` | Form và tóm tắt giỏ |
| `design/UseCase.drawio` | Use Case chung |

## 4. Làm từng bước như thế nào?

Các bước có model/migration/seed/Auth/layout đã được làm trong bộ nền: đọc và kiểm tra file hiện có, không tạo lại. Tiếp tục bước controller/form/validation/kiểm thử còn thiếu. Tất cả thành viên dùng seed để bắt đầu cùng lúc.

### Bước 1: Tạo bảng và model đơn

**Bạn làm:** Tạo orders và order_items đúng SCHEMA.md, không trùng migration người khác. orders có user_id, checkout_token unique, thông tin giao hàng, cod, pending và total_amount. order_items lưu bản chụp tên sản phẩm, SKU, size, màu, giá, số lượng và line_total. Thêm FK và các quan hệ Order/OrderItem.

**Xong bước này khi:** Đơn lưu được dữ liệu độc lập với giá/tên sản phẩm thay đổi về sau.

### Bước 2: Làm trang checkout

**Bạn làm:** GET checkout lấy giỏ User hiện tại, kiểm tra không rỗng, tính tổng tại máy chủ và tạo token cho lượt đặt. Hiển thị hàng, đúng size/màu/giá, phí giao hàng 0 và COD. Form có @csrf, người nhận, phone, address; giữ token khi form lỗi.

**Xong bước này khi:** Trang hiện đúng giỏ người đang đăng nhập và có đủ thông tin giao hàng.

### Bước 3: Kiểm tra form

**Bạn làm:** CheckoutRequest validate người nhận, phone lưu chuỗi để giữ số 0 đầu, địa chỉ và payment_method chỉ cod. Không dùng user_id, giá, tổng tiền từ trình duyệt để quyết định đơn. Token phải thuộc lượt checkout máy chủ của User; token trùng đơn người khác không được trả thông tin.

**Xong bước này khi:** Thiếu địa chỉ hoặc sửa giá gửi lên không tạo đơn sai.

### Bước 4: Viết xử lý đặt hàng nguyên khối

**Bạn làm:** Trong DB::transaction: khóa dòng User → tìm đơn theo token và chủ sở hữu → đọc lại giỏ → khóa biến thể theo thứ tự ID bằng lockForUpdate → kiểm tra sản phẩm/biến thể đang bán, giá mới và đủ kho → tạo order/order_items → trừ kho → xóa giỏ. Nhựt sửa giỏ cũng phải khóa cùng User. Nếu giá đổi so với bản máy chủ ở lúc hiển thị checkout, yêu cầu khách xem và xác nhận lại giá mới trước khi tạo đơn.

**Xong bước này khi:** Hoặc toàn bộ thao tác thành công, hoặc tất cả rollback; không có đơn dở/kho âm.

### Bước 5: Chống gửi lặp và xử lý lỗi

**Bạn làm:** Nếu cùng checkout_token đã tạo đơn của đúng User, trả lại đơn đó, không trừ kho thêm. Giữ token khi validation/đặt thất bại, đổi khi bắt đầu lượt checkout mới. Lỗi kho hoặc dữ liệu: hiện thông báo, giữ giỏ và không để lại đơn dở. Có thể khóa nút giao diện nhưng vẫn cần unique và transaction ở máy chủ.

**Xong bước này khi:** Bấm hai lần không thành hai đơn; lỗi vẫn có thể sửa và thử lại.

### Bước 6: Bàn giao cho quản lý đơn

**Bạn làm:** Sau thành công chuyển tới chi tiết đơn cá nhân do Kiều cung cấp. Gửi cấu trúc Order/OrderItem và quy tắc trừ kho một lần ở pending. Vẽ Use Case đặt hàng và tổng hợp Use Case từ các chủ module.

**Xong bước này khi:** Kiều tiếp tục xác nhận/giao/hủy đơn mà không trừ kho lần hai.

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
| T12 | Đặt giỏ hợp lệ có nhiều biến thể | Một đơn và đủ dòng; trừ đúng kho; giỏ được xóa sau thành công | Chưa thử |
| T13 | Đổi giá/tổng tiền trong dữ liệu gửi; thiếu thông tin giao hàng | Giá lấy từ CSDL; dữ liệu sai bị chặn; không tạo đơn dở | Chưa thử |
| T14 | Hai User cùng đặt biến thể chỉ còn 1; gửi lại cùng token | Tối đa một lượt mua được; không âm kho; không tạo đơn trùng | Chưa thử |


Tất cả đang Chưa thử. Với mỗi ca, ghi thao tác, kết quả thực tế, ngày, người thử, commit và ảnh. Riêng thử cạnh tranh kho T14 phải thực hiện hai yêu cầu gần đồng thời; thử lần lượt không chứng minh xử lý đồng thời.

## 7. Viết báo cáo và giải thích phần mình

Bạn viết: **Mục 2.3, 2.4.5, 4.4.5; Use Case và kiểm thử đặt hàng**.

Mỗi chức năng trình bày theo thứ tự: mục đích → người dùng → dữ liệu nhập → cách xử lý → dữ liệu lưu → lỗi bị chặn → ảnh/kết quả kiểm thử. Chọn đoạn code quan trọng và giải thích vì sao cần, không dán toàn bộ source.

Ví dụ cách nói: “Phần em phụ trách là đặt hàng cod và xử lý tồn kho. Em nhận dữ liệu từ …, kiểm tra …, sau đó lưu/đọc ở bảng …. Khi người dùng nhập sai hoặc không có quyền, hệ thống …. Em đã kiểm tra bằng ca …, kết quả thực tế là ….” Điền theo code và kết quả thật.

## 8. Khi nào được bàn giao cho nhóm?

- [ ] Các bước trên đã thực hiện hoặc phần chưa xong đã ghi rõ.
- [ ] Ca thử T12–T14 có kết quả thực tế; lỗi còn lại được liệt kê.
- [ ] Code khớp schema, role, route và layout chung.
- [ ] Có `Anh Tú_Bao_cao.docx`, ảnh và `BAN_GIAO_Anh Tú.md` theo [mẫu](BAN_GIAO_MAU.md).
- [ ] Đã commit/push branch và tạo PR về main, kèm cách chạy và phụ thuộc.

Đọc [Git workflow](README_GIT_WORKFLOW.md) để gửi code. Không tự ghi Đạt khi chưa thử; không đưa .env hoặc mật khẩu thật vào Git.

## 9. Học lại kiến thức nào / nhờ AI thế nào?

Ôn MVC, route, Blade, migration, Eloquent và validation; thêm Auth/middleware cho Kiều, quan hệ/upload cho Danh/Châu, transaction/khóa/token cho Anh Tú, truy vấn/lọc/phân trang/giỏ cho Nhựt. Hai tài liệu không đối chiếu số Lab nên chưa thể gán Lab chính xác.

Nếu nhờ AI hỗ trợ, gửi README này, SCHEMA.md, composer.json và code liên quan đang có. Yêu cầu làm từng bước, giữ tên bảng/FK/quy tắc của nhóm; sau mỗi bước tự chạy và giải thích được code. File README hướng dẫn công việc, không phải bộ code hoàn chỉnh.

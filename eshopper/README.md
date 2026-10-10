# Fashion Shop — Bộ nền nhóm 5 người

**Dự án Laravel chính:** eshopper. Asset giao diện đang dùng nằm tại eshopper/public/template/eshopper/. Không tạo thêm một dự án Laravel riêng cho từng người.

## Có gì trong bộ nền?

- Bảy bảng nghiệp vụ + role users; model và quan hệ cố định.
- Seed: 1 Admin, 2 User, 3 danh mục, 15 sản phẩm, 30 biến thể, 4 dòng giỏ và 2 đơn pending với snapshot; đơn mẫu đã trừ kho một lần.
- Auth đăng ký/đăng nhập/đăng xuất và middleware admin/user.
- Layout Blade, asset EShopper local, trang nền đọc dữ liệu mẫu, Shop phân trang.
- CartService và OrderService dùng chung; CheckoutRequest; 8 test nền và script verify.
- Route/field/service contract, phân công và lịch 7 ngày.

Đây là bộ nền để phát triển song song, **chưa hoàn thành CN01–CN06**. Các mutation nghiệp vụ chưa nối controller trả HTTP 501, không giả báo thành công. Chưa chạy PHP/MySQL ở môi trường tạo file; cần nhóm thực hiện verify trước khi ghi đã chạy được.

## Ai làm gì?

| Người | Phần chính | Đọc |
|---|---|---|
| Kiều | Auth, CN06, tích hợp | [Người 1](README_NGUOI_1.md) |
| Danh | CN02, CSDL | [Người 2](README_NGUOI_2.md) |
| Anh Tú | CN05, Use Case | [Người 3](README_NGUOI_3.md) |
| Châu | CN01, layout/Word | [Người 4](README_NGUOI_4.md) |
| Nhựt | CN03/CN04, test/slide | [Người 5](README_NGUOI_5.md) |

Mỗi người đã có dữ liệu mẫu của phần phụ thuộc để làm ngay. Không tạo lại model/migration có sẵn.

## Cài Windows / PowerShell

Clone hoặc tải ZIP repository, mở thư mục gốc trong VS Code. Trong Laragon bấm Start All để chạy MySQL; mở Terminal có PHP/Composer. Nếu tải ZIP, giải nén trước. Terminal:

```powershell
cd eshopper
php -v
composer install
if (!(Test-Path .env)) { Copy-Item .env.example .env }
```

Chỉnh DB_USERNAME/DB_PASSWORD trong .env theo MySQL trên máy mình. Laragon mặc định thường dùng root và mật khẩu trống; dùng thông tin thực tế của bạn. DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306. APP_ENV=local. Không chép .env cho người khác, không commit .env. Nếu đã có .env cần giữ, không chạy lệnh chép đè.

### Nhập database chung một lần

Dùng HeidiSQL trong Laragon, kết nối MySQL trên máy mình → File → Load SQL file → chọn `eshopper/database/fashion_shop.sql` → chạy toàn bộ script (F9). File SQL có CREATE DATABASE, USE, bảng và dữ liệu mẫu nên không phải tạo từng bảng bằng tay.

Chỉ import vào máy chưa có database này hoặc database mới trống. File có INSERT với ID cố định; import lại vào database đã có dữ liệu có thể gây lỗi trùng ID. Không xóa database đang có bài làm để import lại.

Sau import: Refresh danh sách database, kiểm tra `fashion_shop` và các bảng. Mỗi người có bản database riêng, không kết nối vào máy nhóm trưởng; nhóm trưởng tắt máy vẫn làm được. Không dùng thư mục `mysql-8.4` được chép từ máy khác làm Data Directory. Giữ Data Directory của Laragon đang hoạt động; file `.sql` là bản trao đổi dữ liệu.

### Mở website

```powershell
php artisan key:generate
php artisan optimize:clear
php artisan storage:link
php artisan serve
```

Chỉ chạy key:generate khi APP_KEY còn trống; nếu đã có key thì giữ lại. Mở http://127.0.0.1:8000. Giữ terminal serve đang chạy; lần sau bật MySQL rồi chạy lại php artisan serve từ eshopper/. Các trang nền không dùng @vite nên không cần npm cho nền này. Cài composer theo lock, không tự composer update toàn bộ.

Sau khi đã import SQL, không chạy migrate --seed để khởi tạo lần nữa. Nếu không dùng SQL, có thể tạo database trống rồi chạy `php artisan migrate --seed` như một cách khởi tạo thay thế; chọn một cách cho mỗi máy. Khi nhóm thêm migration mới, pull code và chạy `php artisan migrate`; không dùng migrate:fresh trên dữ liệu cần giữ. Dữ liệu chỉnh trên máy một người không tự truyền sang máy khác qua Git. Thay đổi cấu trúc dùng migration; dữ liệu mẫu mới dùng seeder đã thống nhất hoặc SQL cập nhật có hướng dẫn riêng.

## Tài khoản demo local

| Role | Email | Mật khẩu demo |
|---|---|---|
| Admin | admin@example.test | DemoShop2026! |
| User A | usera@example.test | DemoShop2026! |
| User B | userb@example.test | DemoShop2026! |

Đây là dữ liệu thử, không dùng cho triển khai public. Seeder chỉ chạy local/testing, không reset kho khi chạy lại. Dữ liệu demo mặc định chỉ được tạo lần đầu; không tự ghi đè tài khoản trùng email đã tồn tại. Nếu cần reset, dùng database demo riêng, không database có dữ liệu cần giữ.

## Đọc trước khi viết module

1. [Checklist nền](BAT_DAU_CUNG_NHAU.md).
2. [Schema](SCHEMA.md), [phạm vi](SCOPE.md), [môi trường](ENVIRONMENT.md).
3. [Route](ROUTES.md), [hợp đồng dữ liệu/service](CONTRACTS.md).
4. README của mình và [lịch 7 ngày](LO_TRINH.md).
5. [Git workflow](README_GIT_WORKFLOW.md), [bàn giao](BAN_GIAO_MAU.md), [kiểm thử](KIEM_THU.md).

## Kiểm tra trước khi cả nhóm nhận nền

```powershell
powershell -ExecutionPolicy Bypass -File scripts/verify.ps1
```

Script chạy route:list, compile Blade và FoundationTest (SQLite in-memory độc lập, không reset MySQL của bạn). PHPUnit cần PDO SQLite. Vẫn phải thử migration/seed và khóa kho T14 trên MySQL; test SQLite không chứng minh chống cạnh tranh kho.

Đọc [kết quả kiểm tra và giới hạn](VERIFICATION.md). Ai chạy được ghi máy/ngày/commit/kết quả vào đó.

## Đưa lên GitHub

Repository này đã là bộ nền chung. Cả nhóm clone/pull main, rồi tạo branch riêng theo README của mình. Đọc README_GIT_WORKFLOW.md; gửi PR thay vì làm chung trực tiếp trên main. Nhóm trưởng thêm tài khoản GitHub của thành viên tại Settings → Collaborators nếu họ cần push branch vào repository.

Không commit .env, vendor, node_modules, log hoặc dữ liệu MySQL cục bộ. Chỉ chia sẻ database qua file SQL mẫu đã kiểm tra. Thư mục mysql-8.4 ở gốc là dữ liệu máy cục bộ; không cần để phát triển, không sao chép vào Laragon của thành viên.

## Template

EShopper v1.0.0, HTML Codex/ThemeWagon. Giữ credit và LICENSE.txt. CSS/JS/ảnh đang dùng ở public/template/eshopper. Layout EShopper đã được ghép vào Blade; Châu kiểm tra, hoàn thiện và thống nhất giao diện, không dựng lại từ đầu. Không thêm nút giả ngoài phạm vi.

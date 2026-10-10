# Fashion Shop — Bộ nền nhóm 5 người

**Dự án Laravel chính:** DuAnLTMNM/laravel-eshopper/eshopper. Template gốc nằm tại DuAnLTMNM/template_html. Không tạo thêm một dự án Laravel riêng cho từng người.

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

Giải nén, mở folder DuAnLTMNM trong VS Code. Terminal:

```powershell
cd laravel-eshopper/eshopper
php -v
composer install
Copy-Item .env.example .env
```

Tạo database MySQL `fashion_shop` trên máy mình; chỉnh DB_USERNAME/DB_PASSWORD trong .env. DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306. APP_ENV=local. Không chép .env cho người khác, không commit .env. Nếu đã có .env cần giữ, không chạy lệnh chép đè.

```powershell
php artisan key:generate
php artisan optimize:clear
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Mở http://127.0.0.1:8000. Các trang nền không dùng @vite nên không cần npm cho nền này. Cài composer theo lock, không tự composer update toàn bộ.

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

Giữ một repository hiện có. Tạo branch `setup/nen-chung` rồi chép source vào đúng thư mục DuAnLTMNM tương ứng; không tạo DuAnLTMNM/DuAnLTMNM lồng nhau. Xem Changes; không commit .env, vendor, node_modules, log, database.sqlite hoặc .git từ ZIP. Commit/Push/PR/merge, cả nhóm pull main rồi tạo branch riêng. Bộ ZIP này không tự tạo branch hay thay đổi repository GitHub.

Bản gốc đã được giữ nguyên trong file upload. ZIP bàn giao loại dependency/cache/dữ liệu cục bộ, giữ lock files; chạy composer install trên mỗi máy.

## Template

EShopper v1.0.0, HTML Codex/ThemeWagon. Giữ credit và LICENSE.txt. CSS/JS gốc còn trong template_html và public/template/eshopper. Layout nền đơn giản, Châu tiếp tục chuyển bố cục EShopper đầy đủ. Không thêm nút giả ngoài phạm vi.

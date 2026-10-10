# Checklist bắt đầu trong một buổi

## Những thứ đã chốt trong source này

- [x] Một dự án chung: laravel-eshopper/eshopper.
- [x] Tên bảng, quan hệ, role, size/màu, VND và COD.
- [x] Migration/model/Seeder dùng chung; dữ liệu độc lập cho từng phần.
- [x] Layout, asset, tên section, thông báo success/error.
- [x] Route và field/service contract.
- [x] Phân công tên thật và lịch 7 ngày; chưa có MSSV/GitHub của mọi người.

## Nhóm cần làm trên máy mình trước khi viết module

- [ ] Kiều chạy migrate --seed và verify; ghi lỗi thật, không chỉ nhìn file.
- [ ] Cả năm người cài/pull cùng commit nền và mở trang Shop.
- [ ] Cả năm đọc SCHEMA/ROUTES/CONTRACTS, xác nhận giữ hợp đồng.
- [ ] Kiểm tra User A/B có giỏ và đơn; Admin bị chặn mua hàng; User bị chặn quản trị.
- [ ] Ghi PHP/Composer/MySQL thật, MSSV/GitHub/branch của từng người.

Nếu module phụ thuộc chưa có giao diện, dùng seed hiện có; không tạo bảng/model khác. Nếu nền còn lỗi, một người sửa nền và gửi PR nhỏ; các người khác vẫn có thể làm view/request từ contract nhưng chưa được coi tích hợp đã chạy.

## Quyền sửa file

Kiều routes/Auth/bootstrap; Danh schema/ERD; Châu layout/assets; Nhựt CartService; Anh Tú OrderService.checkout, Kiều OrderService.transition. Các service chung sửa qua PR và người cùng sử dụng review. Migration nền hiện gom sáu bảng; không chia/tạo lại migration. Thay đổi schema mới dùng ALTER migration sau khi nhóm chốt.

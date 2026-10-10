# Kế hoạch kiểm thử

Tất cả ca dưới đây đang **Chưa thử**. Sau khi chạy, bổ sung thực tế, Đạt/Không đạt, ngày, người thử, commit và đường dẫn ảnh.

| Mã | Tình huống | Kết quả mong đợi | Chủ ca |
| --- | --- | --- | --- |
| T01 | Đăng ký, đăng nhập đúng/sai, đăng xuất | Role user mặc định; mật khẩu băm; đăng xuất mất quyền | Kiều |
| T02 | User mở URL Admin và gửi yêu cầu sửa dữ liệu | Máy chủ chặn; dữ liệu không thay đổi | Kiều |
| T03 | Thêm, xem, sửa, xóa danh mục chưa có sản phẩm | Đủ CRUD; dữ liệu MySQL và menu khớp | Châu |
| T04 | Tên trùng hoặc xóa danh mục còn sản phẩm | Validation hoặc thông báo chặn xóa; giữ quan hệ | Châu |
| T05 | CRUD sản phẩm và hai biến thể khác size màu | Đủ CRUD; từng biến thể có SKU, giá, kho riêng | Danh |
| T06 | SKU trùng, giá âm, kho âm, size màu trùng, category_id sai | Từ chối ở máy chủ và hiện lỗi đúng trường | Danh |
| T07 | Xóa sản phẩm chưa phát sinh giỏ hoặc đơn; thử xóa sản phẩm có đơn | Xóa được bản thử chưa có tham chiếu; bản có đơn phải giữ và ẩn | Danh |
| T08 | Tìm tên; kết hợp danh mục giá size màu; không có kết quả | Kết quả đúng; size và màu phải khớp cùng biến thể | Nhựt |
| T09 | Seed hơn 12 sản phẩm; lọc rồi chuyển trang | Có phân trang thật; giữ mọi tham số lọc | Nhựt |
| T10 | Thêm cùng biến thể hai lần; thêm size khác; sửa và xóa | Cộng dồn cùng biến thể; tách size khác; tổng tiền đúng | Nhựt |
| T11 | Số lượng 0, âm, vượt kho; đổi ID dòng giỏ của User khác | Từ chối; không sửa giỏ người khác | Nhựt |
| T12 | Đặt giỏ hợp lệ có nhiều biến thể | Một đơn và đủ dòng; trừ đúng kho; giỏ được xóa sau thành công | Anh Tú |
| T13 | Đổi giá/tổng tiền trong dữ liệu gửi; thiếu thông tin giao hàng | Giá lấy từ CSDL; dữ liệu sai bị chặn; không tạo đơn dở | Anh Tú |
| T14 | Hai User cùng đặt biến thể chỉ còn 1; gửi lại cùng token | Tối đa một lượt mua được; không âm kho; không tạo đơn trùng | Anh Tú |
| T15 | Admin đổi pending tới confirmed, shipping, completed | Chỉ chuyển trạng thái hợp lệ; User thấy cập nhật | Kiều |
| T16 | Hủy pending; gửi lại hủy; hủy đơn đã shipping | Hoàn kho đúng một lần; đơn shipping không được hủy | Kiều |
| T17 | User B xem hoặc hủy đơn của User A | Không đọc hoặc sửa được đơn người khác | Kiều |
| T18 | Thử Shop, Detail, Cart, Checkout ở độ rộng khoảng 375 px | Menu, nút, lỗi form và bảng dùng được; không tràn trang | Châu |
| T19 | Cài bộ source trên máy khác với CSDL demo trống | Migrate và seed chạy; ảnh hiển thị; luồng mua hàng chạy | Kiều |

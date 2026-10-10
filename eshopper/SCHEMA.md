# Schema và quy tắc chung

| Bảng | Trường chính | Ràng buộc |
| --- | --- | --- |
| users | id; name; email unique; password; role | role chỉ admin hoặc user; đăng ký công khai luôn tạo user |
| categories | id; name unique; slug unique; description nullable | Một danh mục có nhiều sản phẩm; chặn xóa khi còn sản phẩm |
| products | id; category_id FK; name; slug unique; description; image_path nullable; is_active | Một sản phẩm thuộc một danh mục và có nhiều biến thể |
| product_variants | id; product_id FK; sku unique; size; color; price decimal(12,0); stock unsigned integer; is_active | Unique product_id + size + color; giá và kho theo từng biến thể |
| cart_items | id; user_id FK; variant_id FK; quantity unsigned integer | Unique user_id + variant_id; quantity ít nhất 1; chỉ chủ giỏ thao tác |
| orders | id; user_id FK; checkout_token unique; recipient_name; phone; address; payment_method; status; total_amount decimal(12,0) | Mặc định payment_method cod và status pending; dữ liệu giao hàng chụp tại lúc đặt |
| order_items | id; order_id FK; variant_id FK; product_name; sku; size; color; unit_price decimal(12,0); quantity; line_total decimal(12,0) | Lưu bản chụp tên, size, màu và giá để đơn cũ không đổi khi sản phẩm đổi |

4.2 Cơ sở dữ liệu và quan hệ Eloquent

Bảng 7 Schema nghiệp vụ thống nhất

Mọi bảng có timestamps. Thứ tự migration: users → categories → products → product_variants → cart_items và orders → order_items. Tên khóa của biến thể trong cart_items và order_items thống nhất là variant_id; khi viết belongsTo cần truyền tên FK này nếu dùng model ProductVariant.

Quan hệ phải có trên ERD và trong model: Category hasMany Product; Product belongsTo Category và hasMany ProductVariant; ProductVariant belongsTo Product; User hasMany CartItem và Order; CartItem belongsTo User và ProductVariant qua variant_id; Order belongsTo User và hasMany OrderItem; OrderItem belongsTo Order và ProductVariant qua variant_id.

Dùng FK và ràng buộc unique thật trong migration, kết hợp validation phía máy chủ. Chặn xóa category còn product; chặn xóa product/variant có dòng giỏ hoặc đơn tham chiếu. Sản phẩm đã bán chuyển is_active=false. Với sản phẩm thử không có tham chiếu, có thể xóa các variant của nó rồi xóa product trong transaction; không cascade từ sản phẩm sang order_items.

4.3 Quy tắc đồng bộ bắt buộc

RB01 Mỗi dòng giỏ là một variant_id, không chỉ product_id. Cùng sản phẩm nhưng khác size/màu là hai dòng; thêm lại cùng biến thể thì cộng số lượng. Khi thêm hoặc sửa, kiểm tra sản phẩm/biến thể đang hoạt động và tổng quantity không vượt stock.

RB02 Size, màu và khoảng giá khi lọc phải cùng khớp một biến thể. Ví dụ áo có M đỏ và L đen thì bộ lọc M đen không được trả áo đó. Giá hiển thị ngoài danh sách có thể là giá thấp nhất của biến thể đang bán, ghi “Từ”; detail và checkout dùng giá đúng biến thể đã chọn.

RB03 Giỏ chưa giữ chỗ tồn kho. Khi checkout, lấy giá mới nhất từ MySQL và kiểm tra lại kho; nếu giá thay đổi so với lúc khách xem, yêu cầu xác nhận lại trước khi tạo đơn. Bỏ qua price, total_amount hoặc user_id do trình duyệt tự gửi để tránh sửa giá hoặc đặt dưới tài khoản khác.

RB04 Tạo đơn, tạo order_items, trừ kho và xóa giỏ diễn ra trong một DB::transaction. Khóa dòng User của giỏ, tải lại giỏ, khóa các biến thể theo thứ tự ID bằng lockForUpdate, kiểm tra đủ kho rồi mới ghi [9]. Khi lỗi, rollback toàn bộ; không có đơn thiếu dòng hoặc tồn kho âm. Các thao tác sửa giỏ cũng dùng cùng khóa User để không đổi giỏ trong lúc checkout.

RB05 Mỗi lần checkout có checkout_token do máy chủ tạo và unique trong orders. Gửi lại cùng token phải trả về cùng đơn của đúng User, không trừ kho hoặc tạo đơn mới. Nút khóa sau khi bấm chỉ hỗ trợ giao diện; máy chủ vẫn cần bảo vệ thao tác lặp. Token được dùng lại khi lỗi form, chỉ đổi sau khi đặt thành công hoặc bắt đầu lượt checkout mới.

RB06 Trừ kho một lần tại lúc tạo đơn pending. Không trừ tiếp khi confirmed hoặc shipping. User chỉ hủy pending của mình; Admin hủy pending hoặc confirmed. Khi hủy, khóa đơn và biến thể trong transaction, chuyển cancelled và hoàn kho một lần. Lần hủy lặp không đổi kho. Shipping/completed không được hủy trong phạm vi đồ án; đơn không bị xóa để giữ lịch sử.

Bảng 8 Trạng thái đơn được phép chuyển

# Hợp đồng dữ liệu để làm song song

## Tên quan hệ cố định
Category.products(); Product.category()/variants(); ProductVariant.product()/cartItems()/orderItems(); User.cartItems()/orders(); CartItem.user()/variant(); Order.user()/items(); OrderItem.order()/variant(). FK biến thể là variant_id.

## View và thông báo
Layout: layouts.store hoặc layouts.admin; section title/content. Partials header/footer/alerts. Session success/error; validation qua $errors, @error, old(). Form thay đổi dữ liệu dùng CSRF và @method PATCH/DELETE khi cần. CSS EShopper asset trực tiếp. image_path là đường dẫn tương đối disk public, ví dụ products/a.jpg; hiển thị asset('storage/'.$product->image_path), không lưu đường dẫn C:\ hoặc URL tùy ý.

## Controller/view của mỗi người
- Châu: $categories / $category; views admin/categories/index, show, create, edit.
- Danh: $products / $product / $categories; views admin/products/index, show, create, edit.
- Nhựt: $products (Paginator), $product (kèm variants), $cartItems, $total; views shop/index, shop/show, cart/index.
- Anh Tú: $cartItems, $total, $checkoutToken; checkout/index.
- Kiều: $orders (Paginator), $order (kèm items); admin/orders/index/show, orders/index/show.
Các view này là hợp đồng tiếp theo, chưa tạo hết. FoundationController/view foundation là màn hình nền; thay từng route bằng controller thật nhưng giữ tên route và hợp đồng.

## Các field form
- Danh mục: name, slug, description.
- Sản phẩm: category_id, name, slug, description, image (file), is_active; variants[index][id/sku/size/color/price/stock/is_active]. ID chỉ dùng khi sửa và phải thuộc product đang sửa.
- Shop GET: q, category_id, min_price, max_price, size, color, page. Giá/size/màu trong cùng whereHas('variants').
- Thêm giỏ POST: variant_id, quantity. Sửa giỏ PATCH: quantity, ID dòng nằm trong URL; user_id lấy từ Auth.
- Checkout POST: recipient_name, phone, address, payment_method=cod, checkout_token. Không nhận total_amount/quotedPrices/user_id để tính đơn.
- Trạng thái Admin PATCH: status; User cancel không tự chọn status khác.

## CartService — Nhựt dùng
add(User $user,int $variantId,int $quantity): CartItem — cộng dồn.
update(User $user,int $itemId,int $quantity): CartItem — thay số lượng, chỉ giỏ mình.
remove(User $user,int $itemId): void — xóa dòng giỏ mình.
Khóa User trước, rồi variant theo ID; kiểm tra active/kho. Controller phải validate số nguyên dương rồi gọi service. Giỏ chưa giữ kho.

## OrderService — Anh Tú và Kiều dùng chung
checkout(User $user,array $delivery,string $token,array $quotedPrices): Order.
transition(User $actor,int $orderId,string $target): Order.
Service ném ValidationException cho lỗi giá/kho/trạng thái; 403/404 cho quyền/bản ghi. Controller không nuốt lỗi thành thông báo thành công.

GET checkout: tạo UUID, lưu session checkout.token và checkout.prices (map variant_id => giá hiện tại lấy từ MySQL). Giữ token khi lỗi; POST dùng CheckoutRequest, so sánh token form với token session trước khi gọi checkout. quotedPrices chỉ lấy từ session. Nếu giá/giỏ đổi, dựng lại giá hiện tại và cho khách xác nhận; không tự dùng giá cũ.
Sau thành công giữ session checkout.last_token/last_order_id để lần POST lặp cùng token truy xuất đúng đơn User qua service; xóa token/quote đang hoạt động. GET lượt mua mới tạo token mới. Không bỏ qua quyền/token vì route đã có Auth.
Service đã bảo vệ token unique/chủ sở hữu, transaction, khóa User/variants, tính tiền, trừ kho và xóa giỏ. Khi hủy: khóa Order rồi variants theo ID, hoàn kho một lần; không khóa ngược variant→Order trong module khác. Rà và thử trên MySQL trước nghiệm thu.

## Thay đổi hợp đồng
Người muốn đổi cột, route, quan hệ hoặc chữ ký service phải cập nhật SCHEMA/ROUTES/CONTRACTS trong cùng PR và báo người bị ảnh hưởng. Không đổi ngầm. Schema nền đã có: tạo migration ALTER nếu thay đổi, không sửa migration đã dùng chung để mong máy khác tự cập nhật.

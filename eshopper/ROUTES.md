# Route chung

Giữ tên/parameter/method; đổi action FoundationController sang controller chủ module khi triển khai. HTTP 501 nghĩa chưa triển khai, không phải lỗi MySQL.

| Method | URL | Tên route | Quyền | Hiện trạng |
|---|---|---|---|---|
| GET | / | home | — | Trang nền |
| GET | /shop | shop.index | — | Shop nền; Nhựt thêm lọc |
| GET | /shop/{product} | shop.show | — | Biến thể nền |
| GET/POST | /login | login / login.store | guest | Auth |
| GET/POST | /register | register / register.store | guest | Auth |
| POST | /logout | logout | auth | Auth |
| GET | /cart | cart.index | user | Giỏ mẫu |
| POST | /cart | cart.store | user | 501; Nhựt nối CartService |
| PATCH/DELETE | /cart/{cartItem} | cart.update / cart.destroy | user | 501 |
| GET/POST | /checkout | checkout.index / checkout.store | user | GET nền, POST 501; Anh Tú |
| GET | /orders | orders.index | user | Đơn cá nhân nền |
| GET | /orders/{order} | orders.show | user | Chi tiết, kiểm tra chủ sở hữu |
| PATCH | /orders/{order}/cancel | orders.cancel | user | 501; Kiều |
| GET | /admin | admin.dashboard | admin | Trang nền |
| GET | /admin/orders | admin.orders.index | admin | Đơn nền |
| GET | /admin/orders/{order} | admin.orders.show | admin | Chi tiết nền |
| PATCH | /admin/orders/{order}/status | admin.orders.update | admin | 501; Kiều |
| GET | /admin/categories | admin.categories.index | admin | Đọc nền |
| GET | /admin/categories/create | admin.categories.create | admin | 501; Châu |
| POST | /admin/categories | admin.categories.store | admin | 501; Châu |
| GET | /admin/categories/{category} | admin.categories.show | admin | 501; Châu |
| GET | /admin/categories/{category}/edit | admin.categories.edit | admin | 501; Châu |
| PATCH | /admin/categories/{category} | admin.categories.update | admin | 501; Châu |
| DELETE | /admin/categories/{category} | admin.categories.destroy | admin | 501; Châu |
| GET | /admin/products | admin.products.index | admin | Đọc nền |
| GET | /admin/products/create | admin.products.create | admin | 501; Danh |
| POST | /admin/products | admin.products.store | admin | 501; Danh |
| GET | /admin/products/{product} | admin.products.show | admin | 501; Danh |
| GET | /admin/products/{product}/edit | admin.products.edit | admin | 501; Danh |
| PATCH | /admin/products/{product} | admin.products.update | admin | 501; Danh |
| DELETE | /admin/products/{product} | admin.products.destroy | admin | 501; Danh |

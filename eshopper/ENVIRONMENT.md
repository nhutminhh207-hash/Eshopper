# Môi trường chung

Source gốc: PHP ^8.3, Laravel ^13.17 theo composer.json; cài đúng composer.lock. Một dự án: DuAnLTMNM/laravel-eshopper/eshopper.

- MySQL 8.x, bảng transactional InnoDB; mỗi máy một database fashion_shop.
- Composer 2.x; VS Code; Git/GitHub Desktop; trình duyệt.
- Layout nền dùng CSS EShopper/Bootstrap 4 trực tiếp; không gọi @vite, không cần npm để mở các màn hình nền. Nếu sau này dùng Vite thì build theo lock file.
- Asset gốc: CSS Bootstrap 4.5.3; template JS gốc 4.4.1. Layout nền không tải JS Bootstrap; thêm tương thích khi cần modal/dropdown và thử lại.
- PHP/Composer/MySQL thực tế từng máy: nhóm điền sau khi chạy php -v, composer -V, mysql --version.
- Môi trường tạo bộ nền này chưa có PHP/Composer; chưa chạy migration, Auth hoặc PHPUnit. Không coi kiểm tra cấu trúc thay cho chạy thật.

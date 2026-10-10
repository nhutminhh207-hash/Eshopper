# Quy trình Git của nhóm

## Nhánh và người phụ trách

`main` giữ bản đã được nhóm kiểm tra. Kiều điều phối tích hợp; mỗi người tạo branch riêng theo README. Đây là quy ước nhóm, không phải branch protection đã được bật.

## Thiết lập lần đầu — từ thư mục gốc repository

```bash
git switch main
git pull origin main
git switch -c feature/ten-nhanh-cua-ban
```

Thay tên nhánh bằng đúng nhánh TVx ở README. Nếu branch đã có, dùng `git switch` thay cho `git switch -c`.

## Mỗi buổi làm

Trước khi đổi nhánh, commit công việc dở hoặc stash rồi phục hồi trên đúng branch. Không đổi nhánh khi còn thay đổi chưa xử lý.

```bash
git switch main
git pull origin main
git switch feature/ten-nhanh-cua-ban
git merge main
```

Sau khi sửa và thử chức năng:

```bash
git status
git add duong-dan-file-da-sua
git commit -m "CN02: thêm validation SKU và tồn kho"
git push -u origin feature/ten-nhanh-cua-ban
```

Thay đường dẫn và commit bằng phần việc thật. Kiểm tra danh sách staged trước commit; không thêm .env hoặc dữ liệu thật.

## Pull Request

GitHub → Pull requests → New pull request, base `main`, compare branch của mình. Ghi CN, các file, phụ thuộc, cách chạy, ca thử và lỗi còn lại. Một người khác review; Kiều ghép sau khi xử lý góp ý và kiểm tra luồng liên quan.

## File dùng chung

- Kiều điều phối routes/web.php, middleware và Auth.
- Danh điều phối schema/ERD và thứ tự migration.
- Châu điều phối layout/partial/CSS; ghép Word từ phần riêng từng người.
- Anh Tú checkout và Kiều quản lý đơn dùng cùng quy tắc OrderService, kho và trạng thái.
- Nhựt tổng hợp kiểm thử và slide; các chủ module cung cấp kết quả thật.

Không chia thành năm dự án Laravel độc lập. Mỗi máy dùng CSDL riêng, cùng migration/seeder.

## Conflict

Mở file xung đột trong VS Code, đọc cả hai thay đổi và thống nhất với chủ file. Sửa nội dung, xóa marker conflict, thử lại rồi git add/commit/push. Không chọn toàn bộ Current/Incoming khi chưa hiểu phần nghiệp vụ. Có thể `git merge --abort` để hủy merge đang dở.

## GitHub Desktop

Clone repository → tạo/chọn branch → Fetch/Pull → sửa bằng VS Code → review Changes → Commit → Push → Create Pull Request. Dùng cùng branch và quy tắc như lệnh trên.

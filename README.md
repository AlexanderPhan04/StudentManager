# 📚 Hệ thống Quản lý Sinh viên

Ứng dụng web quản lý thông tin sinh viên được xây dựng bằng **PHP thuần với mô hình MVC và OOP**, sử dụng **Single Entry Point Router**.

## 🌐 Demo

**Live Demo:** [https://studentmanager.alexstudio.id.vn/](https://studentmanager.alexstudio.id.vn/)

**Tài khoản demo:**

- Admin: `admin` / `admin`
- User: Đăng ký tài khoản mới

## 🎯 Tính năng chính

### Xác thực và phân quyền

- ✅ Đăng ký tài khoản (username, email, password, role)
- ✅ Đăng nhập với xác thực mật khẩu mã hóa
- ✅ Quản lý session
- ✅ Đăng xuất
- ✅ Phân quyền Admin/User

### Quản lý sinh viên (CRUD)

- ✅ Xem danh sách sinh viên
- ✅ Thêm sinh viên mới (chỉ Admin)
- ✅ Sửa thông tin sinh viên (chỉ Admin)
- ✅ Xóa sinh viên (chỉ Admin)
- ✅ Tìm kiếm sinh viên theo tên hoặc mã sinh viên

### Phân quyền

- **Admin**: Toàn quyền CRUD trên tất cả sinh viên
- **User**: Chỉ xem danh sách sinh viên

## 🛠 Công nghệ sử dụng

- **Backend**: PHP 7.4+ với OOP và MVC Pattern
- **Database**: MySQL 5.7+
- **Kết nối DB**: PDO với Singleton Pattern
- **Bảo mật**: password_hash() và password_verify()
- **Giao diện**: HTML5, CSS3 thuần (responsive)
- **Routing**: Single Entry Point Router

## 🏗 Kiến trúc MVC

Dự án sử dụng mô hình **Model-View-Controller (MVC)** với lập trình hướng đối tượng (OOP) và **Single Entry Point Router**:

### Router (Điểm vào duy nhất)

- `index.php`: Router chính xử lý tất cả requests
  - `?action=login` → Đăng nhập
  - `?action=register` → Đăng ký
  - `?action=logout` → Đăng xuất
  - `?action=students` → Danh sách sinh viên
  - `?action=student_add` → Thêm sinh viên
  - `?action=student_edit&id=x` → Sửa sinh viên
  - `?action=student_delete&id=x` → Xóa sinh viên

### Model (Tầng dữ liệu)

- `Database.php`: Singleton pattern để quản lý kết nối DB
- `Model.php`: Base class cho tất cả models
- `UserModel.php`: Xử lý thao tác với bảng users
- `StudentModel.php`: Xử lý thao tác với bảng students

### Controller (Tầng xử lý logic)

- `AuthController.php`: Xử lý đăng nhập, đăng ký, đăng xuất
- `StudentController.php`: Xử lý CRUD sinh viên

### View (Tầng hiển thị)

- `views/auth/`: Các view đăng nhập, đăng ký
- `views/students/`: Các view quản lý sinh viên
- `views/layouts/`: Header, footer chung

## 📋 Cấu trúc Database

### Bảng `users`

```sql
- id: INT (Primary Key, Auto Increment)
- user_name: VARCHAR(50) UNIQUE
- password: VARCHAR(255) (hashed)
- email: VARCHAR(100)
- role: ENUM('admin', 'user')
- create_at: TIMESTAMP
```

### Bảng `students`

```sql
- id: INT (Primary Key, Auto Increment)
- student_code: VARCHAR(20) UNIQUE
- full_name: VARCHAR(100)
- birthday: DATE
- gender: ENUM('Male', 'Female')
- email: VARCHAR(100)
- phone: VARCHAR(20)
- address: TEXT
- user_id: INT (Foreign Key -> users.id)
- create_at: TIMESTAMP
```

## 🚀 Cài đặt và chạy dự án

### Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx) hoặc Laragon/XAMPP

### Các bước cài đặt

1. **Clone hoặc tải dự án về**

   ```bash
   git clone <repository-url>
   cd StudentManager
   ```

2. **Import database**

   - Mở phpMyAdmin hoặc MySQL client
   - Tạo database mới: `student_manager`
   - Import file `db.sql` vào database vừa tạo

3. **Cấu hình kết nối database**

   - Mở file `config/Database.php`
   - Sửa thông tin kết nối phù hợp với môi trường của bạn:

   ```php
   private $host = 'localhost';
   private $dbname = 'student_manager';
   private $username = 'root';
   private $password = '';
   ```

4. **Chạy dự án**

   - Nếu dùng Laragon/XAMPP: Copy thư mục vào `htdocs` hoặc `www`
   - Truy cập: `http://localhost/StudentManager/index.php?action=login`
   - Hoặc dùng PHP built-in server:

   ```bash
   php -S localhost:8000
   ```

5. **Đăng nhập**
   - Tài khoản admin mặc định (đã có trong db.sql):
     - Username: `admin`
     - Password: `password`

## 📂 Cấu trúc thư mục

```
StudentManager/
├── config/
│   └── Database.php        # Singleton DB connection
├── models/
│   ├── Model.php           # Base Model class
│   ├── UserModel.php       # User model (extends Model)
│   └── StudentModel.php    # Student model (extends Model)
├── controllers/
│   ├── AuthController.php  # Authentication controller
│   └── StudentController.php # Student CRUD controller
├── views/
│   ├── auth/
│   │   ├── login_view.php  # View đăng nhập
│   │   └── register_view.php # View đăng ký
│   ├── students/
│   │   ├── index.php       # View danh sách sinh viên
│   │   ├── create.php      # View thêm sinh viên
│   │   └── edit.php        # View sửa sinh viên
│   └── layouts/
│       ├── header.php      # Header chung
│       └── footer.php      # Footer chung
├── .github/workflows/
│   └── deploy.yml          # CI/CD workflow
├── .env.example            # Mẫu file cấu hình
├── .gitignore              # Git ignore
├── .htaccess               # Apache config
├── db.sql                  # Database schema
├── index.php               # Router chính (Single Entry Point)
└── README.md               # Documentation
```

## 🔒 Bảo mật

- ✅ Mật khẩu được mã hóa bằng `password_hash()`
- ✅ Sử dụng Prepared Statements (PDO) để chống SQL Injection
- ✅ Validate dữ liệu đầu vào cả client-side và server-side
- ✅ Kiểm tra phân quyền trong Controller trước mỗi thao tác
- ✅ Sử dụng `htmlspecialchars()` để chống XSS
- ✅ Singleton pattern để quản lý kết nối DB duy nhất
- ✅ Single Entry Point Router để kiểm soát tất cả requests

## 🎨 Design Patterns được sử dụng

- **MVC Pattern**: Tách biệt logic, dữ liệu và giao diện
- **Single Entry Point**: Tất cả requests đi qua index.php router
- **Singleton Pattern**: Database connection duy nhất
- **Inheritance**: Model classes kế thừa từ base Model
- **Encapsulation**: Private/protected properties và methods

## 👥 Phân quyền chi tiết

| Tính năng               | Admin | User |
| ----------------------- | ----- | ---- |
| Xem danh sách sinh viên | ✅    | ✅   |
| Tìm kiếm sinh viên      | ✅    | ✅   |
| Thêm sinh viên          | ✅    | ❌   |
| Sửa sinh viên           | ✅    | ❌   |
| Xóa sinh viên           | ✅    | ❌   |

## 📝 Ghi chú

- Dự án sử dụng **OOP và MVC pattern** cho cấu trúc code chuyên nghiệp
- **Single Entry Point Router** để tập trung xử lý tất cả requests
- **Singleton pattern** để quản lý database connection hiệu quả
- **Inheritance** để tái sử dụng code (Model base class)
- Code được tổ chức rõ ràng, dễ bảo trì và mở rộng
- Giao diện responsive cơ bản, không dùng thư viện CSS
- Có comment giải thích các phần quan trọng

## 📧 Liên hệ

Nếu có vấn đề hoặc câu hỏi, vui lòng tạo issue hoặc liên hệ qua email.

---

© 2025 Student Manager - PHP MVC Project

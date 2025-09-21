# Toxic Comment Detection System - Setup Instructions

## Hướng dẫn khởi động hệ thống phát hiện comment độc hại

### 1. Cài đặt dependencies cho Python API

Mở Command Prompt/Terminal và navigate đến thư mục toxic classification:

```bash
cd "c:\xampp\htdocs\OnlineNewsSite\Toxic Comment Classification"
pip install -r requirements.txt
```

### 2. Khởi động Toxic Detection API

Trong cùng thư mục, chạy lệnh:

```bash
python toxic_api.py
```

API sẽ chạy trên http://localhost:5001

### 3. Kiểm tra API hoạt động

Mở browser và truy cập:
- Health check: http://localhost:5001/api/health
- Stats: http://localhost:5001/api/stats

### 4. Test hệ thống comment

1. Khởi động XAMPP (Apache + MySQL)
2. Mở website: http://localhost/OnlineNewsSite
3. Login vào một user account
4. Vào một bài viết và thử comment:
   - Comment bình thường: "This is a great article, thank you!"
   - Comment toxic: "This article is stupid and the author is an idiot!"
5. Kiểm tra trong admin panel (admin/comment) để xem status:
   - Comment không toxic sẽ có status "approved" (tự động duyệt)
   - Comment toxic sẽ có status "unseen" (cần admin xem xét)

### 5. Cách thức hoạt động

- **Auto-approval**: Comment với toxic probability < 50% được tự động duyệt
- **Flagged for review**: Comment với toxic probability >= 50% được flag để admin xem xét
- **Fallback**: Nếu API lỗi, comment được approve theo mặc định (có thể thay đổi trong ToxicCommentDetector.php)

### 6. Monitoring và Statistics

- API cung cấp endpoint /api/stats để theo dõi số lượng comment đã xử lý
- Admin panel hiển thị comment với status rõ ràng và badges màu sắc
- System log sẽ ghi lại các hoạt động của API

### 7. Troubleshooting

**Nếu API không hoạt động:**
- Kiểm tra Python đã cài đặt scikit-learn, flask, pandas
- Đảm bảo file toxic_pipeline.pkl tồn tại trong thư mục
- Kiểm tra port 5001 không bị conflict

**Nếu comment không được detect:**
- Kiểm tra ToxicCommentDetector.php đang gọi đúng URL API
- Xem log trong browser console hoặc PHP error log
- Fallback sẽ auto-approve nếu API không khả dụng

### 8. Cấu hình tùy chỉnh

Trong file `lib/ToxicCommentDetector.php`:
- Thay đổi threshold: `$toxicProbability < 0.5` (currently 50%)
- Thay đổi fallback behavior: `$this->fallback_approval = false` để reject thay vì approve
- Điều chỉnh timeout: Constructor parameter `$timeout = 10`
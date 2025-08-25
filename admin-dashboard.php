<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['logged_in'])) {
    header('Location: admin-login.php');
    exit;
}

// ملف قاعدة البيانات البسيط
$articles_file = 'articles.json';

// تحميل المقالات الحالية
$articles = [];
if (file_exists($articles_file)) {
    $articles = json_decode(file_get_contents($articles_file), true);
}

// إضافة مقال جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $image_name = '';
    
    // معالجة رفع الصورة إذا تم اختيارها
    if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        
        // إنشاء مجلد التحميل إذا لم يكن موجوداً
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['article_image']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $file_path = $upload_dir . $file_name;
        
        // التحقق من أن الملف صورة
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($file_extension), $allowed_extensions)) {
            if (move_uploaded_file($_FILES['article_image']['tmp_name'], $file_path)) {
                $image_name = $file_name;
            }
        }
    }
    
    $new_article = [
        'id' => uniqid(),
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'date' => date('Y-m-d'),
        'category' => $_POST['category'],
        'image_color' => $_POST['image_color'],
        'image' => $image_name // حفظ اسم الصورة إذا تم رفعها
    ];
    
    array_unshift($articles, $new_article);
    file_put_contents($articles_file, json_encode($articles));
    
    // إعادة التوجيه لمنع إعادة إرسال النموذج
    header('Location: admin-dashboard.php');
    exit;
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin-login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - كوكب زمردة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #D56989;
            --secondary-color: #E49CAF;
            --accent-color: #C2DC80;
            --background-color: #F3EEF1;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .dashboard {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--secondary-color);
        }
        
        .dashboard-header h1 {
            color: var(--primary-color);
            margin: 0;
        }
        
        .logout-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 30px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .logout-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .add-article-form {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .add-article-form h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.7rem;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .form-group input, 
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--secondary-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus, 
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .form-group textarea {
            min-height: 250px;
            resize: vertical;
        }
        
        .submit-btn {
            background: var(--accent-color);
            color: #333;
            border: none;
            padding: 1rem 2rem;
            border-radius: 30px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 1rem;
        }
        
        .submit-btn:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .submit-btn i {
            margin-left: 0.5rem;
        }
        
        .articles-list {
            margin-top: 3rem;
        }
        
        .articles-list h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--accent-color);
        }
        
        .article-item {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }
        
        .article-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .article-item h3 {
            color: var(--primary-color);
            margin-bottom: 0.7rem;
            font-size: 1.3rem;
        }
        
        .article-meta {
            color: var(--accent-color);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
        }
        
        .article-meta i {
            margin-left: 0.5rem;
        }
        
        .article-content-preview {
            color: #666;
            line-height: 1.6;
        }
        
        /* أنماط رفع الصورة */
        .image-upload-box {
            position: relative;
            border: 2px dashed var(--secondary-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: border-color 0.3s ease;
            margin-bottom: 0.5rem;
        }
        
        .image-upload-box:hover {
            border-color: var(--primary-color);
        }
        
        .image-upload-box input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }
        
        .upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--primary-color);
            cursor: pointer;
        }
        
        .upload-label i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }
        
        .upload-label span {
            font-weight: 500;
        }
        
        .image-preview {
            display: none;
            margin-top: 1.5rem;
            position: relative;
            max-width: 300px;
            margin: 1.5rem auto 0;
        }
        
        .image-preview img {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        #removeImage {
            position: absolute;
            top: -10px;
            left: -10px;
            background: var(--primary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .upload-hint {
            display: block;
            color: #888;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .dashboard {
                padding: 1rem;
            }
            
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .add-article-form {
                padding: 1.5rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="dashboard-header">
            <h1>لوحة التحكم - كوكب زمردة</h1>
            <a href="?logout=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
            </a>
        </div>
        
        <div class="add-article-form">
            <h2>إضافة مقال جديد</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">عنوان المقال</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="content">محتوى المقال</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category">التصنيف</label>
                    <select id="category" name="category" required>
                        <option value="الجمال">الجمال</option>
                        <option value="الصحة">الصحة</option>
                        <option value="العلاقات">العلاقات</option>
                        <option value="التربية">التربية</option>
                        <option value="التطوير الذاتي">التطوير الذاتي</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image_color">لون المقال</label>
                    <select id="image_color" name="image_color" required>
                        <option value="#E49CAF">وردي فاتح</option>
                        <option value="#D56989">وردي غامق</option>
                        <option value="#C2DC80">أخضر فاتح</option>
                    </select>
                </div>

                <!-- قسم رفع الصور المضاف -->
                <div class="form-group">
                    <label for="article_image">أو رفع صورة للمقال</label>
                    <div class="image-upload-box">
                        <input type="file" id="article_image" name="article_image" accept="image/*" onchange="previewImage(this)">
                        <label for="article_image" class="upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>اختر صورة (JPG, PNG, GIF)</span>
                        </label>
                        <div class="image-preview" id="imagePreview">
                            <img id="previewImage" src="#" alt="معاينة الصورة">
                            <span id="removeImage" onclick="removeImage()"><i class="fas fa-times"></i></span>
                        </div>
                    </div>
                    <small class="upload-hint">الحجم الأقصى: 1MB - سيتم تغيير حجم الصورة تلقائياً</small>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> نشر المقال
                </button>
            </form>
        </div>
        
        <div class="articles-list">
            <h2>المقالات المنشورة</h2>
            
            <?php if (empty($articles)): ?>
                <p>لا توجد مقالات منشورة بعد.</p>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article-item">
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                        <div class="article-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo $article['date']; ?></span>
                            <span><i class="fas fa-tag"></i> <?php echo $article['category']; ?></span>
                            <?php if (!empty($article['image'])): ?>
                                <span><i class="fas fa-image"></i> تحتوي على صورة</span>
                            <?php endif; ?>
                        </div>
                        <p class="article-content-preview"><?php echo substr(htmlspecialchars($article['content']), 0, 200); ?>...</p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // معاينة الصورة قبل الرفع
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // إزالة معاينة الصورة
        function removeImage() {
            const preview = document.getElementById('imagePreview');
            const fileInput = document.getElementById('article_image');
            
            preview.style.display = 'none';
            fileInput.value = '';
        }
        
        // التحقق من حجم الصورة قبل الرفع
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('article_image');
            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size / 1024 / 1024; // حجم الملف بالميجابايت
                if (fileSize > 1) {
                    e.preventDefault();
                    alert('حجم الصورة يجب أن يكون أقل من 1MB');
                }
            }
        });
    </script>
</body>
</html>
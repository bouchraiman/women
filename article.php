<?php
$articles_file = 'articles.json';
$article_id = $_GET['id'] ?? '';
$article = null;

if (file_exists($articles_file)) {
    $articles = json_decode(file_get_contents($articles_file), true);
    foreach ($articles as $item) {
        if ($item['id'] === $article_id) {
            $article = $item;
            break;
        }
    }
}

if (!$article) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> | كوكب زمردة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* أنماط CSS العامة */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #F3EEF1;
            color: #333;
            line-height: 1.6;
            padding-top: 80px;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Animation de fond */
        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            border-radius: 70%;
            background: #D56989 ; /* لون يتناسب مع Petal Glaze */
            animation: float 15s infinite linear;
            opacity: 0.6;
        }

        .bubble-1 {
            width: 200px;
            height: 200px;
            background: #D56989 ;
            top: 5%;
            left: 5%;
            animation-delay: 0s;
        }

        .bubble-2 {
            width: 300px;
            height: 300px;
            background: #D56989 ;
            top: 50%;
            right: 5%;
            animation-delay: 2s;
        }

        .bubble-3 {
            width: 150px;
            height: 150px;
            background: #D56989 ;
            bottom: 20%;
            left: 5%;
            animation-delay: 4s;
        }

        .bubble-4 {
            width: 250px;
            height: 250px;
            background: #D56989 ;
            bottom: 10%;
            right: 0%;
            animation-delay: 1s;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0) rotate(1deg);
            }
            25% {
                transform: translateY(-20px) rotate(5deg);
            }
            50% {
                transform: translateY(0) rotate(0deg);
            }
            75% {
                transform: translateY(20px) rotate(-5deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
            }
        }
        
        /* شريط التنقل */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #D56989, #E49CAF);
            color: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            animation: slideDown 0.5s ease;
        }
        
        .navbar .logo {
            font-size: 1.8rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .navbar .logo i {
            margin-left: 10px;
            color: #C2DC80;
        }
        
        .navbar .nav-links {
            display: flex;
            list-style: none;
        }
        
        .navbar .nav-links li {
            margin: 0 15px;
        }
        
        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }
        
        .navbar .nav-links a:hover {
            color: #C2DC80;
        }
        
        .navbar .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #C2DC80;
            transition: width 0.3s ease;
        }
        
        .navbar .nav-links a:hover::after {
            width: 100%;
        }
        
        /* محتوى المقال */
        .article-content {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.8s ease;
            position: relative;
            z-index: 1;
        }
        
        .article-hero {
            padding: 30px;
            background: linear-gradient(135deg, #D56989, #E49CAF);
            color: white;
            text-align: center;
        }
        
        .article-hero h1 {
            font-size: 2.2rem;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }
        
        .article-meta {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
            animation: fadeInUp 1s ease;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .article-meta i {
            margin-left: 8px;
        }
        
        .article-image {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            display: block;
            animation: zoomIn 1s ease;
        }
        
        .article-body {
            padding: 40px;
        }
        
        .article-body p {
            margin-bottom: 20px;
            font-size: 1.1rem;
            line-height: 1.8;
            animation: fadeIn 1s ease;
            animation-fill-mode: both;
        }
        
        .article-body p:nth-child(1) {
            animation-delay: 0.2s;
        }
        
        .article-body p:nth-child(2) {
            animation-delay: 0.4s;
        }
        
        .article-body p:nth-child(3) {
            animation-delay: 0.6s;
        }
        
        .article-body p:nth-child(4) {
            animation-delay: 0.8s;
        }
        
        .article-body p:nth-child(5) {
            animation-delay: 1s;
        }
        
        .article-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
            gap: 20px;
            animation: fadeIn 1.2s ease;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background: #D56989;
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(213, 105, 137, 0.3);
        }
        
        .back-link:hover {
            background: #c15a7c;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(213, 105, 137, 0.4);
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        .social-share {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .social-share span {
            font-weight: 500;
        }
        
        .social-share a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #f1f2f6;
            color: #D56989;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .social-share a:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        
        .social-share a:nth-child(2):hover {
            background: #3b5998;
            color: white;
        }
        
        .social-share a:nth-child(3):hover {
            background: #1da1f2;
            color: white;
        }
        
        .social-share a:nth-child(4):hover {
            background: #25d366;
            color: white;
        }
        
        /* تذييل الصفحة */
        .footer {
            background: #E49CAF;
            color: white;
            padding: 40px 5%;
            text-align: center;
            margin-top: 60px;
            animation: fadeIn 1.5s ease;
            position: relative;
            z-index: 1;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .footer-section {
            flex: 1;
            min-width: 250px;
        }
        
        .footer-section h3 {
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 2px;
            background: #C2DC80;
        }
        
        .footer-section p {
            margin-bottom: 15px;
        }
        
        .footer-section a {
            color: #f8f8f8;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 10px;
        }
        
        .footer-section a:hover {
            color: #C2DC80;
        }
        
        .footer-bottom {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* الرسوم المتحركة */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(1.1);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 15px;
            }
            
            .navbar .nav-links {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .navbar .nav-links li {
                margin: 5px 10px;
            }
            
            .article-hero h1 {
                font-size: 1.8rem;
            }
            
            .article-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .article-body {
                padding: 25px;
            }
            
            .article-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .social-share {
                margin-top: 15px;
            }
            
            .footer-content {
                flex-direction: column;
            }
            
            /* تعديل حجم الفقاعات على الشاشات الصغيرة */
            .bubble-1, .bubble-2, .bubble-3, .bubble-4 {
                width: 120px !important;
                height: 120px !important;
            }
        }
    </style>
</head>
<body>
    <!-- تأثير الفقاعات المتحركة في الخلفية -->
    <div class="background-animation">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
    </div>

    <!-- شريط التنقل -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-globe-africa"></i>
            <span>كوكب زمردة</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="#">المقالات</a></li>
            <li><a href="#">الاقسام</a></li>
            <li><a href="#">من نحن</a></li>
            <li><a href="#">اتصل بنا</a></li>
        </ul>
    </nav>

    <!-- محتوى المقال -->
    <div class="article-content">
        <div class="article-hero">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            <div class="article-meta">
                <span><i class="far fa-calendar-alt"></i> <?php echo $article['date']; ?></span>
                <span><i class="far fa-clock"></i> وقت القراءة: 4 دقائق</span>
                <span><i class="fas fa-tag"></i> <?php echo $article['category']; ?></span>
            </div>
        </div>
        
        <?php if (!empty($article['image'])): ?>
            <img src="uploads/<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
        <?php endif; ?>
        
        <div class="article-body">
            <?php
            $content = htmlspecialchars($article['content']);
            $paragraphs = explode("\n", $content);
            
            foreach ($paragraphs as $index => $paragraph) {
                if (trim($paragraph)) {
                    echo '<p>' . nl2br($paragraph) . '</p>';
                }
            }
            ?>
            
            <div class="article-footer">
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-right"></i> العودة للمقالات
                </a>
                <div class="social-share">
                    <span>شارك المقال:</span>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- تذييل الصفحة -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>عن كوكب زمردة</h3>
                <p>منصة عربية تقدم محتوى مميز في مختلف المجالات الثقافية والعلمية والترفيهية.</p>
            </div>
            <div class="footer-section">
                <h3>روابط سريعة</h3>
                <a href="index.php">الرئيسية</a>
                <a href="#">المقالات</a>
                <a href="#">الاقسام</a>
                <a href="#">اتصل بنا</a>
            </div>
            <div class="footer-section">
                <h3>معلومات التواصل</h3>
                <p><i class="fas fa-envelope"></i> info@emeraldplanet.com</p>
                <p><i class="fas fa-phone"></i> +123456789</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>جميع الحقوق محفوظة &copy; كوكب زمردة2025</p>
        </div>
    </footer>

    <script>
        // إضافة تأثير التمرير السلس للروابط
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
        
        // إضافة تأثير التمرير للشريط العلوي
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                navbar.style.background = 'linear-gradient(135deg, #D56989, #d47a96)';
            } else {
                navbar.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
                navbar.style.background = 'linear-gradient(135deg, #D56989, #E49CAF)';
            }
        });
        
        // إنشاء فقاعات إضافية بشكل ديناميكي
        function createBubbles() {
            const background = document.querySelector('.background-animation');
            const colors = [
                'rgba(234, 156, 175, 0.1)', // Petal Glaze
                'rgba(213, 105, 137, 0.1)', // Dusty Orchid
                'rgba(194, 220, 128, 0.1)'  // Sorbet Stem
            ];
            
            for (let i = 0; i < 6; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                
                const size = Math.random() * 100 + 50;
                bubble.style.width = `${size}px`;
                bubble.style.height = `${size}px`;
                
                bubble.style.top = `${Math.random() * 100}%`;
                bubble.style.left = `${Math.random() * 100}%`;
                
                bubble.style.animationDelay = `${Math.random() * 10}s`;
                bubble.style.animationDuration = `${15 + Math.random() * 10}s`;
                
                bubble.style.background = colors[Math.floor(Math.random() * colors.length)];
                
                background.appendChild(bubble);
            }
        }
        
        // استدعاء الدالة لإنشاء فقاعات إضافية عند تحميل الصفحة
        window.addEventListener('load', createBubbles);
    </script>
</body>
</html>
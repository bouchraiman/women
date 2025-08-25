<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كوكب زمردة - مدونة المرأة العصرية</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- شريط التنقل العلوي -->
    <nav class="navbar">
        <div class="logo-container">
            <div class="logo-circle"></div>
            <h1 class="logo-text">كوكب زمردة</h1>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">الرئيسية</a></li>
            <li><a href="article.php">مقالات تخص المرأة</a></li>
            <li><a href="#">اتصل بنا</a></li>
        </ul>
    </nav>

    <!-- قسم الهيرو -->
    <header class="hero">
        <div class="hero-content">
            <h2>مرحباً بكن في عالم زمردة</h2>
            <p>مدونة متخصصة لكل ما يهم المرأة العصرية في حياتها اليومية</p>
            <button class="cta-button">استكشف المقالات</button>
        </div>
    </header>

   <!-- المقالات المميزة -->
<section class="featured-articles">
    <h2 class="section-title">أحدث المقالات</h2>
    <div class="articles-container">
        <?php
        $articles_file = 'articles.json';
        $articles = [];
        
        if (file_exists($articles_file)) {
            $articles = json_decode(file_get_contents($articles_file), true);
            
            // عرض آخر 3 مقالات فقط في الصفحة الرئيسية
            $recent_articles = array_slice($articles, 0, 3);
            
            foreach ($recent_articles as $article): ?>
                <article class="article-card">
                    <div class="article-image" style="background-color: <?php echo $article['image_color']; ?>;"></div>
                    <div class="article-content">
                        <h3><?php echo htmlspecialchars($article['title']); ?></h3>
                        <p class="article-meta">نشر في <?php echo $article['date']; ?></p>
                        <p><?php echo substr(htmlspecialchars($article['content']), 0, 100); ?>...</p>
                        <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">قراءة المزيد</a>
                    </div>
                </article>
            <?php endforeach;
        } else {
            echo '<p>لا توجد مقالات منشورة بعد.</p>';
        }
        ?>
    </div>
</section>
    <!-- قسم التعريف -->
    <section class="about-section">
        <div class="about-image">
            <img src="téléchargement (43).jpg" alt="صورة الفتاة">
        </div>
        <div class="about-content">
            <h2>عن المدونة</h2>
            <p>مرحباً بكن في كوكب زمردة، مساحتك الآمنة للاستكشاف والنمو. هنا تجدين مقالات ونصائح خصصناها لتناسب احتياجاتك كامرأة عصرية. نؤمن بأن كل امرأة هي عالم يستحق الاكتشاف، ونحن هنا لنساعدك في رحلتك نحو تحقيق التوازن بين مسؤولياتك وتطلعاتك.</p>
            <p>نقدم محتوى متنوعاً يغطي الجمال، الصحة، العلاقات، التربية، التطوير الذاتي، والكثير من المواضيع التي تهمك.</p>
        </div>
    </section>

    <!-- تذييل الصفحة -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-circle"></div>
                <h3>كوكب زمردة</h3>
            </div>
            <div class="footer-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-pinterest-p"></i></a>
            </div>
            <p class="copyright">© 2025 كوكب زمردة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>




<?php
$articles_file = 'articles.json';
$articles = file_exists($articles_file) ? json_decode(file_get_contents($articles_file), true) : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- الهيد السابق... -->
    <style>
        .article-image {
            height: 200px;
            overflow: hidden;
            border-radius: 10px 10px 0 0;
        }
        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .article-card:hover .article-image img {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<section class="featured-articles">
    <div class="section-header">
        <h2 class="section-title">أحدث المقالات</h2>
        <div class="title-decoration">
            <span class="dot" style="background-color: #E49CAF;"></span>
            <span class="dot" style="background-color: #D56989;"></span>
            <span class="dot" style="background-color: #C2DC80;"></span>
        </div>
    </div>
    
    <div class="articles-grid">
        <?php foreach (array_slice($articles, 0, 3) as $article): ?>
        <article class="article-card" data-category="<?php echo $article['category']; ?>">
            <?php if (!empty($article['image'])): ?>
            <div class="article-image">
                <img src="uploads/<?php echo $article['image']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                <div class="category-badge"><?php echo $article['category']; ?></div>
            </div>
            <?php else: ?>
            <div class="article-image" style="background-color: <?php echo $article['image_color']; ?>;">
                <div class="category-badge"><?php echo $article['category']; ?></div>
            </div>
            <?php endif; ?>
            
            <div class="article-content">
                <div class="article-meta">
                    <span class="date"><?php echo $article['date']; ?></span>
                    <span class="reading-time">⏳ 3 دقائق قراءة</span>
                </div>
                <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="article-excerpt"><?php echo substr(htmlspecialchars($article['content']), 0, 120); ?>...</p>
                <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more-btn">
                    اقرأ المزيد
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</section>
    <!-- باقي المحتوى... -->
</body>

<script>
// إعادة تنشيط الأنيميشنات عند التمرير
window.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.article-card');
    cards.forEach(card => {
        const cardTop = card.getBoundingClientRect().top;
        if (cardTop < window.innerHeight - 100) {
            card.style.animationPlayState = 'running';
        }
    });
});
</script>
</html>
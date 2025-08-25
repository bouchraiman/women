document.addEventListener('DOMContentLoaded', function() {
    // تأثيرات الظهور
    const articles = document.querySelectorAll('.article-card');
    
    articles.forEach((article, index) => {
        article.style.opacity = '0';
        article.style.transform = 'translateY(20px)';
        article.style.transition = `all 0.5s ease ${index * 0.1}s`;
        
        setTimeout(() => {
            article.style.opacity = '1';
            article.style.transform = 'translateY(0)';
        }, 100);
    });

    // تأثيرات الـ Hover
    articles.forEach(article => {
        article.addEventListener('mouseenter', () => {
            const img = article.querySelector('img');
            if (img) img.style.transform = 'scale(1.1)';
        });
        
        article.addEventListener('mouseleave', () => {
            const img = article.querySelector('img');
            if (img) img.style.transform = 'scale(1)';
        });
    });
});
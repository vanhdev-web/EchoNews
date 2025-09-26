<?php 
    require_once(BASE_PATH . '/template/app/layouts/header.php');

    // Function to get icon based on category name
    function getCategoryIcon($categoryName) {
        $categoryName = strtolower($categoryName);
        
        if (strpos($categoryName, 'technology') !== false || strpos($categoryName, 'tech') !== false) {
            return 'fas fa-microchip';
        } elseif (strpos($categoryName, 'business') !== false || strpos($categoryName, 'finance') !== false) {
            return 'fas fa-chart-line';
        } elseif (strpos($categoryName, 'sport') !== false || strpos($categoryName, 'sports') !== false) {
            return 'fas fa-futbol';
        } elseif (strpos($categoryName, 'science') !== false || strpos($categoryName, 'research') !== false) {
            return 'fas fa-flask';
        } elseif (strpos($categoryName, 'health') !== false || strpos($categoryName, 'medical') !== false) {
            return 'fas fa-heartbeat';
        } elseif (strpos($categoryName, 'entertainment') !== false || strpos($categoryName, 'movie') !== false) {
            return 'fas fa-film';
        } elseif (strpos($categoryName, 'politics') !== false || strpos($categoryName, 'government') !== false) {
            return 'fas fa-landmark';
        } elseif (strpos($categoryName, 'travel') !== false || strpos($categoryName, 'tourism') !== false) {
            return 'fas fa-plane';
        } elseif (strpos($categoryName, 'food') !== false || strpos($categoryName, 'cooking') !== false) {
            return 'fas fa-utensils';
        } elseif (strpos($categoryName, 'education') !== false || strpos($categoryName, 'learning') !== false) {
            return 'fas fa-graduation-cap';
        }
        
        return 'fas fa-newspaper'; // default
    }

    // Function to get category color
    function getCategoryColor($categoryName) {
        $categoryName = strtolower($categoryName);
        
        if (strpos($categoryName, 'technology') !== false || strpos($categoryName, 'tech') !== false) {
            return '#667eea';
        } elseif (strpos($categoryName, 'business') !== false || strpos($categoryName, 'finance') !== false) {
            return '#fa709a';
        } elseif (strpos($categoryName, 'sport') !== false || strpos($categoryName, 'sports') !== false) {
            return '#f093fb';
        } elseif (strpos($categoryName, 'science') !== false || strpos($categoryName, 'research') !== false) {
            return '#4facfe';
        } elseif (strpos($categoryName, 'health') !== false || strpos($categoryName, 'medical') !== false) {
            return '#43e97b';
        } elseif (strpos($categoryName, 'entertainment') !== false || strpos($categoryName, 'movie') !== false) {
            return '#fa8072';
        } elseif (strpos($categoryName, 'politics') !== false || strpos($categoryName, 'government') !== false) {
            return '#a8edea';
        } elseif (strpos($categoryName, 'travel') !== false || strpos($categoryName, 'tourism') !== false) {
            return '#ffecd2';
        } elseif (strpos($categoryName, 'food') !== false || strpos($categoryName, 'cooking') !== false) {
            return '#fed6e3';
        } elseif (strpos($categoryName, 'education') !== false || strpos($categoryName, 'learning') !== false) {
            return '#d299c2';
        }
        
        return '#6c757d'; // default gray
    }
?>

<style>
    /* Modern Post Layout Styles */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --shadow-light: 0 2px 20px rgba(0,0,0,0.08);
        --shadow-medium: 0 8px 40px rgba(0,0,0,0.12);
        --shadow-hover: 0 15px 60px rgba(0,0,0,0.15);
        --border-radius: 20px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        line-height: 1.7;
        color: #2d3748;
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        min-height: 100vh;
    }

    /* Hero Section */
    .post-hero {
        background: var(--primary-gradient);
        color: white;
        padding: 80px 0 40px;
        position: relative;
        overflow: hidden;
    }

    .post-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .post-hero .container {
        position: relative;
        z-index: 2;
    }

    .category-badge-hero {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: var(--transition);
        margin-bottom: 20px;
    }

    .category-badge-hero:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        color: white;
    }

    .post-title-hero {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        text-shadow: 0 2px 20px rgba(0,0,0,0.3);
    }

    .post-meta-hero {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Main Content Container */
    .post-content-wrapper {
        margin-top: 40px;
        position: relative;
        z-index: 10;
    }

    .post-main-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-medium);
        overflow: hidden;
        margin-bottom: 40px;
    }

    .post-featured-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .post-content-body {
        padding: 50px;
    }

    .post-content-text {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #4a5568;
    }

    .post-content-text p {
        margin-bottom: 1.8rem;
    }

    .post-content-text h2, 
    .post-content-text h3, 
    .post-content-text h4 {
        color: #2d3748;
        margin-top: 2.5rem;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .post-content-text h2 {
        font-size: 2rem;
        border-left: 4px solid #667eea;
        padding-left: 20px;
    }

    .post-content-text img {
        max-width: 100%;
        height: auto;
        border-radius: 15px;
        margin: 2rem auto;
        display: block;
        box-shadow: var(--shadow-light);
    }

    .post-content-text blockquote {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-left: 5px solid #667eea;
        padding: 25px 30px;
        margin: 2rem 0;
        border-radius: 0 15px 15px 0;
        font-style: italic;
        font-size: 1.3rem;
        color: #4a5568;
        position: relative;
    }

    .post-content-text blockquote::before {
        content: '"';
        font-size: 4rem;
        color: #667eea;
        position: absolute;
        top: -10px;
        left: 20px;
        font-family: Georgia, serif;
    }

    /* Share Section */
    .share-section {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        padding: 40px;
        border-radius: var(--border-radius);
        margin: 40px 0;
        text-align: center;
    }

    .share-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
    }

    .share-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .share-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
        border: none;
        font-size: 0.95rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-hover);
        text-decoration: none;
        color: white;
    }

    .share-btn:active {
        transform: translateY(-1px);
    }

    .share-btn.facebook { 
        background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%); 
        color: white; 
    }
    
    .share-btn.facebook:hover {
        background: linear-gradient(135deg, #166fe5 0%, #3d9cec 100%);
        color: white;
    }
    
    .share-btn.twitter { 
        background: linear-gradient(135deg, #1da1f2 0%, #0d8bd9 100%); 
        color: white; 
    }
    
    .share-btn.twitter:hover {
        background: linear-gradient(135deg, #1a94e0 0%, #0c7bc7 100%);
        color: white;
    }
    
    .share-btn.whatsapp { 
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); 
        color: white; 
    }
    
    .share-btn.whatsapp:hover {
        background: linear-gradient(135deg, #22c55e 0%, #107c70 100%);
        color: white;
    }
    
    .share-btn.copy { 
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%); 
        color: white; 
    }
    
    .share-btn.copy:hover {
        background: linear-gradient(135deg, #5a6268 0%, #3e464d 100%);
        color: white;
    }

    /* MOST RELATED ARTICLES Section - Style like MOST VIEWED */
    .most-viewed-section {
        margin: 3rem 0;
    }

    .most-viewed-header {
        background: transparent;
        color: #3b82f6;
        padding: 0 0 1rem 0;
        margin-bottom: 0;
        border-radius: 0;
        position: relative;
        overflow: hidden;
        border-bottom: 3px solid #3b82f6;
    }

    .most-viewed-header::before {
        display: none;
    }

    @keyframes move-stripes {
        0% { background-position: 0 0; }
        100% { background-position: 40px 40px; }
    }

    .most-viewed-title {
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 0;
        text-transform: uppercase;
        position: relative;
        z-index: 2;
    }

    .most-viewed-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 0;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        margin-top: 1rem;
    }

    .most-viewed-card {
        display: flex;
        position: relative;
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        background: white;
    }

    .most-viewed-card:last-child {
        border-bottom: none;
    }

    .most-viewed-card:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .most-viewed-image {
        width: 120px;
        height: 90px;
        border-radius: 8px;
        overflow: hidden;
        margin-right: 1rem;
        position: relative;
        flex-shrink: 0;
    }

    .most-viewed-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .most-viewed-card:hover .most-viewed-image img {
        transform: scale(1.1);
    }

    .similarity-badge-corner {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(0,0,0,0.8);
        color: #ffd700;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.65rem;
        font-weight: 600;
    }

    .most-viewed-content {
        flex: 1;
        padding-top: 0.5rem;
    }

    .most-viewed-article-title {
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.3;
        margin-bottom: 0.5rem;
        color: #1f2937;
    }

    .most-viewed-article-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .most-viewed-article-title a:hover {
        color: #3b82f6;
    }

    .most-viewed-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .most-viewed-meta span {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .most-viewed-excerpt {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.4;
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .most-viewed-grid {
            grid-template-columns: 1fr;
        }
        
        .most-viewed-card {
            flex-direction: column;
            padding: 1rem;
        }
        
        .most-viewed-image {
            width: 100%;
            height: 150px;
            margin-right: 0;
            margin-bottom: 1rem;
        }
    }

    /* Related Articles Section */
    .related-articles-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        border-radius: var(--border-radius);
        margin: 40px 0;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .related-articles-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
        animation: float 20s linear infinite;
    }

    .related-articles-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 2;
    }

    .ai-badge {
        background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        color: #1a202c;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes pulse-glow {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .related-articles-grid {
        display: grid;
        gap: 20px;
        position: relative;
        z-index: 2;
    }

    .related-article-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 25px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 20px;
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    .related-article-card:hover {
        transform: translateY(-5px) scale(1.02);
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .related-article-content {
        flex: 1;
    }

    .related-article-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .related-article-title a {
        color: white;
        text-decoration: none;
        transition: var(--transition);
    }

    .related-article-title a:hover {
        color: #ffd700;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .related-article-summary {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .related-article-meta {
        display: flex;
        gap: 20px;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .similarity-score {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(255, 215, 0, 0.2);
        padding: 4px 10px;
        border-radius: 12px;
        color: #ffd700;
        font-weight: 600;
    }

    .related-category {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .related-article-arrow {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
        transition: var(--transition);
    }

    .related-article-card:hover .related-article-arrow {
        transform: translateX(5px);
        color: #ffd700;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        from { transform: translateX(-100px); }
        to { transform: translateX(100px); }
    }

    /* Responsive Design for Related Articles */
    @media (min-width: 768px) {
        .related-articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .related-articles-section {
            padding: 25px 20px;
        }
        
        .related-article-card {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .related-article-meta {
            justify-content: center;
        }
    }

    /* Sidebar Styles */
    .sidebar-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
        margin-bottom: 30px;
        transition: var(--transition);
    }

    .sidebar-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    .sidebar-card-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-card-body {
        padding: 25px;
    }

    .sidebar-post-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
        transition: var(--transition);
    }

    .sidebar-post-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .sidebar-post-item:hover {
        transform: translateX(5px);
    }

    .sidebar-post-img {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .sidebar-post-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 8px;
        color: #2d3748;
    }

    .sidebar-post-content h6 a {
        color: #2d3748;
        text-decoration: none;
        transition: var(--transition);
    }

    .sidebar-post-content h6 a:hover {
        color: #667eea;
    }

    .sidebar-post-meta {
        color: #718096;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Comments Section */
    .comments-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-light);
        padding: 50px;
        margin-top: 40px;
    }

    .comments-header {
        border-bottom: 3px solid #e2e8f0;
        padding-bottom: 20px;
        margin-bottom: 40px;
    }

    .comments-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 10px;
    }

    .comment-item {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        border-left: 4px solid #667eea;
        transition: var(--transition);
    }

    .comment-item:hover {
        transform: translateX(5px);
        box-shadow: var(--shadow-light);
    }

    .comment-avatar {
        width: 60px;
        height: 60px;
        background: var(--primary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .comment-author {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 5px;
    }

    .comment-date {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .comment-content {
        color: #4a5568;
        line-height: 1.6;
        font-size: 1rem;
    }

    /* Comment Form */
    .comment-form {
        background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
        border-radius: var(--border-radius);
        padding: 40px;
        margin-top: 40px;
    }

    .comment-form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px 20px;
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-primary {
        background: var(--primary-gradient);
        border: none;
        padding: 15px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    /* No Comments State */
    .no-comments {
        text-align: center;
        padding: 60px 40px;
        color: #718096;
    }

    .no-comments i {
        color: #cbd5e0;
        margin-bottom: 20px;
    }

    /* Auth Prompt */
    .auth-prompt {
        background: var(--accent-gradient);
        color: white;
        border-radius: var(--border-radius);
        padding: 40px;
        text-align: center;
        margin-top: 40px;
    }

    .auth-prompt h5 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .auth-prompt .btn {
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        margin: 0 10px;
    }

    .btn-light {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        backdrop-filter: blur(10px);
    }

    .btn-outline-light {
        border: 2px solid rgba(255,255,255,0.5);
        color: white;
        background: transparent;
    }

    .btn-light:hover, .btn-outline-light:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-2px);
    }

    /* Comment Messages */
    .comment-message {
        border-radius: 12px;
        padding: 20px 25px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 500;
        animation: slideDown 0.5s ease-out;
    }

    .comment-message.success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 4px solid #28a745;
        color: #155724;
    }

    .comment-message.error {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 4px solid #dc3545;
        color: #721c24;
    }

    .comment-message.warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-left: 4px solid #ffc107;
        color: #856404;
    }

    .comment-message i {
        font-size: 1.2rem;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .post-title-hero {
            font-size: 2.5rem;
        }
        
        .post-content-body {
            padding: 30px 25px;
        }
        
        .share-buttons {
            justify-content: center;
        }
        
        .share-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .comments-section {
            padding: 30px 25px;
        }
        
        .comment-form {
            padding: 30px 25px;
        }
        
        .post-meta-hero {
            gap: 20px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .post-title-hero {
            font-size: 2rem;
        }
        
        .post-content-body {
            padding: 25px 20px;
        }
        
        .share-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .share-btn {
            width: 200px;
            justify-content: center;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .fade-in-up:nth-child(2) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.3s; }

    .post-meta {
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .category-badge {
        background: var(--primary);
        color: var(--white);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-decoration: none;
    }
    
    .post-image {
        border-radius: 12px;
        margin: 2rem 0;
        overflow: hidden;
    }
    
    .post-image img {
        border-radius: 12px;
        width: 100%;
        height: auto;
        max-height: 400px;
        object-fit: cover;
    }
    
    .post-content {
        line-height: 1.8;
        color: var(--text-primary);
        font-size: 1.1rem;
        word-wrap: break-word;
        overflow-wrap: break-word;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .post-content p {
        margin-bottom: 1.5rem;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1rem auto;
        border-radius: 8px;
    }
    
    .post-content table {
        max-width: 100%;
        overflow-x: auto;
        display: block;
        white-space: nowrap;
    }
    
    .post-content h2, .post-content h3 {
        color: var(--text-primary);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .post-share {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid var(--border-light);
    }
    
    .post-share .btn {
        border-radius: 6px;
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
    
    .comments-section {
        border-top: 1px solid var(--border-light);
        padding-top: 2rem;
        margin-top: 3rem;
    }
    
    .comment-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: var(--bg-light);
        transition: border-color 0.2s ease;
    }
    
    .comment-card:hover {
        border-color: var(--border-medium);
    }
    
    .comment-author {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .comment-date {
        color: var(--text-muted);
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .comment-content {
        color: var(--text-primary);
        line-height: 1.6;
    }
    
    .comment-form {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 2rem;
        margin-top: 2rem;
        border: 1px solid var(--border-light);
    }
    
    /* Sidebar Styles */
    .sidebar-card {
        border: 1px solid var(--border-light);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        background: var(--bg-white);
        transition: border-color 0.2s ease;
    }
    
    .sidebar-card:hover {
        border-color: var(--border-medium);
    }
    
    .sidebar-card-header {
        background: var(--primary);
        color: var(--white);
        padding: 1rem 1.25rem;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .sidebar-card-body {
        padding: 1.25rem;
    }
    
    .sidebar-post-item {
        display: flex;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }
    
    .sidebar-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .sidebar-post-img {
        width: 80px;
        height: 60px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .sidebar-post-content h6 {
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    
    .sidebar-post-content h6 a {
        color: var(--text-primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .sidebar-post-content h6 a:hover {
        color: var(--secondary);
    }
    
    .sidebar-post-meta {
        color: var(--text-muted);
        font-size: 0.75rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .post-title {
            font-size: 1.875rem;
        }
        
        .post-content {
            font-size: 1rem;
        }
        
        /* On mobile, allow normal stacking */
        .row {
            display: flex !important;
            flex-wrap: wrap !important;
        }
        
        .col-md-6, .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
    
    /* Ensure sidebar stays on right on medium screens and up */
    @media (min-width: 768px) {
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        
        .post-single {
            max-width: 100%;
            width: 100%;
            margin-right: auto;
        }
        
        .post-content {
            max-width: 100%;
            padding-right: 1rem;
        }
        
        .sidebar-sticky {
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }
        
        /* Ensure proper flexbox layout */
        .row {
            display: flex !important;
        }
        
        .row:first-child {
            flex-wrap: nowrap !important;
        }
        
        .col-md-6 {
            order: 1;
        }
        
        .col-md-4 {
            order: 2;
        }
    }
    
    /* Container and grid fixes */
    .container {
        max-width: 1200px;
        width: 100%;
        padding-left: 15px;
        padding-right: 15px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-left: -15px;
        margin-right: -15px;
    }
    
    .col-md-6, .col-md-4 {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    /* Prevent content from breaking layout */
    .post-wrapper {
        max-width: 100%;
        overflow: hidden;
        width: 100%;
        box-sizing: border-box;
    }
    
    .post-single {
        overflow: hidden;
        max-width: 100%;
        width: 100%;
        box-sizing: border-box;
    }
    
    .post-single * {
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .post-header, .post-content, .post-share {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }
</style>

<main>
    <!-- Hero Section -->
    <section class="post-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb" style="background: transparent; margin: 0; padding: 0;">
                            <li class="breadcrumb-item">
                                <a href="<?= url('/') ?>" style="color: rgba(255,255,255,0.8);">
                                    <i class="fas fa-home me-1"></i>Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="<?= url('show-category/' . $post['cat_id']) ?>" style="color: rgba(255,255,255,0.8);">
                                    <?= $post['category'] ?>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" style="color: white;">
                                Article
                            </li>
                        </ol>
                    </nav>

                    <!-- Category Badge -->
                    <a href="<?= url('show-category/' . $post['cat_id']) ?>" class="category-badge-hero">
                        <i class="<?= getCategoryIcon($post['category']) ?>"></i>
                        <?= $post['category'] ?>
                    </a>

                    <!-- Post Title -->
                    <h1 class="post-title-hero fade-in-up"><?= $post['title'] ?></h1>

                    <!-- Post Meta -->
                    <div class="post-meta-hero fade-in-up">
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <span>By <strong><?= $post['username'] ?></strong></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span><?= date('M d, Y', strtotime($post['created_at'])) ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-comments"></i>
                            <span><?= $post['comments_count'] ?> Comments</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-eye"></i>
                            <span><?= number_format($post['view']) ?> Views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="post-content-wrapper">
        <div class="container">
            <div class="row">
                <!-- Post Content -->
                <div class="col-lg-8">
                    <article class="post-main-card fade-in-up">
                        <!-- Featured Image -->
                        <img src="<?= asset($post['image']) ?>" 
                             alt="<?= $post['title'] ?>" 
                             class="post-featured-image">
                        
                        <!-- Post Content -->
                        <div class="post-content-body">
                            <div class="post-content-text">
                                <?= nl2br($post['body']) ?>
                            </div>

                            <!-- Share Section -->
                            <div class="share-section">
                                <h3 class="share-title">
                                    <i class="fas fa-share-alt me-2"></i>Share this article
                                </h3>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                                       target="_blank" class="share-btn facebook" onclick="openShareWindow(this.href, 'facebook'); return false;">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>&text=<?= urlencode($post['title']) ?>" 
                                       target="_blank" class="share-btn twitter" onclick="openShareWindow(this.href, 'twitter'); return false;">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                    <a href="https://wa.me/?text=<?= urlencode($post['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) ?>" 
                                       target="_blank" class="share-btn whatsapp" onclick="openShareWindow(this.href, 'whatsapp'); return false;">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                    <button onclick="copyToClipboard()" class="share-btn copy">
                                        <i class="fas fa-copy"></i>
                                        <span>Copy Link</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Related Articles Section -->
                            <?php 
                            // Get related articles from same category, ordered by views and recency
                            $relatedPosts = $db->select("
                                SELECT p.*, u.username, c.name as category 
                                FROM posts p 
                                LEFT JOIN users u ON p.user_id = u.id 
                                LEFT JOIN categories c ON p.cat_id = c.id 
                                WHERE p.cat_id = ? AND p.id != ? AND p.status = 1 
                                ORDER BY p.view DESC, p.created_at DESC 
                                LIMIT 3
                            ", [$post['cat_id'], $post['id']]);
                            
                            // If not enough articles in same category, get popular articles from other categories
                            if (!$relatedPosts || $relatedPosts->rowCount() < 3) {
                                $additionalPosts = $db->select("
                                    SELECT p.*, u.username, c.name as category 
                                    FROM posts p 
                                    LEFT JOIN users u ON p.user_id = u.id 
                                    LEFT JOIN categories c ON p.cat_id = c.id 
                                    WHERE p.id != ? AND p.status = 1 
                                    ORDER BY p.view DESC, p.created_at DESC 
                                    LIMIT 3
                                ", [$post['id']]);
                                
                                if ($additionalPosts && $additionalPosts->rowCount() > 0) {
                                    $relatedPosts = $additionalPosts;
                                }
                            }
                            
                            if ($relatedPosts && $relatedPosts->rowCount() > 0) {
                            ?>
                            
                            <!-- AI Related Articles Section - Style like MOST VIEWED -->
                            <div class="most-viewed-section mt-5">
                                <!-- Blue Header Bar -->
                                <div class="most-viewed-header">
                                    <h3 class="most-viewed-title">
                                        RELATED ARTICLES
                                    </h3>
                                </div>
                                
                                <!-- Articles Grid -->
                                <div class="most-viewed-grid">
                                    <?php foreach ($relatedPosts as $index => $relatedPost) { ?>
                                    <div class="most-viewed-card">
                                        <!-- Article Image -->
                                        <div class="most-viewed-image">
                                            <img src="<?= asset($relatedPost['image']) ?>" alt="<?= htmlspecialchars($relatedPost['title']) ?>">
                                            
                                            <!-- Category Badge -->
                                            <div class="similarity-badge-corner">
                                                <i class="fas fa-folder"></i>
                                                <?= htmlspecialchars($relatedPost['category']) ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Article Content -->
                                        <div class="most-viewed-content">
                                            <h4 class="most-viewed-article-title">
                                                <a href="<?= url('show-post/' . $relatedPost['id']) ?>">
                                                    <?= htmlspecialchars($relatedPost['title']) ?>
                                                </a>
                                            </h4>
                                            
                                            <div class="most-viewed-meta">
                                                <span class="author">
                                                    <i class="fas fa-user"></i> <?= $relatedPost['username'] ?>
                                                </span>
                                                <span class="views">
                                                    <i class="fas fa-eye"></i> <?= $relatedPost['view'] ?? 0 ?> views
                                                </span>
                                            </div>
                                            
                                            <p class="most-viewed-excerpt">
                                                <?= isset($relatedPost['summary']) ? substr(strip_tags($relatedPost['summary']), 0, 80) : substr(strip_tags($relatedPost['body']), 0, 80) ?>...
                                            </p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 2rem;">
                        <!-- Popular Posts -->
                        <?php if(!empty($popularPosts)) { ?>
                        <div class="sidebar-card fade-in-up">
                            <div class="sidebar-card-header">
                                <i class="fas fa-fire"></i>
                                <span>Popular Posts</span>
                            </div>
                            <div class="sidebar-card-body">
                                <?php foreach (array_slice($popularPosts, 0, 5) as $index => $popularPost) { ?>
                                <div class="sidebar-post-item" style="animation-delay: <?= $index * 0.1 ?>s;">
                                    <img src="<?= asset($popularPost['image']) ?>" 
                                         alt="<?= $popularPost['title'] ?>"
                                         class="sidebar-post-img">
                                    <div class="sidebar-post-content">
                                        <h6>
                                            <a href="<?= url('show-post/' . $popularPost['id']) ?>">
                                                <?= strlen($popularPost['title']) > 60 ? substr($popularPost['title'], 0, 60) . '...' : $popularPost['title'] ?>
                                            </a>
                                        </h6>
                                        <div class="sidebar-post-meta">
                                            <span>
                                                <i class="fas fa-calendar-alt"></i>
                                                <?= date('M d', strtotime($popularPost['created_at'])) ?>
                                            </span>
                                            <span>
                                                <i class="fas fa-comments"></i>
                                                <?= $popularPost['comments_count'] ?? 0 ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>



                        <!-- Categories -->
                        <?php if(!empty($categories)) { ?>
                        <div class="sidebar-card fade-in-up">
                            <div class="sidebar-card-header">
                                <i class="fas fa-folder"></i>
                                <span>Categories</span>
                            </div>
                            <div class="sidebar-card-body">
                                <?php foreach ($categories as $index => $category) { ?>
                                <div class="d-flex justify-content-between align-items-center mb-3" style="animation-delay: <?= $index * 0.1 ?>s;">
                                    <a href="<?= url('show-category/' . $category['id']) ?>" 
                                       class="text-decoration-none d-flex align-items-center gap-2" 
                                       style="color: #4a5568; font-weight: 500;">
                                        <i class="<?= getCategoryIcon($category['name']) ?>" 
                                           style="color: <?= getCategoryColor($category['name']) ?>; width: 16px;"></i>
                                        <?= $category['name'] ?>
                                    </a>
                                    <span class="badge rounded-pill" 
                                          style="background: <?= getCategoryColor($category['name']) ?>; color: white;">
                                        <?= $category['count'] ?? 0 ?>
                                    </span>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comments Section -->
    <section class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="comments-section fade-in-up">
                    <div class="comments-header">
                        <h2 class="comments-title">
                            <i class="fas fa-comments me-3"></i>
                            Comments (<?= count($comments) ?>)
                        </h2>
                        <p style="color: #718096; margin: 0;">Join the conversation and share your thoughts</p>
                    </div>

                    <!-- Comments List -->
                    <?php if(!empty($comments)) { ?>
                    <div class="comments-list">
                        <?php foreach ($comments as $index => $comment) { ?>
                        <div class="comment-item fade-in-up" style="animation-delay: <?= $index * 0.1 ?>s;">
                            <div class="d-flex gap-3">
                                <div class="comment-avatar">
                                    <?= strtoupper(substr($comment['username'], 0, 1)) ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="comment-author"><?= $comment['username'] ?></div>
                                    <div class="comment-date">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('M d, Y \a\t H:i', strtotime($comment['created_at'])) ?>
                                    </div>
                                    <div class="comment-content">
                                        <?= nl2br(htmlspecialchars($comment['comment'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } else { ?>
                    <div class="no-comments">
                        <i class="fas fa-comments fa-4x"></i>
                        <h4 style="margin: 20px 0 10px; color: #4a5568;">No comments yet</h4>
                        <p>Be the first to share your thoughts about this article!</p>
                    </div>
                    <?php } ?>

                    <!-- Comment Form -->
                    <?php if(isset($_SESSION['user'])) { ?>
                    <div class="comment-form">
                        <?php if(isset($_SESSION['comment_message'])) { ?>
                        <div class="comment-message <?= $_SESSION['comment_message']['type'] ?>">
                            <i class="fas fa-<?= $_SESSION['comment_message']['type'] === 'success' ? 'check-circle' : ($_SESSION['comment_message']['type'] === 'warning' ? 'exclamation-circle' : 'exclamation-triangle') ?>"></i>
                            <span><?= $_SESSION['comment_message']['text'] ?></span>
                        </div>
                        <?php 
                            // Clear message after display
                            unset($_SESSION['comment_message']); 
                        } ?>
                        
                        <h3 class="comment-form-title">
                            <i class="fas fa-edit"></i>
                            <span>Leave a Comment</span>
                        </h3>
                        <form action="<?= url('comment-store') ?>" method="post" class="needs-validation" novalidate>
                            <input type="hidden" name="post_id" value="<?= $id ?>">
                            <div class="mb-4">
                                <label for="comment" class="form-label" style="font-weight: 600; color: #2d3748;">
                                    Your Comment *
                                </label>
                                <textarea class="form-control" 
                                          id="comment" 
                                          name="comment" 
                                          rows="6" 
                                          placeholder="Share your thoughts about this article..."
                                          required></textarea>
                                <div class="invalid-feedback">
                                    Please provide a valid comment.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Post Comment
                            </button>
                        </form>
                    </div>
                    <?php } else { ?>
                    <div class="auth-prompt">
                        <i class="fas fa-sign-in-alt fa-3x mb-3"></i>
                        <h5>Join the Discussion</h5>
                        <p style="margin-bottom: 25px; opacity: 0.9;">
                            Please log in to share your thoughts and engage with other readers
                        </p>
                        <div>
                            <a href="<?= url('login') ?>" class="btn btn-light">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="<?= url('register') ?>" class="btn btn-outline-light">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Enhanced JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Smooth scrolling for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add reading progress indicator
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        z-index: 9999;
        transition: width 0.3s ease;
    `;
    document.body.appendChild(progressBar);

    // Update progress on scroll
    window.addEventListener('scroll', function() {
        const article = document.querySelector('.post-main-card');
        if (article) {
            const articleTop = article.offsetTop;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollTop = window.pageYOffset;
            
            const progress = Math.min(
                Math.max((scrollTop - articleTop + windowHeight * 0.3) / articleHeight * 100, 0),
                100
            );
            
            progressBar.style.width = progress + '%';
        }
    });

    // Enhanced animations for elements coming into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all animated elements
    document.querySelectorAll('.fade-in-up').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });

    // Add hover effects to images
    document.querySelectorAll('.post-content-text img').forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add click-to-zoom functionality for images
    document.querySelectorAll('.post-content-text img, .post-featured-image').forEach(img => {
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() {
            createImageModal(this.src, this.alt);
        });
    });
});

// Copy to clipboard function
function copyToClipboard() {
    const url = window.location.href;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(() => {
            fallbackCopyTextToClipboard(url);
        });
    } else {
        fallbackCopyTextToClipboard(url);
    }
}

// Fallback copy function
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.position = 'fixed';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showToast('Link copied to clipboard!', 'success');
    } catch (err) {
        showToast('Failed to copy link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)' : 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'};
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
        z-index: 10000;
        font-weight: 600;
        transform: translateX(400px);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    `;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Image modal function
function createImageModal(src, alt) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    const img = document.createElement('img');
    img.src = src;
    img.alt = alt;
    img.style.cssText = `
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 20px 80px rgba(0,0,0,0.5);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    `;
    
    modal.appendChild(img);
    document.body.appendChild(modal);
    
    setTimeout(() => {
        modal.style.opacity = '1';
        img.style.transform = 'scale(1)';
    }, 10);
    
    modal.addEventListener('click', function() {
        modal.style.opacity = '0';
        img.style.transform = 'scale(0.9)';
        setTimeout(() => {
            document.body.removeChild(modal);
        }, 300);
    });
    
    // Prevent scrolling when modal is open
    document.body.style.overflow = 'hidden';
    modal.addEventListener('click', function() {
        document.body.style.overflow = 'auto';
    });
}

// Auto-resize textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});

// Add keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+Shift+C to copy URL
    if (e.ctrlKey && e.shiftKey && e.key === 'C') {
        e.preventDefault();
        copyToClipboard();
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        const modal = document.querySelector('[style*="position: fixed"][style*="z-index: 10000"]');
        if (modal) {
            modal.click();
        }
    }
});

// Lazy loading for images in sidebar
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Social sharing functions
function openShareWindow(url, platform) {
    let width = 600;
    let height = 400;
    
    // Customize window size for different platforms
    switch(platform) {
        case 'facebook':
            width = 555;
            height = 580;
            break;
        case 'twitter':
            width = 600;
            height = 280;
            break;
        case 'whatsapp':
            // WhatsApp Web opens in same tab
            window.open(url, '_blank');
            return;
    }
    
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;
    
    const shareWindow = window.open(
        url, 
        `share_${platform}`,
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
    );
    
    if (shareWindow) {
        shareWindow.focus();
    }
}

// Copy to clipboard function
function copyToClipboard() {
    const url = window.location.href;
    
    if (navigator.clipboard && window.isSecureContext) {
        // Modern approach using Clipboard API
        navigator.clipboard.writeText(url).then(() => {
            showToast('Link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            fallbackCopyToClipboard(url);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyToClipboard(url);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showToast('Link copied to clipboard!', 'success');
        } else {
            showToast('Failed to copy link', 'error');
        }
    } catch (err) {
        console.error('Fallback: Could not copy text: ', err);
        showToast('Failed to copy link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Enhanced toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.share-toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = 'share-toast';
    
    const bgColor = type === 'success' ? '#10b981' : 
                   type === 'error' ? '#ef4444' : '#3b82f6';
    
    const icon = type === 'success' ? '✓' : 
                type === 'error' ? '✗' : 'ℹ';
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        font-weight: 500;
        z-index: 10001;
        transform: translateX(400px);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        max-width: 300px;
    `;
    
    toast.innerHTML = `
        <span style="font-size: 16px;">${icon}</span>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Animate out and remove
    setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Add click animations to share buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>

<?php require_once(BASE_PATH . '/template/app/layouts/footer.php'); ?>
<?php

/**
 * Article Recommendation System Integration
 * Functions to interact with Python ML recommendation API
 */

class ArticleRecommendation {
    
    private $apiUrl = 'http://127.0.0.1:5000';
    private $timeout = 10; // seconds
    
    /**
     * Get related articles for a given post title
     * @param string $title - The title of the current article
     * @param int $numRecommendations - Number of recommendations to get
     * @return array - Array of related articles or empty array on failure
     */
    public function getRelatedArticles($title, $numRecommendations = 5) {
        try {
            // Prepare the request data
            $data = json_encode([
                'title' => $title,
                'num_recommendations' => $numRecommendations
            ]);
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/api/recommendations');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]);
            
            // Execute the request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            // Check for cURL errors
            if ($response === false) {
                error_log("ArticleRecommendation: cURL error - " . curl_error($ch));
                return [];
            }
            
            // Check HTTP status
            if ($httpCode !== 200) {
                error_log("ArticleRecommendation: HTTP error - Status code: $httpCode");
                return [];
            }
            
            // Decode the response
            $result = json_decode($response, true);
            
            if (!$result || !isset($result['success']) || !$result['success']) {
                error_log("ArticleRecommendation: API error - " . ($result['error'] ?? 'Unknown error'));
                return [];
            }
            
            return $result['recommendations'] ?? [];
            
        } catch (Exception $e) {
            error_log("ArticleRecommendation: Exception - " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if the recommendation API is healthy
     * @return bool - True if API is responding, false otherwise
     */
    public function isApiHealthy() {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/api/health');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            return $httpCode === 200;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Refresh the recommendation model with latest data
     * @return bool - True if refresh was successful, false otherwise
     */
    public function refreshModel() {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/api/refresh');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Longer timeout for model refresh
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            if ($httpCode === 200) {
                $result = json_decode($response, true);
                return isset($result['success']) && $result['success'];
            }
            
            return false;
            
        } catch (Exception $e) {
            error_log("ArticleRecommendation: Refresh failed - " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get fallback related articles from database when API is down
     * @param int $categoryId - Category ID of current article
     * @param int $currentPostId - Current post ID to exclude
     * @param int $limit - Number of articles to get
     * @return array - Array of related articles from same category
     */
    public function getFallbackRelatedArticles($categoryId, $currentPostId, $limit = 5) {
        try {
            // Database connection parameters
            $host = 'localhost';
            $dbname = 'news-project';
            $username = 'root';
            $password = '';
            
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "SELECT id, title, summary 
                    FROM posts 
                    WHERE cat_id = ? AND id != ? AND status = 1 
                    ORDER BY created_at DESC 
                    LIMIT ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$categoryId, $currentPostId, $limit]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format result to match API response
            $articles = [];
            foreach ($result as $row) {
                $articles[] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'similarity_score' => 0.5 // Default fallback score
                ];
            }
            
            return $articles;
            
        } catch (Exception $e) {
            error_log("ArticleRecommendation: Fallback failed - " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get related articles with fallback support
     * @param string $title - Current article title
     * @param int $categoryId - Current article category ID
     * @param int $currentPostId - Current post ID
     * @param int $numRecommendations - Number of recommendations
     * @return array - Related articles array
     */
    public function getRelatedArticlesWithFallback($title, $categoryId, $currentPostId, $numRecommendations = 5) {
        // Try to get recommendations from ML API first
        $recommendations = $this->getRelatedArticles($title, $numRecommendations);
        
        // If API fails or returns no results, use fallback
        if (empty($recommendations)) {
            $recommendations = $this->getFallbackRelatedArticles($categoryId, $currentPostId, $numRecommendations);
        }
        
        return $recommendations;
    }
}

/**
 * Helper function to get article recommendations
 * @param string $title - Article title
 * @param int $categoryId - Category ID (for fallback)
 * @param int $currentPostId - Current post ID (for fallback)
 * @param int $count - Number of recommendations
 * @return array - Related articles
 */
function getArticleRecommendations($title, $categoryId = null, $currentPostId = null, $count = 5) {
    $recommender = new ArticleRecommendation();
    
    if ($categoryId && $currentPostId) {
        return $recommender->getRelatedArticlesWithFallback($title, $categoryId, $currentPostId, $count);
    } else {
        return $recommender->getRelatedArticles($title, $count);
    }
}

?>
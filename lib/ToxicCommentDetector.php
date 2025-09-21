<?php

/**
 * Toxic Comment Detector
 * 
 * PHP wrapper để giao tiếp với Toxic Comment Classification API
 * Cung cấp chức năng tự động phê duyệt comment dựa trên mức độ toxic
 */

class ToxicCommentDetector
{
    private $api_url;
    private $timeout;
    private $fallback_approval;
    
    public function __construct($api_url = 'http://localhost:5001', $timeout = 10) {
        $this->api_url = rtrim($api_url, '/');
        $this->timeout = $timeout;
        $this->fallback_approval = true; // Fallback to approve nếu API lỗi (comment sẽ đi vào pending review)
    }
    
    /**
     * Kiểm tra comment có toxic không và quyết định auto-approval
     * 
     * @param string $comment Nội dung comment
     * @return array ['should_approve' => bool, 'toxic_probability' => float, 'explanation' => string]
     */
    public function checkComment($comment) {
        if (empty($comment) || trim($comment) === '') {
            return [
                'should_approve' => false,
                'toxic_probability' => 1.0,
                'explanation' => 'Empty comment'
            ];
        }
        
        try {
            $response = $this->callToxicAPI($comment);
            
            if ($response['success']) {
                $toxicProbability = $response['data']['toxic_probability'];
                $shouldApprove = $toxicProbability < 0.5; // < 50% toxic = auto approve
                
                return [
                    'should_approve' => $shouldApprove,
                    'toxic_probability' => $toxicProbability,
                    'explanation' => $shouldApprove ? 'Auto-approved (non-toxic)' : 'Flagged for review (toxic detected)'
                ];
            } else {
                // API error - use fallback keyword checking
                return $this->fallbackKeywordCheck($comment);
            }
            
        } catch (Exception $e) {
            // Exception - use fallback keyword checking
            return $this->fallbackKeywordCheck($comment);
        }
    }
    
    /**
     * Fallback keyword-based toxic detection when API is down
     */
    private function fallbackKeywordCheck($comment) {
        $toxicKeywords = [
            // English toxic words
            'fuck', 'shit', 'damn', 'bitch', 'asshole', 'bastard', 'idiot', 'stupid', 'moron',
            'retard', 'gay', 'fag', 'nigger', 'cunt', 'whore', 'slut', 'piss', 'crap',
            
            // Vietnamese toxic words  
            'đụ', 'địt', 'đéo', 'đm', 'dm', 'cc', 'cặc', 'lồn', 'buồi', 'đĩ', 'mẹ mày',
            'thằng ngu', 'con chó', 'đồ chó', 'súc vật', 'đồ khốn', 'thằng khốn'
        ];
        
        $comment_lower = strtolower($comment);
        $foundToxicWords = [];
        
        foreach ($toxicKeywords as $keyword) {
            if (strpos($comment_lower, strtolower($keyword)) !== false) {
                $foundToxicWords[] = $keyword;
            }
        }
        
        if (!empty($foundToxicWords)) {
            // Found toxic keywords - mark as toxic
            return [
                'should_approve' => false,
                'toxic_probability' => 0.8, // High probability due to explicit keywords
                'explanation' => 'Flagged by keyword filter (API offline): ' . implode(', ', $foundToxicWords)
            ];
        } else {
            // No toxic keywords found - approve
            return [
                'should_approve' => true,
                'toxic_probability' => 0.1, // Low probability
                'explanation' => 'Approved by keyword filter (API offline) - no toxic words detected'
            ];
        }
    }
    
    /**
     * Gọi Toxic Detection API
     */
    private function callToxicAPI($comment) {
        $url = $this->api_url . '/api/check-toxicity';
        
        $data = json_encode([
            'comment' => $comment
        ]);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ]
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return [
                'success' => false,
                'error' => 'CURL Error: ' . $error
            ];
        }
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'HTTP ' . $httpCode . ' - ' . $response
            ];
        }
        
        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error' => 'JSON decode error: ' . json_last_error_msg()
            ];
        }
        
        return [
            'success' => true,
            'data' => $decoded
        ];
    }
    
    /**
     * Kiểm tra health của Toxic API
     */
    public function checkAPIHealth() {
        try {
            $url = $this->api_url . '/api/health';
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTPHEADER => ['Content-Type: application/json']
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error || $httpCode !== 200) {
                return [
                    'healthy' => false,
                    'error' => $error ?: 'HTTP ' . $httpCode
                ];
            }
            
            $decoded = json_decode($response, true);
            return [
                'healthy' => true,
                'data' => $decoded
            ];
            
        } catch (Exception $e) {
            return [
                'healthy' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Set fallback behavior khi API không khả dụng
     */
    public function setFallbackApproval($approve = true) {
        $this->fallback_approval = $approve;
    }
    
    /**
     * Batch check multiple comments
     */
    public function checkMultipleComments($comments) {
        if (empty($comments)) {
            return [];
        }
        
        try {
            $url = $this->api_url . '/api/batch-check-toxicity';
            
            $data = json_encode([
                'comments' => array_values($comments)
            ]);
            
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout * 2, // More time for batch
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data)
                ]
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error || $httpCode !== 200) {
                // Fallback to individual checks
                $results = [];
                foreach ($comments as $index => $comment) {
                    $results[$index] = $this->checkComment($comment);
                }
                return $results;
            }
            
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback to individual checks
                $results = [];
                foreach ($comments as $index => $comment) {
                    $results[$index] = $this->checkComment($comment);
                }
                return $results;
            }
            
            // Process batch results
            $results = [];
            foreach ($decoded['results'] as $index => $result) {
                $toxicProbability = $result['toxic_probability'];
                $shouldApprove = $toxicProbability < 0.5;
                
                $results[$index] = [
                    'should_approve' => $shouldApprove,
                    'toxic_probability' => $toxicProbability,
                    'explanation' => $shouldApprove ? 'Auto-approved (non-toxic)' : 'Flagged for review (toxic detected)'
                ];
            }
            
            return $results;
            
        } catch (Exception $e) {
            // Fallback to individual checks
            $results = [];
            foreach ($comments as $index => $comment) {
                $results[$index] = $this->checkComment($comment);
            }
            return $results;
        }
    }
}
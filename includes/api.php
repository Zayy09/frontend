<?php
/**
 * BAMUK - API Integration Helper
 * Base API URL is pointing to Laravel backend
 */

define('API_BASE_URL', 'http://localhost:8000/api');

function api_get($endpoint) {
    $url = API_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);
    
    if ($error || $httpCode !== 200) {
        return ['success' => false, 'error' => $error ?: "HTTP $httpCode", 'data' => []];
    }
    
    $decoded = json_decode($response, true);
    
    // Support Laravel wrapping style (e.g., response()->json(['data' => ...]))
    $data = isset($decoded['data']) ? $decoded['data'] : $decoded;
    
    return ['success' => true, 'data' => $data];
}

function api_post($endpoint, $postData) {
    $url = API_BASE_URL . $endpoint;
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($postData),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error    = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        return ['success' => false, 'error' => $error, 'data' => null, 'code' => $httpCode];
    }
    
    $decoded = json_decode($response, true);
    $isSuccess = ($httpCode >= 200 && $httpCode < 300);
    
    return ['success' => $isSuccess, 'data' => $decoded, 'code' => $httpCode];
}

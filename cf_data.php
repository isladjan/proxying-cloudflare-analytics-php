<?php
// This script acts as a proxy to forward analytics data to Cloudflare, helping to bypass ad blockers.
// First, CF beacon.js for Web Analytics is downloaded via cf_loader.php (which serves as a redirect)
// And then that script (beacon.js) starts sending data to CF, but through this file

// Set CORS headers to allow requests from your domain
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Set the Cloudflare Analytics endpoint URL
$cloudflareEndpoint = 'https://cloudflareinsights.com/cdn-cgi/rum';

// Get the raw POST data and request headers
$rawData = file_get_contents('php://input');
$contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
$accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : '';

// Initialize cURL
$ch = curl_init($cloudflareEndpoint);

// Set cURL options to match exactly what beacon.min.js expects
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $rawData,
    CURLOPT_HTTPHEADER => [
        'Content-Type: ' . ($contentType ?: 'text/plain'),
        'Accept: ' . ($accept ?: '*/*'),
        'Origin: ' . (isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '*'),
        'Referer: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''),
        'User-Agent: ' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''),
        'Content-Length: ' . strlen($rawData)
    ],
    // Additional options to handle potential SSL issues
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    // Timeout settings
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 3
]);

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    error_log('Cloudflare Analytics Error: ' . curl_error($ch));
    http_response_code(500);
    echo json_encode(['error' => 'Failed to forward analytics data']);
} else {
    // Forward the HTTP status code
    http_response_code($httpCode);

    // Get and forward the content type from Cloudflare's response
    $responseContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if ($responseContentType) {
        header('Content-Type: ' . $responseContentType);
    } else {
        // Default to text/plain if no content type is returned
        header('Content-Type: text/plain');
    }

    // Return the response exactly as received from Cloudflare
    echo $response;
}

curl_close($ch);
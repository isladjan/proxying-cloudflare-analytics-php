<?php
// This script fetches Cloudflare's Analytics beacon.js, So that AdBlockers don't block it if downloaded directly from CF

// Cloudflare Analytics beacon script URL provided by Cloudflare
$remoteScriptUrl = 'https://static.cloudflareinsights.com/beacon.min.js';

// Fetch the script from the remote URL
$scriptContent = file_get_contents($remoteScriptUrl);
if ($scriptContent === false) {
    // If fetching fails, return a 500 error
    header('HTTP/1.1 500 Internal Server Error');
    exit('Error fetching the Cloudflare script.');
}

// Set header for JavaScript content
header('Content-Type: application/javascript');

echo $scriptContent;
<?php
// Backend proxy: keeps the ScrapeCreators API key off the browser.
header('Content-Type: application/json');

$envFile = __DIR__ . '/.env';
$key = null;
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (strpos($line, '=') !== false && $line[0] !== '#') {
            [$k, $v] = explode('=', $line, 2);
            if (trim($k) === 'SCRAPECREATORS_API_KEY') $key = trim($v);
        }
    }
}
// Lightweight status check (does NOT call ScrapeCreators, so no credits used)
if (isset($_GET['status'])) { echo json_encode(['ok' => (bool)$key, 'key_set' => (bool)$key]); exit; }

if (!$key) { http_response_code(500); echo json_encode(['error' => 'Missing SCRAPECREATORS_API_KEY in .env']); exit; }

$company = trim($_GET['company'] ?? '');
$country = strtoupper(trim($_GET['country'] ?? 'US'));
$cursor  = trim($_GET['cursor'] ?? '');
if ($company === '') { http_response_code(400); echo json_encode(['error' => 'Enter a company name']); exit; }

$params = ['companyName' => $company, 'country' => $country, 'trim' => 'true'];
if ($cursor !== '') $params['cursor'] = $cursor;
$url = 'https://api.scrapecreators.com/v1/facebook/adLibrary/company/ads?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['x-api-key: ' . $key],
    CURLOPT_TIMEOUT => 60,
]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($body === false) { http_response_code(502); echo json_encode(['error' => curl_error($ch)]); exit; }
curl_close($ch);

http_response_code($code ?: 200);
echo $body;

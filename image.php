<?php
require_once __DIR__ . '/config.php';
$f = basename($_GET['f'] ?? '');
if ($f === '') { http_response_code(404); exit; }
$path = $uploadDir . DIRECTORY_SEPARATOR . $f;
if (!is_file($path)) { http_response_code(404); exit; }
$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$types = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp'];
if (!isset($types[$ext])) { http_response_code(403); exit; }
header('Content-Type: '.$types[$ext]);
header('Cache-Control: public, max-age=86400');
readfile($path);

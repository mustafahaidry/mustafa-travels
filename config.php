<?php
declare(strict_types=1);
session_start();

const SITE_NAME = 'Mustafa Travels & Tours';
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'ChangeMe123!'; // CHANGE AFTER UPLOAD
const WHATSAPP = '34611473217';
const PHONE1 = '+34 632 234 216';
const PHONE2 = '+34 611 473 217';
const PHONE3 = '+34 631 984 997';
const EMAIL = 'info@mustafatravels.org';
const WEBSITE = 'www.mustafatravels.org';
const ADDRESS = 'Rambla de Badal 141, Local 1 Bajo, Barcelona 08028, Spain';

$defaultDataDir = __DIR__ . '/data';
$renderDataDir = getenv('MUSTAFA_DATA_DIR') ?: '';
$dataDir = $renderDataDir !== '' ? rtrim($renderDataDir, '/\\') : $defaultDataDir;

$defaultUploadDir = __DIR__ . '/uploads';
$renderUploadDir = getenv('MUSTAFA_UPLOAD_DIR') ?: '';
$uploadDir = $renderUploadDir !== '' ? rtrim($renderUploadDir, '/\\') : $defaultUploadDir;

if (!is_dir($dataDir)) { @mkdir($dataDir, 0777, true); }
if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0777, true); }

$dbPath = $dataDir . DIRECTORY_SEPARATOR . 'mustafa_travels.sqlite';

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(
        '<h2>Database could not be opened</h2>' .
        '<p>Database path: <code>' . htmlspecialchars($dbPath, ENT_QUOTES, 'UTF-8') . '</code></p>' .
        '<p>For Render, mount a persistent disk and set <code>MUSTAFA_DATA_DIR</code> to that mount path.</p>' .
        '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>'
    );
}

$pdo->exec("
CREATE TABLE IF NOT EXISTS offers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    subtitle TEXT,
    origin TEXT,
    destination TEXT,
    airline TEXT,
    price REAL,
    currency TEXT DEFAULT 'EUR',
    travel_dates TEXT,
    baggage TEXT,
    badge TEXT,
    image TEXT,
    featured INTEGER DEFAULT 1,
    active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS certificates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    issuer TEXT,
    image TEXT,
    sort_order INTEGER DEFAULT 0,
    active INTEGER DEFAULT 1
);
CREATE TABLE IF NOT EXISTS inquiries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    phone TEXT,
    email TEXT,
    service TEXT,
    message TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);
");

function h(?string $v): string { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
function admin_only(): void {
    if (empty($_SESSION['admin'])) { header('Location: admin.php'); exit; }
}
function upload_image(string $field): ?string {
    if (empty($_FILES[$field]['name']) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','webp'], true)) return null;
    $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    global $uploadDir;
    $dest = $uploadDir . DIRECTORY_SEPARATOR . $name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $dest)) {
        // If uploads are inside the app, return a relative URL. If using Render persistent disk,
        // serve via image.php so files remain accessible.
        if (realpath(dirname($dest)) === realpath(__DIR__ . '/uploads')) return 'uploads/' . $name;
        return 'image.php?f=' . rawurlencode($name);
    }
    return null;
}
?>
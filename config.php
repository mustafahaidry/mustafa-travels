<?php
declare(strict_types=1);

session_start();

const SITE_NAME = 'Mustafa Travels & Tours';
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'Alizaminhaidry786@'; // CHANGE AFTER DEPLOY

const WHATSAPP = '34611473217';
const PHONE1 = '+34 632 234 216';
const PHONE2 = '+34 611 473 217';
const PHONE3 = '+34 631 984 997';

const EMAIL = 'info@mustafatravels.org';
const WEBSITE = 'www.mustafatravels.org';
const ADDRESS = 'Rambla de Badal 141, Local 1 Bajo, Barcelona 08028, Spain';

const SUPABASE_URL = 'https://nrykcfejpdxoodmdmjgz.supabase.co';

const SUPABASE_PUBLISHABLE_KEY =
    'sb_publishable_G1NpOE1LQ_Pngt8xLLgsQg_n9t0Q7eq';


/* =========================================================
   BASIC HELPERS
   ========================================================= */

function h(?string $v): string
{
    return htmlspecialchars(
        $v ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}


/* =========================================================
   ADMIN SECURITY
   ========================================================= */

function admin_only(): void
{
    if (empty($_SESSION['admin'])) {
        header('Location: admin.php');
        exit;
    }
}


/* =========================================================
   SUPABASE KEY
   ========================================================= */

function sb_insert(string $table, array $row): bool
{
    $r = sb_request(
        $table,
        'POST',
        $row,
        [
            'Prefer: return=representation'
        ]
    );

    if (!$r['ok']) {

        $message =
            'SUPABASE INSERT FAILED'
            . ' | TABLE: ' . $table
            . ' | HTTP: ' . $r['code']
            . ' | RESPONSE: ' . (string)$r['raw']
            . ' | CURL: ' . (string)$r['error'];

        error_log($message);

        // TEMPORARY DEBUG - show exact Supabase error
        echo '<div style="
            background:#ffe8e8;
            color:#8b0000;
            border:2px solid #cc0000;
            padding:20px;
            margin:20px;
            font-family:monospace;
            white-space:pre-wrap;
            word-break:break-word;
        ">';
        echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        echo '</div>';
    }

    return $r['ok'];
}

/* =========================================================
   SUPABASE HEADERS
   ========================================================= */

function sb_headers(): array
{
    $key = sb_active_key();

    return [
        'apikey: ' . $key,
        'Accept: application/json',
        'Content-Type: application/json'
    ];
}


/* =========================================================
   SUPABASE REQUEST
   ========================================================= */

function sb_request(
    string $path,
    string $method = 'GET',
    ?array $payload = null,
    array $extraHeaders = []
): array {

    $url =
        rtrim(SUPABASE_URL, '/') .
        '/rest/v1/' .
        $path;

    $ch = curl_init($url);

    curl_setopt_array(
        $ch,
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => array_merge(
                sb_headers(),
                $extraHeaders
            ),
            CURLOPT_TIMEOUT        => 25
        ]
    );

    if ($payload !== null) {

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            )
        );
    }

    $body = curl_exec($ch);

    $code = (int) curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    $err = curl_error($ch);

    curl_close($ch);

    $data = json_decode(
        (string) $body,
        true
    );

    return [
        'ok'    => $code >= 200 && $code < 300,
        'code'  => $code,
        'data'  => is_array($data) ? $data : [],
        'raw'   => $body,
        'error' => $err
    ];
}


/* =========================================================
   SELECT
   ========================================================= */

function sb_select(
    string $table,
    string $query = ''
): array {

    $r = sb_request(
        $table . ($query ? '?' . $query : ''),
        'GET'
    );

    return $r['ok']
        ? $r['data']
        : [];
}


/* =========================================================
   INSERT
   ========================================================= */


/* =========================================================
   UPDATE
   ========================================================= */

function sb_update(
    string $table,
    string $filter,
    array $row
): bool {

    $r = sb_request(
        $table . '?' . $filter,
        'PATCH',
        $row,
        [
            'Prefer: return=minimal'
        ]
    );

    return $r['ok'];
}


/* =========================================================
   DELETE
   ========================================================= */

function sb_delete(
    string $table,
    string $filter
): bool {

    $r = sb_request(
        $table . '?' . $filter,
        'DELETE',
        null,
        [
            'Prefer: return=minimal'
        ]
    );

    return $r['ok'];
}


/* =========================================================
   IMAGE UPLOAD
   ========================================================= */

function upload_image(
    string $field
): ?string {

    if (
        empty($_FILES[$field]['name']) ||
        $_FILES[$field]['error'] !== UPLOAD_ERR_OK
    ) {
        return null;
    }

    /*
     * Maximum image size: 2MB
     */

    if (
        (int) $_FILES[$field]['size']
        > 2 * 1024 * 1024
    ) {
        return null;
    }

    $ext = strtolower(
        pathinfo(
            $_FILES[$field]['name'],
            PATHINFO_EXTENSION
        )
    );

    /*
     * Allowed image formats
     */

    if (
        !in_array(
            $ext,
            [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ],
            true
        )
    ) {
        return null;
    }

    $mime =
        mime_content_type(
            $_FILES[$field]['tmp_name']
        )
        ?: 'image/jpeg';

    return
        'data:' .
        $mime .
        ';base64,' .
        base64_encode(
            file_get_contents(
                $_FILES[$field]['tmp_name']
            )
        );
}

?>

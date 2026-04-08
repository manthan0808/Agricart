<?php
// Load .env file manually if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . '=' . trim($value));
    }
}

$db_host = getenv('DB_HOST');
$db_port = getenv('DB_PORT') ?: '5432';
$db_name = getenv('DB_NAME');
$db_user = getenv('DB_USER');
$db_pass = getenv('DB_PASS');
$db_type = getenv('DB_TYPE') ?: 'pgsql';


// Try connecting using a full DATABASE_URL (for cloud deployment)
$database_url = getenv('DATABASE_URL');

try {
    if ($database_url) {
        $conn = new PDO($database_url);
    } else {
        // Fallback for local development or individual variables
        if ($db_type === 'pgsql') {
            $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;sslmode=require";
        } else {
            $dsn = "mysql:host=$db_host;dbname=$db_name";
        }
        $conn = new PDO($dsn, $db_user, $db_pass);
    }

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

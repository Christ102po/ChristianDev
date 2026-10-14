<?php
// Security: CLI-only admin provisioning. Run: php setup-admin.php admin@example.com 'StrongPasswordHere'
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/includes/bootstrap.php';
if ($argc !== 3 || !filter_var($argv[1], FILTER_VALIDATE_EMAIL) || strlen($argv[2]) < 12) {
    fwrite(STDERR, "Usage: php setup-admin.php email@example.com 'password-with-12-or-more-characters'\n"); exit(1);
}
try {
    $stmt = db()->prepare('INSERT INTO admins (email,password_hash) VALUES (?,?)');
    $stmt->execute([strtolower($argv[1]),password_hash($argv[2], PASSWORD_DEFAULT)]);
    echo "Admin created. Delete no files; this script only works from command line.\n";
} catch (PDOException $e) { fwrite(STDERR, "Could not create admin (check database setup or duplicate email).\n"); exit(1); }

<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','samesite'=>'Lax']);
    session_start();
}
require_once __DIR__ . '/db.php';
const SITE_NAME = 'Christian Fundal | Full-Stack Web Developer';
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { $base = rtrim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'); if (str_ends_with($base, '/admin')) $base = substr($base, 0, -6); return ($base === '/' ? '' : $base) . '/' . ltrim($path, '/'); }
function token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function verify_token(): void { if (!hash_equals(token(), (string)($_POST['csrf'] ?? ''))) { http_response_code(419); exit('Invalid security token. Go back and refresh the page.'); } }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function logged_in(): bool { return isset($_SESSION['admin_id']); }
function require_admin(): void { if (!logged_in()) redirect('admin/login.php'); }
function flash(string $type, string $msg): void { $_SESSION['flash'] = ['type'=>$type, 'text'=>$msg]; }
function take_flash(): ?array { $x = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $x; }
function project_status(string $status): string { return ['in_progress'=>'In development','completed'=>'Completed','concept'=>'Concept'] [$status] ?? 'Project'; }

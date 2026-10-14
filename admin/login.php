<?php
require dirname(__DIR__).'/includes/bootstrap.php';
if (logged_in()) redirect('admin/index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_token();
 if (time() < (int)($_SESSION['login_locked_until']??0)) $error='Too many attempts. Please try again later.';
 else {
  $email=strtolower(trim((string)($_POST['email']??'')));$password=(string)($_POST['password']??'');
  try { $stmt=db()->prepare('SELECT * FROM admins WHERE email=? LIMIT 1');$stmt->execute([$email]);$admin=$stmt->fetch(); }
  catch(PDOException $e){$admin=false;$error='Cannot connect to the database.';}
  if($admin && password_verify($password,$admin['password_hash'])) {session_regenerate_id(true);$_SESSION['admin_id']=(int)$admin['id'];$_SESSION['admin_email']=$admin['email'];unset($_SESSION['login_fails'],$_SESSION['login_locked_until']);redirect('admin/index.php');}
  if (!$error) {$fails=($_SESSION['login_fails']??0)+1;$_SESSION['login_fails']=$fails;if($fails>=5){$_SESSION['login_locked_until']=time()+900;$error='Too many attempts. Try again in 15 minutes.';}else $error='Invalid email or password.';}
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin login · Christian/Dev</title><link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>"></head><body><main class="login-wrap"><form method="post" class="login-card"><a class="brand" href="<?= e(url('index.php')) ?>"><span class="brand-mark">C<span>.</span></span> CHRISTIAN/DEV</a><h1>Welcome back.</h1><p>Sign in to manage your portfolio.</p><?php if($error): ?><div class="flash error" role="alert"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="csrf" value="<?= e(token()) ?>"><label>EMAIL<input type="email" name="email" required autocomplete="username"></label><label>PASSWORD<input type="password" name="password" required autocomplete="current-password"></label><button class="btn btn-primary" type="submit">Sign in ↗</button><a class="back-link" href="<?= e(url('index.php')) ?>">← Back to portfolio</a></form></main></body></html>

<?php
require __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php#contact');
verify_token();
if (!empty($_POST['website'])) {flash('success','Thank you for reaching out.');redirect('index.php#contact');}
$name=trim((string)($_POST['name']??''));$email=trim((string)($_POST['email']??''));$subject=trim((string)($_POST['subject']??''));$message=trim((string)($_POST['message']??''));
if (mb_strlen($name)<2 || mb_strlen($name)>150 || !filter_var($email,FILTER_VALIDATE_EMAIL) || mb_strlen($email)>190 || mb_strlen($subject)<2 || mb_strlen($subject)>190 || mb_strlen($message)<10 || mb_strlen($message)>5000) {flash('error','Please check your details and try again.');redirect('index.php#contact');}
if (time()-(int)($_SESSION['last_contact']??0)<60) {flash('error','Please wait a minute before sending another message.');redirect('index.php#contact');}
try { $stmt=db()->prepare('INSERT INTO messages (sender_name,sender_email,subject,message) VALUES (?,?,?,?)');$stmt->execute([$name,$email,$subject,$message]);$_SESSION['last_contact']=time();flash('success','Message received! Thank you for getting in touch.'); }
catch(PDOException $e) {flash('error','Your message could not be saved. Please email me directly instead.');}
redirect('index.php#contact');

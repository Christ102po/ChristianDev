<?php
require dirname(__DIR__).'/includes/bootstrap.php';require_admin();if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}verify_token();$id=(int)($_POST['id']??0);if($id>0){$stmt=db()->prepare('DELETE FROM projects WHERE id=?');$stmt->execute([$id]);flash('success','Project deleted.');}redirect('admin/projects.php');

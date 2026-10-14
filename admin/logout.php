<?php
require dirname(__DIR__).'/includes/bootstrap.php';if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}verify_token();$_SESSION=[];session_regenerate_id(true);redirect('admin/login.php');

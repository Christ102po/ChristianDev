<?php
require dirname(__DIR__).'/includes/bootstrap.php';require_admin();require __DIR__.'/_layout.php';
$id=max(0,(int)($_GET['id']??$_POST['id']??0));
$defaults=['title'=>'','slug'=>'','category'=>'Web application','summary'=>'','description'=>'','technologies'=>'','status'=>'in_progress','github_url'=>'','demo_url'=>'','image_path'=>'','featured'=>1,'sort_order'=>0];
$p=$defaults;
if ($id) { $stmt=db()->prepare('SELECT * FROM projects WHERE id=?');$stmt->execute([$id]);$found=$stmt->fetch();if(!$found){http_response_code(404);exit('Project not found');}$p=$found; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 verify_token();
 foreach(['title','slug','category','summary','description','technologies','status','github_url','demo_url'] as $key) $p[$key]=trim((string)($_POST[$key]??''));
 $p['sort_order']=(int)($_POST['sort_order']??0);$p['featured']=isset($_POST['featured'])?1:0;
 if (!$p['slug']) $p['slug']=strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$p['title']),'-'));
 if(strlen($p['title'])<2 || strlen($p['title'])>160 || !preg_match('/^[a-z0-9-]{2,180}$/',$p['slug']) || strlen($p['category'])>100 || strlen($p['summary'])<10 || strlen($p['summary'])>500 || strlen($p['description'])<10 || strlen($p['technologies'])>255 || !in_array($p['status'],['concept','in_progress','completed'],true)) $error='Please check required fields and field lengths.';
 foreach(['github_url','demo_url'] as $field) if($p[$field] && (!filter_var($p[$field],FILTER_VALIDATE_URL) || !in_array(parse_url($p[$field],PHP_URL_SCHEME),['https','http'],true) || strlen($p[$field])>500)) $error='Links must be valid HTTP or HTTPS URLs.';
 if (!$error && isset($_FILES['screenshot']) && $_FILES['screenshot']['error']!==UPLOAD_ERR_NO_FILE) {
  $file=$_FILES['screenshot'];
  if($file['error']!==UPLOAD_ERR_OK || $file['size']>3*1024*1024) $error='Screenshot must be 3 MB or smaller.';
  else { $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);$types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
   if(!isset($types[$mime]) || !getimagesize($file['tmp_name'])) $error='Upload a valid JPG, PNG, or WebP image.';
   else { $dir=dirname(__DIR__).'/uploads';if(!is_dir($dir) && !mkdir($dir,0755,true)) $error='Cannot create uploads folder.';
    if(!$error){$path='uploads/'.bin2hex(random_bytes(16)).'.'.$types[$mime];if(move_uploaded_file($file['tmp_name'],dirname(__DIR__).'/'.$path))$p['image_path']=$path;else $error='Screenshot upload failed.';}
   }
  }
 }
 if(!$error) {
  try { $cols=['title','slug','category','summary','description','technologies','status','github_url','demo_url','image_path','featured','sort_order'];$values=array_map(fn($col)=>$p[$col],$cols);
   if($id){$query='UPDATE projects SET '.implode(',',array_map(fn($col)=>"$col=?",$cols)).' WHERE id=?';$values[]=$id;}
   else $query='INSERT INTO projects ('.implode(',',$cols).') VALUES ('.implode(',',array_fill(0,count($cols),'?')).')';
   $stmt=db()->prepare($query);$stmt->execute($values);flash('success','Project saved successfully.');redirect('admin/projects.php');
  } catch(PDOException $e){$error='Could not save project. Make sure the slug is unique.';}
 }
}
admin_header($id?'Edit project':'Add project');if($error):?><div class="flash error"><?= e($error) ?></div><?php endif; ?>
<form class="edit-form admin-panel" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= e(token()) ?>"><input type="hidden" name="id" value="<?= $id ?>"><div class="form-row"><label>PROJECT TITLE<input name="title" value="<?= e($p['title']) ?>" required maxlength="160"></label><label>URL SLUG<input name="slug" value="<?= e($p['slug']) ?>" placeholder="automatically from title" pattern="[a-z0-9-]{2,180}"></label></div><div class="form-row"><label>CATEGORY<input name="category" value="<?= e($p['category']) ?>" required maxlength="100"></label><label>STATUS<select name="status"><option value="concept" <?= $p['status']==='concept'?'selected':'' ?>>Concept</option><option value="in_progress" <?= $p['status']==='in_progress'?'selected':'' ?>>In development</option><option value="completed" <?= $p['status']==='completed'?'selected':'' ?>>Completed</option></select></label></div><label>SHORT DESCRIPTION<textarea name="summary" rows="2" required minlength="10" maxlength="500"><?= e($p['summary']) ?></textarea></label><label>CASE STUDY / PROJECT DETAILS<textarea name="description" rows="8" required minlength="10"><?= e($p['description']) ?></textarea></label><label>TECHNOLOGIES (comma separated)<input name="technologies" value="<?= e($p['technologies']) ?>" maxlength="255" placeholder="PHP, JavaScript, MySQL"></label><div class="form-row"><label>GITHUB URL (optional)<input type="url" name="github_url" value="<?= e($p['github_url']) ?>"></label><label>LIVE DEMO URL (optional)<input type="url" name="demo_url" value="<?= e($p['demo_url']) ?>"></label></div><label>PROJECT SCREENSHOT (JPG, PNG, WEBP / max 3 MB)<input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp"></label><?php if($p['image_path']): ?><p>Current screenshot: <a href="<?= e(url($p['image_path'])) ?>" target="_blank" rel="noopener noreferrer">View image ↗</a></p><?php endif; ?><div class="form-row"><label>DISPLAY ORDER<input name="sort_order" type="number" value="<?= (int)$p['sort_order'] ?>"></label><label class="check-label"><input type="checkbox" name="featured" value="1" <?= $p['featured']?'checked':'' ?>> Display on homepage</label></div><div class="form-actions"><button class="btn btn-primary" type="submit">Save project</button><a class="btn btn-outline" href="<?= e(url('admin/projects.php')) ?>">Cancel</a></div></form><?php admin_footer(); ?>

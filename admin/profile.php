<?php
require dirname(__DIR__) . '/includes/bootstrap.php';
require_admin();
require __DIR__ . '/_layout.php';
$error = '';
try {
    $stmt = db()->prepare("SELECT setting_value FROM portfolio_settings WHERE setting_key = 'profile_image'");
    $stmt->execute();
    $current = (string)($stmt->fetchColumn() ?: '');
} catch (PDOException $ex) {
    $current = '';
    $error = 'Profile photo database table is missing. For an existing installation, import database/upgrade-profile-photo.sql in phpMyAdmin.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_token();
    if (!$error) {
        if (isset($_POST['remove_photo'])) {
            try {
                $stmt = db()->prepare("INSERT INTO portfolio_settings (setting_key,setting_value) VALUES ('profile_image','') ON DUPLICATE KEY UPDATE setting_value=''");
                $stmt->execute();
                // Existing file is intentionally retained so removal cannot break another reference.
                flash('success', 'Profile photo removed.');
                redirect('admin/profile.php');
            } catch (PDOException $ex) { $error = 'Unable to remove the profile photo.'; }
        } else {
            $file = $_FILES['photo'] ?? null;
            if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) $error = 'Choose a picture to upload.';
            elseif ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 3 * 1024 * 1024 || $file['size'] < 1) $error = 'Picture must be 3 MB or smaller.';
            elseif (!is_uploaded_file($file['tmp_name'])) $error = 'Invalid upload.';
            else {
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
                $types = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
                $dimensions = @getimagesize($file['tmp_name']);
                if (!isset($types[$mime]) || !$dimensions || $dimensions[0] > 6000 || $dimensions[1] > 6000) {
                    $error = 'Select a valid JPG, PNG or WebP image (maximum 6000 × 6000 pixels).';
                } else {
                    $folder = dirname(__DIR__) . '/uploads';
                    if (!is_dir($folder) && !mkdir($folder, 0755, true)) $error='Cannot create uploads directory.';
                    if (!$error) {
                        $relative = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $types[$mime];
                        $absolute = dirname(__DIR__) . '/' . $relative;
                        if (!move_uploaded_file($file['tmp_name'], $absolute)) $error='Unable to save the uploaded photo. Check uploads folder permissions.';
                        else {
                            try {
                                $stmt = db()->prepare("INSERT INTO portfolio_settings (setting_key, setting_value) VALUES ('profile_image', ?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");
                                $stmt->execute([$relative]);
                                flash('success', 'Profile photo updated successfully.');
                                redirect('admin/profile.php');
                            } catch (PDOException $ex) {
                                @unlink($absolute);
                                $error='Database error while saving the photo.';
                            }
                        }
                    }
                }
            }
        }
    }
}
admin_header('Profile photo');
if ($error): ?><div class="flash error" role="alert"><?= e($error) ?></div><?php endif; ?>
<div class="admin-panel photo-settings">
 <h2>Your homepage picture</h2>
 <p>Upload a clear photo of yourself. Your picture will appear inside the blue animated frame on the homepage and resize automatically for mobile screens.</p>
 <div class="photo-preview-box">
  <?php if ($current): ?><img src="<?= e(url($current)) ?>" alt="Current profile photo" class="photo-preview" id="photo-preview">
  <?php else: ?><div class="photo-placeholder" id="photo-placeholder">CF</div><img class="photo-preview" id="photo-preview" alt="New photo preview" hidden><?php endif; ?>
 </div>
 <form method="post" enctype="multipart/form-data" class="edit-form" id="photo-upload-form">
   <input type="hidden" name="csrf" value="<?= e(token()) ?>">
   <label for="photo">Choose photo (JPG, PNG or WebP, maximum 3 MB)</label>
   <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" required>
   <div class="form-actions"><button class="btn btn-primary" type="submit">Save profile photo</button><a class="btn btn-outline" href="<?= e(url('index.php')) ?>" target="_blank" rel="noopener noreferrer">Preview website ↗</a></div>
 </form>
 <?php if ($current): ?><form method="post" onsubmit="return confirm('Remove your profile picture?')"><input type="hidden" name="csrf" value="<?= e(token()) ?>"><button class="btn btn-outline" type="submit" name="remove_photo" value="1">Remove photo</button></form><?php endif; ?>
</div>
<script>
const photoInput=document.getElementById('photo'), photoPreview=document.getElementById('photo-preview');
photoInput.addEventListener('change',()=>{const file=photoInput.files?.[0];if(!file)return;const allowed=['image/jpeg','image/png','image/webp'];if(!allowed.includes(file.type)||file.size>3*1024*1024){alert('Please select a JPG, PNG, or WebP under 3 MB.');photoInput.value='';return;}const url=URL.createObjectURL(file);photoPreview.onload=()=>URL.revokeObjectURL(url);photoPreview.src=url;photoPreview.hidden=false;const placeholder=document.getElementById('photo-placeholder');if(placeholder)placeholder.hidden=true;});
</script>
<?php admin_footer();

<?php
function page_header(string $title, string $description = ''): void {
    $current = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    $flash = take_flash();
    ?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#05080f"><meta name="description" content="<?= e($description ?: 'Explore the web development projects, skills and experience of Christian Calvario Fundal.') ?>"><title><?= e($title) ?> · <?= SITE_NAME ?></title><link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>"><script src="<?= e(url('assets/js/main.js')) ?>" defer></script></head>
<body><a class="skip-link" href="#main">Skip to content</a><div class="ambient ambient-one"></div><div class="ambient ambient-two"></div>
<header class="site-header"><div class="container header-inner"><a class="brand" href="<?= e(url('index.php')) ?>" aria-label="Christian Fundal home"><span class="brand-mark">C<span>.</span></span><span>CHRISTIAN<span class="brand-muted">/DEV</span></span></a><button class="menu-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="site-nav"><span></span><span></span><span></span></button><nav id="site-nav" class="site-nav" aria-label="Main navigation"><a href="<?= e(url('index.php')) ?>#home">Home</a><a href="<?= e(url('index.php')) ?>#about">About</a><a href="<?= e(url('index.php')) ?>#skills">Skills</a><a href="<?= e(url('index.php')) ?>#projects">Projects</a><a href="<?= e(url('index.php')) ?>#contact">Contact</a><a class="nav-resume" href="<?= e(url('index.php')) ?>#contact">Let's talk <span aria-hidden="true">↗</span></a></nav></div></header>
<?php if ($flash): ?><div class="flash container <?= e($flash['type']) ?>" role="status"><?= e($flash['text']) ?></div><?php endif; ?>
<?php }
function page_footer(): void { ?>
<footer class="footer"><div class="container footer-inner"><div><a class="brand" href="<?= e(url('index.php')) ?>"><span class="brand-mark">C<span>.</span></span> CHRISTIAN/DEV</a><p>Designing ideas. Building solutions.</p></div><div class="footer-right"><div class="footer-social"><a href="https://github.com/Christ102po" target="_blank" rel="noopener noreferrer">GitHub ↗</a><a href="https://linkedin.com/in/christianfundal" target="_blank" rel="noopener noreferrer">LinkedIn ↗</a><a href="mailto:christianfundalofficial@email.com">Email ↗</a></div><small>© <?= date('Y') ?> Christian Calvario Fundal. All rights reserved.</small></div></div></footer>
</body></html>
<?php }

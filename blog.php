<?php require_once 'config/config.php'; ?>
<?php
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id=? AND status=1");
$stmt->execute([$id]);
$blog = $stmt->fetch();

if (!$blog) { header('Location: index.php'); exit(); }

$all_blogs = $pdo->query("SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC LIMIT 6")->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<div class="container">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo t('home'); ?></a></li>
            <li class="breadcrumb-item active">Blog</li>
        </ol>
    </nav>

    <div class="text-center mb-4 ad-container ad-hero" data-ad-pos="hero"><?php
    $hero_ad = getRandomAd('hero');
    if ($hero_ad) echo renderAdCode($hero_ad); else echo '<span class="text-muted small">Advertisement</span>';
    ?></div>
    <div class="row">
        <div class="col-lg-8">
            <article>
                <h1 class="fw-bold mb-3"><?php echo __($blog['title_bn'], $blog['title_en']); ?></h1>
                <div class="text-muted mb-3">
                    <i class="far fa-user me-1"></i><?php echo $blog['author'] ?: 'Editor'; ?>
                    <span class="ms-3"><i class="far fa-clock me-1"></i><?php echo date('F d, Y', strtotime($blog['created_at'])); ?></span>
                </div>
<?php
$imageUrl = getImageUrl($blog['image'], 'https://via.placeholder.com/800x450?text=No+Image');
?>
<img src="<?php echo $imageUrl; ?>" class="img-fluid w-100 mb-4 rounded" style="max-height:450px;object-fit:cover" alt="">
                <div class="blog-content <?php echo $lang === 'bn' ? 'bangla-text' : ''; ?>">
                    <?php echo nl2br(__($blog['content_bn'], $blog['content_en'])); ?>
                </div>
            </article>
        </div>
        <div class="col-lg-4">
            <aside class="sidebar">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white fw-bold">More Blogs</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($all_blogs as $ab): if ($ab['id'] == $id) continue; ?>
                            <li class="list-group-item">
                                <a href="blog.php?id=<?php echo $ab['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($ab['title_bn'], $ab['title_en']), 60); ?></a>
                                <small class="text-muted d-block"><?php echo timeAgo($ab['created_at']); ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

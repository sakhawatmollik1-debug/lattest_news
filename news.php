<?php require_once 'config/config.php'; ?>
<?php
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT n.*, c.name_bn as cat_bn, c.name_en as cat_en, c.slug as cat_slug FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.id=? AND n.status=1");
$stmt->execute([$id]);
$news = $stmt->fetch();

if (!$news) {
    header('Location: index.php');
    exit();
}

$pdo->prepare("UPDATE news SET views = views + 1 WHERE id=?")->execute([$id]);

$related = $pdo->prepare("SELECT * FROM news WHERE category_id=? AND id!=? AND status=1 ORDER BY created_at DESC LIMIT 4");
$related->execute([$news['category_id'], $id]);
$related_items = $related->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<div class="container">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo t('home'); ?></a></li>
            <li class="breadcrumb-item"><a href="category.php?slug=<?php echo $news['cat_slug']; ?>"><?php echo __($news['cat_bn'], $news['cat_en']); ?></a></li>
            <li class="breadcrumb-item active"><?php echo limitText(__($news['title_bn'], $news['title_en']), 50); ?></li>
        </ol>
    </nav>

    <div class="text-center mb-4 ad-container ad-hero" data-ad-pos="hero"><?php
    $hero_ad = getRandomAd('hero');
    if ($hero_ad) echo renderAdCode($hero_ad); else echo '<span class="text-muted small">Advertisement</span>';
    ?></div>
    <div class="row">
        <div class="col-lg-8">
            <article class="news-detail">
                <h1 class="fw-bold mb-3"><?php echo __($news['title_bn'], $news['title_en']); ?></h1>
                <div class="news-meta mb-3">
                    <span class="badge bg-danger me-2"><?php echo __($news['cat_bn'], $news['cat_en']); ?></span>
                    <small class="text-muted me-3"><i class="far fa-user me-1"></i><?php echo t('by'); ?> <?php echo $news['author'] ?: 'Editor'; ?></small>
                    <small class="text-muted me-3"><i class="far fa-clock me-1"></i><?php echo date('F d, Y h:i A', strtotime($news['created_at'])); ?></small>
                    <small class="text-muted"><i class="far fa-eye me-1"></i><?php echo $news['views'] + 1; ?> <?php echo t('views'); ?></small>
                </div>

<?php
$imageUrl = getImageUrl($news['image'], 'https://via.placeholder.com/800x450?text=No+Image');
?>
<img src="<?php echo $imageUrl; ?>" class="img-fluid w-100 mb-4 rounded" style="max-height:500px;object-fit:cover" alt="">

                <div class="news-content <?php echo $lang === 'bn' ? 'bangla-text' : ''; ?>">
                    <?php $content = __($news['content_bn'], $news['content_en']);
                    $lines = explode("\n", $content);
                    $chunks = array_chunk($lines, 15);
                    foreach ($chunks as $i => $chunk) {
                        echo nl2br(implode("\n", $chunk));
                        if ($i < count($chunks) - 1) {
                            $inline_ad = getRandomAd('news_detail');
                            if ($inline_ad):
                                echo '<div class="text-center my-4 ad-container" data-ad-pos="news_detail">' . renderAdCode($inline_ad) . '</div>';
                            endif;
                        }
                    } ?>
                </div>

                <div class="share-buttons mt-4 pt-3 border-top">
                    <strong><?php echo t('follow_us'); ?>: </strong>
                    <a href="#" class="btn btn-sm btn-primary me-1"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="btn btn-sm btn-info me-1"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-danger"><i class="fab fa-whatsapp"></i></a>
                </div>
            </article>

            <?php
            $detail_ad = getRandomAd('news_detail');
            if ($detail_ad): ?>
            <div class="text-center my-4 ad-container" data-ad-pos="news_detail"><?php echo renderAdCode($detail_ad); ?></div>
            <?php endif; ?>

            <?php if (!empty($related_items)): ?>
            <section class="related-section mt-4">
                <h4 class="fw-bold border-bottom pb-2 mb-3"><?php echo t('related_news'); ?></h4>
                <div class="row">
                    <?php foreach ($related_items as $rel): ?>
                    <div class="col-md-3 col-6 mb-3">
<div class="news-card-sm">
                             <a href="news.php?id=<?php echo $rel['id']; ?>">
                                 <img src="<?php echo getImageUrl($rel['image'], 'https://via.placeholder.com/300x200?text=No+Image'); ?>" class="img-fluid w-100" style="height:120px;object-fit:cover" alt="">
                             </a>
                            <h6 class="mt-2">
                                <a href="news.php?id=<?php echo $rel['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($rel['title_bn'], $rel['title_en']), 60); ?></a>
                            </h6>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <aside class="sidebar">
                <?php
                $sidebar_ad = getRandomAd('sidebar');
                if ($sidebar_ad): ?>
                <div class="mb-4 ad-container" data-ad-pos="sidebar"><?php echo renderAdCode($sidebar_ad); ?></div>
                <?php endif; ?>

                <div class="card mb-4">
                    <div class="card-header bg-danger text-white fw-bold"><?php echo t('popular_news'); ?></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php
                            $popular = $pdo->query("SELECT * FROM news WHERE status=1 ORDER BY views DESC LIMIT 5")->fetchAll();
                            foreach ($popular as $i => $p): ?>
                            <li class="list-group-item">
                                <a href="news.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($p['title_bn'], $p['title_en']), 60); ?></a>
                                <small class="text-muted d-block"><i class="far fa-eye"></i> <?php echo $p['views']; ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <?php
                $latest_side = $pdo->query("SELECT * FROM news WHERE status=1 ORDER BY created_at DESC LIMIT 5")->fetchAll(); ?>
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white fw-bold"><?php echo t('latest_news'); ?></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($latest_side as $ls): ?>
                            <li class="list-group-item">
                                <a href="news.php?id=<?php echo $ls['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($ls['title_bn'], $ls['title_en']), 50); ?></a>
                                <small class="text-muted d-block"><?php echo timeAgo($ls['created_at']); ?></small>
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

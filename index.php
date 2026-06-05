<?php require_once 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>
<?php
// Featured news
$featured = $pdo->query("SELECT n.*, c.name_bn as cat_bn, c.name_en as cat_en, c.slug as cat_slug FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.status=1 AND n.featured=1 ORDER BY n.created_at DESC LIMIT 4")->fetchAll();
// Latest news
$latest = $pdo->query("SELECT n.*, c.name_bn as cat_bn, c.name_en as cat_en, c.slug as cat_slug FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.status=1 ORDER BY n.created_at DESC")->fetchAll();
// Popular news
$popular = $pdo->query("SELECT n.*, c.name_bn as cat_bn, c.name_en as cat_en FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.status=1 ORDER BY n.views DESC LIMIT 5")->fetchAll();
// Categories for section
$home_cats = $pdo->query("SELECT * FROM categories WHERE status=1 ORDER BY id LIMIT 6")->fetchAll();
?>
<div class="container">
    <div class="text-center mb-4 ad-container ad-hero" data-ad-pos="hero"><?php
    $hero_ad = getRandomAd('hero');
    if ($hero_ad) echo renderAdCode($hero_ad); else echo '<span class="text-muted small">Advertisement</span>';
    ?></div>
    <?php if (!empty($featured)): ?>
    <section class="featured-section mb-4">
        <div class="row">
            <div class="col-lg-8">
<div class="featured-main position-relative">
                     <a href="news.php?id=<?php echo $featured[0]['id']; ?>">
                         <img src="<?php echo getImageUrl($featured[0]['image'], 'https://via.placeholder.com/800x450?text=No+Image'); ?>" class="img-fluid w-100" style="height:400px;object-fit:cover" alt="<?php echo __($featured[0]['title_bn'], $featured[0]['title_en']); ?>">
                         <div class="featured-overlay">
                             <span class="badge bg-danger mb-2"><?php echo __($featured[0]['cat_bn'], $featured[0]['cat_en']); ?></span>
                             <h2 class="text-white mb-1"><?php echo __($featured[0]['title_bn'], $featured[0]['title_en']); ?></h2>
                             <small class="text-white-50"><i class="far fa-clock me-1"></i><?php echo timeAgo($featured[0]['created_at']); ?></small>
                         </div>
                     </a>
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="row h-100">
                    <?php for ($i = 1; $i < count($featured) && $i < 4; $i++): ?>
                    <div class="col-12 mb-3">
<div class="featured-side position-relative h-100">
                         <a href="news.php?id=<?php echo $featured[$i]['id']; ?>">
                             <img src="<?php echo getImageUrl($featured[$i]['image'], 'https://via.placeholder.com/400x200?text=No+Image'); ?>" class="img-fluid w-100" style="height:120px;object-fit:cover" alt="<?php echo __($featured[$i]['title_bn'], $featured[$i]['title_en']); ?>">
                             <div class="featured-overlay-sm">
                                 <h6 class="text-white mb-0"><?php echo limitText(__($featured[$i]['title_bn'], $featured[$i]['title_en']), 60); ?></h6>
                             </div>
                         </a>
                     </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-8">
            <section class="latest-section mb-4">
                <div class="section-header d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h4 class="fw-bold mb-0"><i class="fas fa-clock text-danger me-2"></i><?php echo t('latest_news'); ?></h4>
                </div>
                <div class="row">
                    <?php foreach ($latest as $item): ?>
                    <div class="col-md-6 mb-4">
<div class="news-card card border-0 h-100 shadow-sm">
                             <a href="news.php?id=<?php echo $item['id']; ?>">
                                 <img src="<?php echo getImageUrl($item['image'], 'https://via.placeholder.com/400x250?text=No+Image'); ?>" class="card-img-top" style="height:200px;object-fit:cover" alt="<?php echo __($item['title_bn'], $item['title_en']); ?>">
                             </a>
                            <div class="card-body">
                                <span class="badge bg-danger mb-2"><?php echo __($item['cat_bn'], $item['cat_en']); ?></span>
                                <h5 class="card-title">
                                    <a href="news.php?id=<?php echo $item['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo limitText(__($item['title_bn'], $item['title_en']), 80); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">
                                    <i class="far fa-clock me-1"></i><?php echo timeAgo($item['created_at']); ?>
                                    <span class="ms-2"><i class="far fa-eye me-1"></i><?php echo $item['views']; ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php
            $news_between_ad = getRandomAd('news_between');
            if ($news_between_ad): ?>
            <div class="text-center mb-4 ad-container" data-ad-pos="news_between"><?php echo renderAdCode($news_between_ad); ?></div>
            <?php endif; ?>
            <?php foreach ($home_cats as $hc):
                $cat_news = $pdo->prepare("SELECT n.*, c.name_bn as cat_bn, c.name_en as cat_en FROM news n LEFT JOIN categories c ON n.category_id = c.id WHERE n.status=1 AND n.category_id=? ORDER BY n.created_at DESC LIMIT 4");
                $cat_news->execute([$hc['id']]);
                $cat_items = $cat_news->fetchAll();
                if (empty($cat_items)) continue;
            ?>
            <section class="category-section mb-4">
                <div class="section-header d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h4 class="fw-bold mb-0"><i class="fas fa-folder text-danger me-2"></i><?php echo __($hc['name_bn'], $hc['name_en']); ?></h4>
                    <a href="category.php?slug=<?php echo $hc['slug']; ?>" class="btn btn-sm btn-outline-danger"><?php echo t('more'); ?></a>
                </div>
                <div class="row">
                    <?php foreach ($cat_items as $ci): ?>
                    <div class="col-md-3 col-6 mb-3">
<div class="news-card-sm">
                             <a href="news.php?id=<?php echo $ci['id']; ?>">
                                 <img src="<?php echo getImageUrl($ci['image'], 'https://via.placeholder.com/300x200?text=No+Image'); ?>" class="img-fluid w-100" style="height:130px;object-fit:cover" alt="">
                             </a>
                            <h6 class="mt-2">
                                <a href="news.php?id=<?php echo $ci['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($ci['title_bn'], $ci['title_en']), 60); ?></a>
                            </h6>
                            <small class="text-muted"><i class="far fa-clock"></i> <?php echo timeAgo($ci['created_at']); ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endforeach; ?>
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
                            <?php foreach ($popular as $i => $p): ?>
                            <li class="list-group-item">
                                <div class="d-flex">
                                    <span class="badge bg-<?php echo $i < 3 ? 'danger' : 'secondary'; ?> me-2 mt-1"><?php echo $i + 1; ?></span>
                                    <div>
                                        <a href="news.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($p['title_bn'], $p['title_en']), 60); ?></a>
                                        <small class="text-muted d-block"><i class="far fa-eye"></i> <?php echo $p['views']; ?> <?php echo t('views'); ?></small>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php
                $blog_items = $pdo->query("SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC LIMIT 3")->fetchAll();
                if ($blog_items): ?>
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white fw-bold"><i class="fas fa-blog me-2"></i>Blog</div>
                    <div class="card-body">
                        <?php foreach ($blog_items as $bi): ?>
                        <div class="mb-3 pb-2 border-bottom">
                            <h6><a href="blog.php?id=<?php echo $bi['id']; ?>" class="text-decoration-none"><?php echo limitText(__($bi['title_bn'], $bi['title_en']), 50); ?></a></h6>
                            <small class="text-muted"><i class="far fa-clock"></i> <?php echo timeAgo($bi['created_at']); ?></small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>


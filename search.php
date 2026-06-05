<?php require_once 'config/config.php'; ?>
<?php include 'includes/header.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-8">
            <h3 class="fw-bold mb-3">
                <i class="fas fa-search text-danger me-2"></i>
                <?php echo t('search'); ?>: "<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
            </h3>
            <?php
            $q = $_GET['q'] ?? '';
            if ($q):
                $search_term = "%$q%";
                $stmt = $pdo->prepare("
                    (SELECT 'news' as type, n.id, n.title_bn, n.title_en, n.image, n.created_at, c.name_bn as cat_bn, c.name_en as cat_en
                     FROM news n LEFT JOIN categories c ON n.category_id = c.id
                     WHERE (n.title_bn LIKE ? OR n.title_en LIKE ? OR n.content_bn LIKE ? OR n.content_en LIKE ?) AND n.status=1)
                    UNION ALL
                    (SELECT 'blog' as type, b.id, b.title_bn, b.title_en, b.image, b.created_at, NULL as cat_bn, NULL as cat_en
                     FROM blogs b
                     WHERE (b.title_bn LIKE ? OR b.title_en LIKE ? OR b.content_bn LIKE ? OR b.content_en LIKE ?) AND b.status=1)
                    ORDER BY created_at DESC");
                $stmt->execute([$search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term]);
                $results = $stmt->fetchAll();
                if (empty($results)): ?>
                    <div class="alert alert-info"><?php echo t('no_news'); ?></div>
                <?php else: ?>
                    <p class="text-muted"><?php echo count($results); ?> result(s) found</p>
                    <div class="row">
                        <?php foreach ($results as $item): ?>
                        <div class="col-md-6 mb-4">
                            <div class="news-card card border-0 h-100 shadow-sm">
                                <a href="<?php echo $item['type'] === 'news' ? 'news.php?id=' . $item['id'] : 'blog.php?id=' . $item['id']; ?>">
                                    <img src="<?php echo $item['image'] ? 'uploads/' . $item['image'] : 'https://via.placeholder.com/400x250?text=No+Image'; ?>" class="card-img-top" style="height:200px;object-fit:cover" alt="">
                                </a>
                                <div class="card-body">
                                    <span class="badge bg-<?php echo $item['type'] === 'news' ? 'danger' : 'dark'; ?> mb-2">
                                        <?php echo $item['type'] === 'news' ? __($item['cat_bn'], $item['cat_en']) : 'Blog'; ?>
                                    </span>
                                    <h5 class="card-title">
                                        <a href="<?php echo $item['type'] === 'news' ? 'news.php?id=' . $item['id'] : 'blog.php?id=' . $item['id']; ?>" class="text-decoration-none text-dark">
                                            <?php echo limitText(__($item['title_bn'], $item['title_en']), 80); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        <i class="far fa-clock me-1"></i><?php echo timeAgo($item['created_at']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning"><?php echo t('search'); ?> term required.</div>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <aside class="sidebar">
                <?php
                $sidebar_ad = getRandomAd('sidebar');
                if ($sidebar_ad): ?>
                <div class="mb-4 ad-container"><?php echo renderAdCode($sidebar_ad); ?></div>
                <?php endif; ?>
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white fw-bold"><?php echo t('popular_news'); ?></div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <?php
                            $popular = $pdo->query("SELECT * FROM news WHERE status=1 ORDER BY views DESC LIMIT 5")->fetchAll();
                            foreach ($popular as $i => $p): ?>
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
            </aside>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

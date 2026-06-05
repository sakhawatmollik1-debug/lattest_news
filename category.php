<?php require_once 'config/config.php'; ?>
<?php
$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug=? AND status=1");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: index.php');
    exit();
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM news WHERE category_id=? AND status=1");
$count_stmt->execute([$category['id']]);
$total_news = $count_stmt->fetchColumn();
$total_pages = ceil($total_news / $limit);

$stmt = $pdo->prepare("SELECT * FROM news WHERE category_id=? AND status=1 ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$stmt->execute([$category['id']]);
$news_list = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<div class="container">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php"><?php echo t('home'); ?></a></li>
            <li class="breadcrumb-item active"><?php echo __($category['name_bn'], $category['name_en']); ?></li>
        </ol>
    </nav>

    <div class="text-center mb-4 ad-container ad-hero" data-ad-pos="hero"><?php
    $hero_ad = getRandomAd('hero');
    if ($hero_ad) echo renderAdCode($hero_ad); else echo '<span class="text-muted small">Advertisement</span>';
    ?></div>
    <div class="row">
        <div class="col-lg-8">
            <div class="section-header border-bottom pb-2 mb-3">
                <h3 class="fw-bold"><i class="fas fa-folder text-danger me-2"></i><?php echo __($category['name_bn'], $category['name_en']); ?></h3>
            </div>

            <?php if (!empty($news_list)): ?>
            <div class="row">
                <?php foreach ($news_list as $item): ?>
                <div class="col-md-6 mb-4">
<div class="news-card card border-0 h-100 shadow-sm">
                         <a href="news.php?id=<?php echo $item['id']; ?>">
                             <img src="<?php echo getImageUrl($item['image'], 'https://via.placeholder.com/400x250?text=No+Image'); ?>" class="card-img-top" style="height:200px;object-fit:cover" alt="">
                         </a>
                        <div class="card-body">
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

            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?slug=<?php echo $slug; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?slug=<?php echo $slug; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?slug=<?php echo $slug; ?>&page=<?php echo $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <?php else: ?>
            <div class="alert alert-info"><?php echo t('no_news'); ?></div>
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
                            $popular = $pdo->query("SELECT * FROM news WHERE category_id={$category['id']} AND status=1 ORDER BY views DESC LIMIT 5")->fetchAll();
                            foreach ($popular as $p): ?>
                            <li class="list-group-item">
                                <a href="news.php?id=<?php echo $p['id']; ?>" class="text-decoration-none text-dark"><?php echo limitText(__($p['title_bn'], $p['title_en']), 60); ?></a>
                                <small class="text-muted d-block"><i class="far fa-eye"></i> <?php echo $p['views']; ?></small>
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

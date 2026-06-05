<?php
require 'config/config.php';
$ads = $pdo->query("SELECT * FROM ads WHERE status=1 ORDER BY position")->fetchAll(PDO::FETCH_ASSOC);
foreach ($ads as $a) {
    echo "ID={$a['id']} pos={$a['position']} code_len=" . strlen($a['code']??'') . " image={$a['image']} link={$a['link']} status={$a['status']}\n";
}
echo "Total active: " . count($ads) . "\n\n";
foreach (['hero','news_between','sidebar','header','footer','news_detail','popup'] as $pos) {
    $ad = getRandomAd($pos);
    echo "pos=$pos => " . ($ad ? "found ID={$ad['id']}" : "NULL") . "\n";
}

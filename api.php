<?php

include_once __DIR__ . '/inc/blog.php';

$dir   = __DIR__ . '/content/';
$count = (int)($_GET['count'] ?? 10);
$page  = (int)($_GET['page'] ?? 1);

// 最新記事を取得
$posts = load_all_articles($dir);
$posts = array_slice($posts, $count * ($page - 1), $count);

// APIのアクセス許可
header("Access-Control-Allow-Origin: *");

// JSON出力
echo json_encode($posts, JSON_UNESCAPED_UNICODE);
return;

<?php
// Qwel start!
require_once __DIR__ . '/inc/Qwel.php';
$cms = new Qwel();

// APIのアクセス許可
header("Access-Control-Allow-Origin: *");

// JSON出力
echo json_encode($cms->get_posts(), JSON_UNESCAPED_UNICODE);
return;

<?php
require_once __DIR__ . '/ContentLoader.php';
require_once __DIR__ . '/ContentNavigation.php';
require_once __DIR__ . '/Parsedown.php';

class Qwel {
  protected int $count;
  protected int $page;
  protected array $posts = [];
  protected array $article = [];

  public function __construct() {
    $slug = $_GET['slug'] ?? 'about';
    $this->count = (int)($_GET['count'] ?? 10);
    $this->page  = (int)($_GET['page'] ?? 1);

    $dir    = dirname(__DIR__) . '/content/';
    $loader = new ContentLoader($dir);

    // 全記事取得
    $this->posts = $loader->load();

    // 個別記事取得
    $this->article = $loader->find($slug);
  }

  // Aboutページか否か
  public function is_about() {
    return isset($_GET['slug']) ? false : true;
  }

  // 全記事からページ数を切り取って取得
  public function get_posts(int $page = null, int $count = null): array {
    $page  = $page  ?? $this->page;
    $count = $count ?? $this->count;
    return array_slice($this->posts, $count * ($page - 1), $count);
  }

  // タイトル取得
  public function get_title() {
    return $this->article['title'];
  }

  // 記事内容取得
  public function get_content() {
    $parsedown = new Parsedown();
    return $parsedown->text($this->article['content']);
  }

  // パンくず生成
  public function get_breadcrumb(): string {
    $nav = new ContentNavigation([
      'topLabel'    => 'QWEL in Action',
      'topPath'     => 'https://qwel.design',
      'subdirLabel' => 'Blog',
      'subdirPath'  => '/'
    ]);

    return $this->is_about() ?
      $nav->breadcrumb() : $nav->breadcrumb($this->article);
  }
}

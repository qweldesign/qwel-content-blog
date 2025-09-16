<?php
require_once __DIR__ . '/inc/blog.php';
require_once __DIR__ . '/inc/parsedown.php';
$Parsedown = new Parsedown();

$dir   = __DIR__ . '/content/';
$slug  = $_GET['slug'] ?? 'about';
$count = (int)($_GET['count'] ?? 10);
$page  = (int)($_GET['page'] ?? 1);

$posts = load_all_articles($dir);
$posts = array_slice($posts, $count * ($page - 1), $count);

$post = find_article_by_slug($slug, $dir);
if (!$post) {
  $content = "<h2>記事が見つかりません</h2><p>指定された記事は存在しないか、削除された可能性があります。</p>";
}
$content = $Parsedown->text($post['content']);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>鋤と鉋とプログラミング - 子ども向けプログラミング教室講師ブログ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&family=Zen+Old+Mincho:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" href="/favicon.ico">
  </head>
  <body>
    <header class="header">
      <div id="logo"><a href="https://qwel.design/"><img src="/logo_animation.svg" alt="QWEL in Action"></a></div>
      <ul id="breadcrumb" class="breadcrumb">
        <li class="breadcrumb__item">
          <a href="https://qwel.design/">QWEL in Action</a>
        </li>
        <?php if ($slug !== 'about' && $post) { ?>
          <li class="breadcrumb__item">
            <a href="/">Blog</a>
          </li>
          <li class="breadcrumb__item is-current">
            <span><?php echo $post['title']; ?></span>
          </li>
        <?php } else { ?>
          <li class="breadcrumb__item is-current">
            <span>Blog</span>
          </li>
        <?php } ?>
      </ul>
    </header>
    <main class="main">
      <div class="main__container">
        <h1 class="main__title">
          <div class="main__titleInner">鋤と鉋とプログラミング<span>―子ども向けプログラミング教室講師ブログ</span></div>
        </h1>
        <div class="main__content">
          <article class="article">
            <?php echo $content ?>
          </article>
        </div>
        <div class="main__aside">
          <h2>新着記事</h2>
          <ul id="post" class="post">
          <?php
          foreach ($posts as $post) {
          ?>
            <li class="post__item">
              <a href="/<?php echo $post['slug']; ?>/">
                <img class="post__image" src="/images/<?php echo $post['img']; ?>">
                <span class="post__date"><?php echo $post['date']; ?></span>
                <span class="post__title"><?php echo $post['title']; ?></span>
              </a>
            </li>
          <?php
          }
          ?>
          </ul>
        </div>
      </div>
    </main>
    <footer id="footer" class="footer">
      <div class="footer__inner">
        <a class="footer__item footer__item--github" href="https://github.com/taigoito" target="_blank" rel="noopener">
          <svg class="icon icon--si icon--github icon--md" width="36" height="36" aria-hidden="true">
            <use href="/icons.svg#si-github"></use>
          </svg>
          <span>GitHub</span>
        </a>
        <a class="footer__item footer__item--contact" href="https://tools.qwel.design/contact-form/" target="_blank" rel="noopener">
          <svg class="icon icon--si icon--mail icon--md" width="36" height="36" aria-hidden="true">
            <use href="/icons.svg#si-mail"></use>
          </svg>
          <span>Contact Me</span>
        </a>
        <small class="footer__copyright"></small>
      </div>
    </footer>
    <script src="/init.js" type="module"></script>
  </body>
</html>

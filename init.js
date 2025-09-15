// Posts
(async () => {
  // 記事データを取得
  const res = await fetch('./posts.json');
  const content = await res.json();

  // Breadcrumb
  const createBreadcrumbItem = (tag, text, href = null, isCurrent = false) => {
    const li = document.createElement('li');
    li.classList.add('breadcrumb__item');
    if (isCurrent) li.classList.add('breadcrumb__item--current');

    const el = document.createElement(tag);
    el.textContent = text;
    if (href) el.setAttribute('href', href);

    li.appendChild(el);
    return li;
  };

  const updateBreadcrumb = () => {
    const params = new URLSearchParams(location.search);
    const slug = params.get('post') || 'about';

    const title = content.find(post => post.slug === slug)?.title || '';

    const breadcrumb = document.getElementById('breadcrumb');
    breadcrumb.innerHTML = '';

    // Root
    breadcrumb.appendChild(createBreadcrumbItem('a', 'QWEL in Action', 'https://qwel.design/'));

    if (slug == 'about') {
      // /Blog/
      breadcrumb.appendChild(createBreadcrumbItem('span', 'Blog', null, true));
    } else {
      // /Blog/?post=xxx
      breadcrumb.appendChild(createBreadcrumbItem('a', 'Blog', './blog/'));
      breadcrumb.appendChild(createBreadcrumbItem('span', title, null, true));
    }
  };

  // 最初と、URL変更時に毎回実行
  updateBreadcrumb();
  window.addEventListener('popstate', updateBreadcrumb);

  // Posts
  const list = document.getElementById('post');
  const template = document.getElementById('postTemplate');
  content.forEach((post) => {
    const item = template.content.cloneNode(true);
    const a = item.querySelector('a');
    const img = a.querySelector('.post__image');
    const date = a.querySelector('.post__date');
    const title = a.querySelector('.post__title');
    a.setAttribute('href', `./?post=${post.slug}`);
    img.setAttribute('src', `./images${post.imgUrl}`)
    date.textContent = post.date;
    title.textContent = post.title;
    list.appendChild(item);
  });

})();


// Auto Copyright
import AutoCopyright from './js/autoCopyright.js';
new AutoCopyright(2019, 'QWEL.DESIGN');

// Back To Top
import BackToTop from './js/backToTop.js';
new BackToTop();

// Drawer Menu
//import DrawerMenu from './js/drawerMenu.js';
//new DrawerMenu();

// Preloader
//import Preloader from './js/preloader.js';
//new Preloader();

// Router
import Router from './js/router.js';
new Router('/blog/', 'about');

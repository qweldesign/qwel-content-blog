/**
 * Router
 * このファイルは QWEL Project の一部です。
 * Part of the QWEL Project © QWEL.DESIGN 2025
 * Licensed under GPL v3 – see https://qwel.design/
 */

import { parse } from './marked.js';

export default class Router {
  constructor(basePath, initialSlug) {
    this.basePath = basePath;
    this.initialSlug = initialSlug;
    this.handeEvents();

    // hashchangeイベントを手動発火 (仮運用)
    //window.dispatchEvent(new HashChangeEvent('hashchange'));

    // popstateイベントを手動発火
    window.dispatchEvent(new PopStateEvent('popstate'));
  }

  handeEvents() {
    //window.addEventListener('hashchange', () => {
    //  const slug = location.hash ? location.hash.slice(1) : this.initialSlug;
    window.addEventListener('popstate', () => {
      const params = new URLSearchParams(location.search);
      const slug = params.get('post') || 'about';
      this.fetchContent(slug);
    });
    
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a');
      if (a && a.href) {
        const url = new URL(a.href);
        if (url.origin === location.origin && url.pathname.startsWith(this.basePath)) {
          e.preventDefault();
          history.pushState(null, '', `./${url.search}`);
          window.dispatchEvent(new PopStateEvent('popstate'));
        }
      }
    });
  }

  async fetchContent(slug) {
    const res = await fetch(`./content/${slug}.md`);
    const txt = await res.text();
    const article = document.getElementById('article');
    this.pageLeave(article).then(() => {
      article.innerHTML = parse(txt);
      return this.pageEnter(article);
    }).then(() => {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
      });
    });
  }

  async pageEnter(elem) {
    elem.style.visibility = '';
    return this.animationEnd(elem, () => {
      elem.classList.add('article--pageEnter');
    }).then(() => {
      elem.classList.remove('article--pageEnter');
    });
  }

  async pageLeave(elem) {
    return this.animationEnd(elem, () => {
      elem.classList.add('article--pageLeave');
    }).then(() => {
      elem.style.visibility = 'hidden';
      elem.classList.remove('article--pageLeave');
    });
  }

  animationEnd(elem, func) {
    let callback;
    const promise = new Promise((resolve, reject) => {
      callback = () => resolve(elem);
      elem.addEventListener('animationend', callback);
    });
    func();
    promise.then((elem) => {
      elem.removeEventListener('animationend', callback);
    });
    return promise;
  }
}

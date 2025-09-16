<?php

function load_all_articles($dir = './content/') {
	$articles = [];
	foreach (glob("$dir/*.md") as $file) {
		$parsed = parse_article($file);
		if ($parsed) $articles[] = $parsed;
	}
	usort($articles, fn($a, $b) => strcmp($b['date'], $a['date']));
	return $articles;
}

function find_article_by_slug($slug, $dir = './content/') {
	foreach (glob("$dir/*.md") as $file) {
		$parsed = parse_article($file);
		if ($parsed && $parsed['slug'] === $slug) return $parsed;
	}
	return null;
}

function parse_article($file) {
	$content = file_get_contents($file);
	if (!$content) return null;

	if (preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $content, $matches)) {
		$front = $matches[1];
		$body  = $matches[2];

		$meta = [];
		foreach (explode("\n", trim($front)) as $line) {
			if (preg_match('/^(\w+):\s*"?(.+?)"?$/', trim($line), $m)) {
				$meta[$m[1]] = $m[2];
			}
		}

		$meta['content'] = $body;
		return $meta;
	}

	return null;
}

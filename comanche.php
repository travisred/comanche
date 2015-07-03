<?php

	include('src/Utility.php');
	include('src/Site.php');
	include('src/Post.php');

	$site = new Site();

	$post_files = glob("md/*");

	foreach ($post_files as $file_name) {

		$file_content = Post::readFileContent($file_name);
		$post = new Post($file_name, $file_content);

		$post->setPostMetadata();
		$post->setPostContent();
		$post->setPostHtml($site);

		$path = 'site/' . $post->file_name . '/index.html';
		$post->writeFileContent($path, $post->html);

		$site->posts[] = $post;
	}

	function sortByDate($a, $b) {
		if ($a->date == $b->date) {
			return 0;
		}
		return ($a->date > $b->date) ? -1 : 1;
	}

	uasort($site->posts, 'sortByDate');

	$index_posts = array_slice($site->posts, 0, $site->front_page_count);

	$site->setIndexHtml($index_posts);
	$site->writeFileContent('site/index.html', $site->html);

	$site->setArchiveHtml();
	$site->writeFileContent('site/archive/index.html', $site->archive_html);

	$site->setRssXml();
	$site->writeFileContent('site/rss.xml', $site->xml);

	$site->setSitemapTxt();
	$site->writeFileContent('site/sitemap.txt', $site->sitemap);

	$site->cleanSiteFolder();


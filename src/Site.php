<?php
	class Site extends Utility 
	{
		public $index_template_variables = [
			'site_root',
			'site_title',
			'site_description',
			'content',
		];

		public $rss_template_variables = [
			'site_root',
			'site_title',
			'site_description',
			'content',
			'last_rss_build_date',
		];	

		public function __construct() 
		{
			$this->content = '';
			$this->posts = [];
			Utility::setConfig($this);
		}

		public function setIndexHtml($posts) 
		{
			$this->html = $this::readFileContent('templates/index_template.html');
			foreach ($posts as $post) {
				$format = '<div class="post-content"><h1><a href="%s/%s">%s</a></h1><hr><p><i>%s</i></p>%s</div>';
				$this->content .= sprintf($format, $this->site_root, $post->file_name, $post->title, $post->date, $post->content);
			}	
			$vars = get_object_vars($this);
			foreach ($this->index_template_variables as $name) {
				$this->html = str_replace('{{'.$name.'}}', $vars[$name], $this->html);
			}
		}

		public function setArchiveHtml() 
		{
			$this->archive_html = $this::readFileContent('templates/archive_template.html');
			$this->content = '';
			foreach ($this->posts as $post) {
				$format = '<h3><a href="%s/%s">%s - %s</a></h3>';
				$this->content .= sprintf($format, $this->site_root, $post->file_name, $post->date, $post->title);
			}	
			$vars = get_object_vars($this);
			foreach ($this->index_template_variables as $name) {
				$this->archive_html = str_replace('{{'.$name.'}}', $vars[$name], $this->archive_html);
			}
		}

		public function setRssXml() 
		{
			$this->xml = $this::readFileContent('templates/rss_template.xml');
			$this->content = '';
			foreach ($this->posts as $post) {
				$format = '<item>
				  <title>%s</title>
				  <link>%s/%s</link>
				  <pubDate>%s</pubDate>
				 </item>';
				$this->content .= sprintf($format, $post->title, $this->site_root, $post->file_name, $post->date);
			}
			$this->last_rss_build_date = date('r', time());	
			$vars = get_object_vars($this);
			foreach ($this->rss_template_variables as $name) {
				$this->xml = str_replace('{{'.$name.'}}', $vars[$name], $this->xml);
			}
		}

		public function setSitemapTxt() 
		{
			$this->sitemap = '';
			foreach ($this->posts as $post) {
				$format = "%s/%s\n";
				$this->sitemap .= sprintf($format, $this->site_root, $post->file_name);
			}
		}

		public function cleanSiteFolder()
		{
			$current_posts = array_map(function($post) { return $post->file_name ;}, $this->posts);
			$allowed_items = array_merge(['archive', 'css', 'img', 'index.html', 'rss.xml', 'sitemap.txt'], $current_posts);

			$dirs = glob('site/*');

			foreach ($dirs as $dir) {
				if (!in_array(split('/', $dir)[1], $allowed_items)) {
					unlink($dir . '/index.html');
					rmdir($dir);
				}
			}
		}
	}


<?php

	include('lib/Parsedown.php');

	class Post extends Utility 
	{
		public $post_template_variables = [
			'site_root',
			'site_title',
			'site_description',
			'title',
			'author',
			'date',
			'content',
		];

		public function __construct($file_name, $raw_post) 
		{
			$this->file_name = explode('/', $file_name)[1];
			$this->raw_post = explode("\n", $raw_post);
		}

		public function setPostMetadata() 
		{
			$this->title = $this->raw_post[0];	
			$this->date = $this->raw_post[1];	
			$this->author = $this->raw_post[2];	
			$this->is_published = $this->raw_post[3];	
		}
		
		public function setPostContent() 
		{
			$Parsedown = new Parsedown();
			$content = implode(array_splice($this->raw_post, 5), "\n");
			$this->content = $Parsedown->text($content);
		}

		public function setPostHtml($site) 
		{
			$this->html = $this::readFileContent('templates/template.html');
			$this->site_title = $site->site_title;
			$this->site_description = $site->site_description;
			$this->site_root = $site->site_root;
			$post_vars = get_object_vars($this);
			foreach ($this->post_template_variables as $name) {
				$this->html = str_replace('{{'.$name.'}}', $post_vars[$name], $this->html);
			}
		}
	}


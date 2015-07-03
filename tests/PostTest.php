<?php
class PostTest extends PHPUnit_Framework_TestCase
{
    public function testSetPostMetaData()
    {
		$post = new Post('md/a-file-name', "Title\n2015-07-02\nAuthor\npublished\n\nContent");

		$post->setPostMetaData();

		$expected = new stdClass();
		$expected_values = [
			'title' => 'Title', 
			'date' => '2015-07-02',
			'author' => 'Author',
			'is_published' => 'published',
		];
		foreach ($expected_values as $key => $value) {
			$expected->$key = $value;
		}
		unset($post->file_name);
		unset($post->raw_post);
		unset($post->post_template_variables);

		$this->assertEquals((array) $expected, (array) $post);
    }

	public function testSetPostContent()
	{
		$post = new Post('md/a-file-name', "Title\n2015-07-02\nAuthor\npublished\n\nContent");

		$post->setPostContent();

		$this->assertEquals('<p>Content</p>', $post->content);

	}
}

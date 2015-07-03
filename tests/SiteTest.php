<?php
class SiteTest extends PHPUnit_Framework_TestCase
{

    public function testSetSitemap()
    {
		$site = new Site();	
		$site->posts[] = new Post('md/a-file-name', "Title\n2015-07-02\nAuthor");

		$site->setSitemapTxt();
		$this->assertEquals($site->site_root . "/a-file-name\n", $site->sitemap);
    }

	public function testSetRssXml()
	{
		$site = new Site();	
		$site->posts[] = new Post('md/a-file-name', "Title\n2015-07-02\nAuthor\npublished\n\nContent");
		$site->posts[0]->setPostMetaData();
	
		$site->setRssXml();
		$last_rss_build_date = date('r', time());
		$expected = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
 <title>Comanche</title>
 <description>a static site builder</description>
 <link>http://comanche.treddell.com</link>
 <lastBuildDate>' . $last_rss_build_date . '</lastBuildDate>
  <item>
				  <title>Title</title>
				  <link>http://comanche.treddell.com/a-file-name</link>
				  <pubDate>2015-07-02</pubDate>
				 </item> 
</channel>
</rss>
'; 
		/* var_dump($site->xml); */
		$this->assertEquals($expected, $site->xml);
	}
}

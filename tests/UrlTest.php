<?php

namespace Jclyons52\PagePreview;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */

    public function it_gets_first_url_from_text()
    {
        $text = "this is my friend's website http://example.com I think it is cool";

        $url = Url::findFirst($text);

        $this->assertEquals('http://example.com', $url->original);

        $text = "this is my friend's website https://example.com/foo/bar/ I think it is cool";

        $url = Url::findFirst($text);

        $this->assertEquals('https://example.com/foo/bar/', $url->original);
    }

    /**
     * @test
     */
    public function it_gets_all_urls_from_text()
    {
        $text = "this is my friend's website http://example.com I think it is cool. this is his other one: https://other-site.com.au";

        $urls = Url::findAll($text);

        $this->assertEquals(['http://example.com', 'https://other-site.com.au'], [$urls[0]->original, $urls[1]->original]);
    }
    
    /**
     * @test
     */
    public function it_can_parse_a_url()
    {
        $url = new Url('https://example.com/foo/bar/');
        
        $this->assertEquals(['scheme' => 'https', 'host' => 'example.com', 'path' => '/foo/bar/'], $url->components);
    }

    /**
     * @test
     */
    public function it_returns_null_if_there_is_no_url()
    {
        $text = "sapien sapien, at consectetur augue pretium ac. Phasellus bibendum pharetra placerat.";

        $url = Url::findFirst($text);

        $this->assertEquals(null, $url);

        $url = Url::findall($text);

        $this->assertEquals(null, $url);
    }
}
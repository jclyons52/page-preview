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

    /**
     * @test
     */
    public function it_throws_error_if_url_is_not_valid()
    {

        $this->setExpectedException(\Exception::class);

        new Url('fooBar');
    }

    /**
     * @test
     */
    public function it_converts_relative_paths_to_absolute()
    {

        $url = new Url('http://www.example.com/directory');

        $results = [
            'http://www.example.com/directory/files/mobile/1_magbrowser.jpg' => $url->formatRelativeToAbsolute("files/mobile/1_magbrowser.jpg"),

            'https://thing.com/image.jpg' => $url->formatRelativeToAbsolute("https://thing.com/image.jpg"),

            'http://www.example.com/images/img2.png' => $url->formatRelativeToAbsolute( "/images/img2.png")
        ];

        foreach ($results as $expected => $actual) {
            $this->assertEquals($expected, $actual);
        }

        $url = new Url('http://www.example.com/directory/');

        $results = [
            'http://www.example.com/directory/files/mobile/1_magbrowser.jpg' => $url->formatRelativeToAbsolute("files/mobile/1_magbrowser.jpg"),

            'https://thing.com/image.jpg' => $url->formatRelativeToAbsolute("https://thing.com/image.jpg"),

            'http://www.example.com/images/img2.png' => $url->formatRelativeToAbsolute( "/images/img2.png")
        ];

        foreach ($results as $expected => $actual) {
            $this->assertEquals($expected, $actual);
        }
    }
}
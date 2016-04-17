<?php

namespace Jclyons52\PagePreview;

use Jclyons52\PagePreview\Cache\Cache;
use Stash\Pool;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_sets_and_gets_preview_object_in_cache()
    {
        $pool = new Pool();
        $data = $this->previewData();
        $previewIn = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $data);
        $cache = new Cache($pool);

        $cache->set($previewIn);

        $previewOut = $cache->get($previewIn->url);

        $this->assertInstanceOf(Preview::class, $previewOut);
    }

    public function previewData()
    {
        return [
            "title" => "Meta title",
            "images" => ["http://www.example.com/imgs/img1.jpg", "http://www.example.com/imgs/img2.jpg"],
            "description" => "Foo bar, bar foo",
            "url" => "http://www.example.com",
            "meta" => [
                "og:title" => "Meta title",
                "og:description" => "Foo bar bar foo",
                "title" => "Meta title",
                "description" => "Foo bar bar foo",
                "og:url" => "http://www.example.com",
                "keywords" => ["test", "thing", "stuff"],
                "apple-mobile-web-app-capable" => "yes",
                "viewport" => "minimal-ui",
            ],
            "media" => false,
        ];
    }
}
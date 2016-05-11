<?php

namespace Jclyons52\PagePreview;

use Stash\Pool;

class PreviewManagerTest extends TestCase
{
    /**
     * @test
     */
    public function it_fetches_page()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_throws_error_if_request_fails()
    {
        $previewManager = $this->getMockPreviewManager('foo.com/bar/foo.html');

        $this->setExpectedException(\Exception::class);

        $previewManager->fetch('http://www.example.com');
    }

    /**
     * @test
     */
    public function it_throws_error_if_url_is_not_valid()
    {
        $previewManager = $this->previewManager;

        $this->setExpectedException(\Exception::class);

        $previewManager->fetch('fooBar');
    }

    /**
     * @test
     */
    public function it_gets_the_page_title()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $this->assertEquals('Document', $preview->title);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_description()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $this->assertEquals('Foo bar bar foo', $preview->description);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_keywords()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $this->assertEquals(['test', 'thing', 'stuff'], $preview->meta['keywords']);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_title()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $this->assertEquals('Meta title', $preview->meta['title']);
    }

    /**
     * @test
     */
    public function it_gets_all_meta()
    {
        $expected = [
            "og:image" => "files/mobile/1_magbrowser.jpg",
            'og:title' => "Meta title",
            'og:description' => "Foo bar bar foo",
            'title' => "Meta title",
            'description' => "Foo bar bar foo",
            'og:url' => "flipbooker.com/flipbooks/flipbook/",
            'keywords' => ["test", "thing", "stuff"],
            "apple-mobile-web-app-capable" => "yes",
            "viewport" => "minimal-ui",
        ];

        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com');

        $meta = $preview->meta;

        foreach ($expected as $key => $item) {
            $this->assertEquals($item, $meta[$key]);
        }
    }

    /**
     * @test
     */
    public function it_returns_empty_array_object_if_there_is_no_meta()
    {
        $previewManager = $this->getMockPreviewManager(__DIR__ . '/data/noMeta.html');

        $preview = $previewManager->fetch('http://www.example.com');

        $meta = $preview->meta;

        $this->assertTrue($meta->count() === 0);
    }

    /**
     * @test
     */
    public function it_gets_all_images()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com/directory');

        $images = $preview->images;

        $this->assertEquals('http://www.example.com/directory/files/mobile/1_magbrowser.jpg', $images[0]);

        $this->assertEquals('https://thing.com/image.jpg', $images[1]);

        $this->assertEquals('http://www.example.com/images/img2.png', $images[2]);

        $preview = $previewManager->fetch('http://www.example.com/directory/');

        $images = $preview->images;
        
        $this->assertEquals('http://www.example.com/directory/files/mobile/1_magbrowser.jpg', $images[0]);

        $this->assertEquals('https://thing.com/image.jpg', $images[1]);

        $this->assertEquals('http://www.example.com/images/img2.png', $images[2]);
    }

    /**
     * @test
     */
    public function it_renders_base64_images()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->fetch('http://www.example.com/directory');

        $images = $preview->images;

        $this->assertEquals('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIA...', $images[3]);
    }

    /**
     * @test
     */
    public function it_creates_a_new_instance_with_dependencies()
    {
        $previewManager = PreviewManager::create();

        $this->assertInstanceOf(PreviewManager::class, $previewManager);
    }

    /**
     * @test
     */
    public function it_gets_preview_from_cache()
    {
        $data = $this->previewData();

        $data['url'] = 'http://www.example.com/directory';

        $previewIn = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $data);

        $pool = new Pool();

        $previewManager = PreviewManager::create($pool);

        $previewManager->cache($previewIn);

        $previewOut = $previewManager->findOrFetch('http://www.example.com/directory');

        $this->assertEquals($previewIn, $previewOut);
    }

    /**
     * @test    
     */
    public function it_fetches_page_if_not_cached()
    {
        $previewManager = $this->previewManager;

        $preview = $previewManager->findOrFetch('http://www.example.com');

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_gets_url_from_text()
    {
        $text = "foo bar foo http://www.example.com bar baz";
        $previewManager = $this->previewManager;
        $preview = $previewManager->findUrl($text)->fetch();

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_takes_url_object_as_input()
    {
        $text = "foo bar foo http://www.example.com bar baz";
        $previewManager = $this->previewManager;
        $url = Url::findFirst($text);
        $preview = $previewManager->fetch($url);

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_fails_if_no_url_is_provided()
    {
        $previewManager = $this->previewManager;

        $this->setExpectedException(\Exception::class);

        $previewManager->fetch();
    }
}
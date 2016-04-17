<?php

namespace Jclyons52\PagePreview;

use Stash\Pool;

class PreviewBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_fetches_page()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_throws_error_if_request_fails()
    {
        $previewBuilder = $this->getMockPreviewBuilder('foo.com/bar/foo.html');

        $this->setExpectedException(\Exception::class);

        $previewBuilder->fetch('http://www.example.com');
    }

    /**
     * @test
     */
    public function it_throws_error_if_url_is_not_valid()
    {
        $previewBuilder = $this->previewBuilder;

        $this->setExpectedException(\Exception::class);

        $previewBuilder->fetch('fooBar');
    }

    /**
     * @test
     */
    public function it_gets_the_page_title()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals('Document', $preview->title);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_description()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals('Foo bar bar foo', $preview->description);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_keywords()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals(['test', 'thing', 'stuff'], $preview->meta['keywords']);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_title()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

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

        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

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
        $previewBuilder = $this->getMockPreviewBuilder(__DIR__ . '/data/noMeta.html');

        $preview = $previewBuilder->fetch('http://www.example.com');

        $meta = $preview->meta;

        $this->assertTrue($meta->count() === 0);
    }

    /**
     * @test
     */
    public function it_gets_all_images()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com/directory');

        $images = $preview->images;

        $this->assertEquals('http://www.example.com/directory/files/mobile/1_magbrowser.jpg', $images[0]);

        $this->assertEquals('https://thing.com/image.jpg', $images[1]);

        $this->assertEquals('http://www.example.com/images/img2.png', $images[2]);

        $preview = $previewBuilder->fetch('http://www.example.com/directory/');

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
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com/directory');

        $images = $preview->images;

        $this->assertEquals('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIA...', $images[3]);
    }

    /**
     * @test
     */
    public function it_creates_a_new_instance_with_dependencies()
    {
        $previewBuilder = PreviewBuilder::create();

        $this->assertInstanceOf(PreviewBuilder::class, $previewBuilder);
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

        $previewBuilder = PreviewBuilder::create($pool);

        $previewBuilder->cache($previewIn);

        $previewOut = $previewBuilder->findOrFetch('http://www.example.com/directory');

        $this->assertEquals($previewIn, $previewOut);
    }

    /**
     * @test
     */
    public function it_fetches_page_if_not_cached()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->findOrFetch('http://www.example.com');

        $this->assertInstanceOf(Preview::class, $preview);
    }
}
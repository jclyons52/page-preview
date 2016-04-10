<?php

namespace Jclyons52\PagePreview;

class PreviewBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_fetches_page()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertInstanceOf(Preview::class, $preview);
    }

    /**
     * @test
     */
    public function it_throws_error_if_request_fails()
    {
        $this->client->request('GET', 'http://www.example.com');

        $previewBuilder = new PreviewBuilder($this->client);

        $this->setExpectedException(\Exception::class);

        $previewBuilder->fetch('http://www.example.com');
    }

    /**
     * @test
     */
    public function it_throws_error_if_url_is_not_valid()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $this->setExpectedException(\Exception::class);

        $previewBuilder->fetch('fooBar');
    }

    /**
     * @test
     */
    public function it_gets_the_page_title()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals('Document', $preview->title);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_description()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals('Foo bar bar foo', $preview->description);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_keywords()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertEquals(['test', 'thing', 'stuff'], $preview->meta['keywords']);
    }

    /**
     * @test
     */
    public function it_gets_the_meta_title()
    {
        $previewBuilder = new PreviewBuilder($this->client);

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

        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $meta = $preview->meta;

        foreach ($expected as $key => $item) {
            $this->assertEquals($item, $meta[$key]);
        }
    }

    /**
     * @test
     */
    public function it_returns_empty_array_if_there_is_no_meta()
    {
        $client = $this->getMockClient(__DIR__ . '/data/noMeta.html');

        $previewBuilder = new PreviewBuilder($client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $meta = $preview->meta;

        $this->assertEquals([], $meta);
    }

    /**
     * @test
     */
    public function it_gets_all_images()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com/directory');

        $images = $preview->images;

        //relative path test
        $this->assertEquals('http://www.example.com/directory/files/mobile/1_magbrowser.jpg', $images[0]);

        $this->assertEquals('https://thing.com/image.jpg', $images[1]);

        $this->assertEquals('http://www.example.com/images/img2.png', $images[2]);
    }

    /**
     * @test
     */
    public function it_creates_a_new_instance_with_dependencies()
    {
        $previewBuilder = PreviewBuilder::create();

        $this->assertInstanceOf(PreviewBuilder::class, $previewBuilder);
    }
}
<?php

namespace Jclyons52\PagePreview;

class PreviewTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_a_preview()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertContains('Meta title', $preview);
    }

    /**
     * @test
     */
    public function it_renders_a_preview_without_meta()
    {
        $client = $this->getMockClient(__DIR__ . '/data/noMeta.html');

        $previewBuilder = new PreviewBuilder($client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertContains('Document', $preview);
    }

    /**
     * @test
     */
    public function it_renders_page_without_image()
    {
        $client = $this->getMockClient(__DIR__ . '/data/noImages.html');

        $previewBuilder = new PreviewBuilder($client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertNotContains('class="media-left"', $preview);
    }

    /**
     * @test
     */
    public function it_renders_templates_dynamically()
    {
        $previewBuilder = new PreviewBuilder($this->client);

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertContains('class="thumbnail"', $preview->render('thumbnail'));

        $this->assertContains('class="demo-card-wide', $preview->render('card'));
    }

    /**
     * @test
     */
    public function it_renders_custom_templates()
    {
        $preview = new Preview($this->previewData());

        $preview->setViewPath(__DIR__ . '/data/customTemplates');

        $this->assertContains('class="my-custom-div"', $preview->render('myAwesomeTemplate'));
    }

    /**
     * @test
     */
    public function it_converts_preview_to_json()
    {
        $preview = new Preview($this->previewData());

        $json = $preview->toJson();

        $this->assertJsonStringEqualsJsonString(json_encode($this->previewData()), $json);
    }

    /**
     * @test
     */
    public function it_gets_media_when_available()
    {
        $data = $this->previewData();

        $data['url'] = 'http://youtu.be/EIQQnoeepgU';

        $preview = new Preview($data);

        $this->assertEquals([
            'thumbnail' => "http://i2.ytimg.com/vi/EIQQnoeepgU/default.jpg",
                'url' => 'https://www.youtube.com/embed/EIQQnoeepgU'
            ], $preview->media);
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
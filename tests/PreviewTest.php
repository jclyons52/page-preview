<?php

namespace Jclyons52\PagePreview;

class PreviewTest extends TestCase
{
    /**
     * @test
     */
    public function it_renders_a_preview()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertContains('Meta title', $preview);
    }

    /**
     * @test
     */
    public function it_renders_a_preview_without_meta()
    {
        $previewBuilder = $this->getMockPreviewBuilder(__DIR__ . '/data/noMeta.html');

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertContains('Document', $preview);
    }

    /**
     * @test
     */
    public function it_renders_page_without_image()
    {
        $previewBuilder = $this->getMockPreviewBuilder(__DIR__ . '/data/noImages.html');

        $preview = $previewBuilder->fetch('http://www.example.com');

        $preview = $preview->render();

        $this->assertNotContains('class="media-left"', $preview);
    }

    /**
     * @test
     */
    public function it_renders_templates_dynamically()
    {
        $previewBuilder = $this->previewBuilder;

        $preview = $previewBuilder->fetch('http://www.example.com');

        $this->assertContains('class="thumbnail"', $preview->render('thumbnail'));

        $this->assertContains('class="demo-card-wide', $preview->render('card'));
    }

    /**
     * @test
     */
    public function it_renders_custom_templates()
    {
        $preview = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $this->previewData());

        $preview->setViewPath(__DIR__ . '/data/customTemplates');

        $this->assertContains('class="my-custom-div"', $preview->render('myAwesomeTemplate'));
    }

    /**
     * @test
     */
    public function it_converts_preview_to_json()
    {
        $preview = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $this->previewData());

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

        $preview = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $data);

        $this->assertEquals([
            'thumbnail' => "http://i2.ytimg.com/vi/EIQQnoeepgU/default.jpg",
                'url' => 'https://www.youtube.com/embed/EIQQnoeepgU'
            ], $preview->media);
    }

    /**
     * @test
     */
    public function it_renders_thumbnail_template_for_videos()
    {
        $data = $this->previewData();

        $data['url'] = 'http://youtu.be/EIQQnoeepgU';

        $preview = new Preview(new Media(new MainHttpInterfaceMock('foo/bar')), $data);

        $preview = $preview->render();

        $this->assertContains('<div class="embed-responsive embed-responsive-16by9">', $preview);
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
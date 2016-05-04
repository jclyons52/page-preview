<?php

namespace Jclyons52\PagePreview;


use Jclyons52\PHPQuery\Document;

class CrawlerTest extends TestCase
{
    private $html = '
    <!doctype html>
    <html lang="en">
    <head>
    </head>
    <body>
    <div class="row">
        <img src="files/mobile/1_magbrowser.jpg" alt="">
        <img src="" alt="">
        <img src="files/mobile/1_magbrowser.jpg" alt="">
        <img src=" " alt="">
    </div>
    </body>
    </html>
    ';
    /**
     * @test
     */
    public function it_filters_out_empty_image_links()
    {
        $dom = new Document($this->html);

        $crawler = new Crawler($dom);

        $images = $crawler->images();

        $this->assertEquals(2, count($images));
    }
}
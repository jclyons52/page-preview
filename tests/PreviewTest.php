<?php

use Jclyons52\PagePreview\Preview;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use Jclyons52\PHPQuery\Document;

class PreviewTest extends PHPUnit_Framework_TestCase
{

    private $client;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $body = file_get_contents(__DIR__ . '/data/test.html');

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $body),
            new Response(404, ['X-Foo' => 'Bar']),
        ]);

        $handler = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handler]);
    }

    /**
     * @test
     */
    public function it_fetches_page()
    {
        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $this->assertInstanceOf(Document::class, $preview->document);
    }

    /**
     * @test
     */
    public function it_throws_error_if_request_fails()
    {
        $this->client->request('GET', 'www.example.com');

        $preview = new Preview($this->client);

        $this->setExpectedException(\Exception::class);

        $preview->fetch('www.example.com');
    }

    /**
     * @test
     */
    public function it_gets_the_page_title()
    {
        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $this->assertEquals('Document', $preview->title());
    }

    /**
     * @test
     */
    public function it_gets_the_meta_description()
    {
        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $this->assertEquals('Foo bar bar foo', $preview->meta('description'));
    }

    /**
     * @test
     */
    public function it_gets_the_meta_keywords()
    {
        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $this->assertEquals(['test', 'thing', 'stuff'], $preview->metaKeywords());
    }

    /**
     * @test
     */
    public function it_gets_the_meta_title()
    {
        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $this->assertEquals('Meta title', $preview->meta('title'));
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
            'keywords' => "test, thing, stuff",
            "apple-mobile-web-app-capable" => "yes",
            "viewport" => "minimal-ui",
        ];

        $preview = new Preview($this->client);

        $preview->fetch('www.example.com');

        $meta = $preview->meta();

        foreach ($expected as $key => $item) {
            $this->assertEquals($item, $meta[$key]);
        }
    }
    
    /**
     * @test
     */
    public function it_gets_all_images()
    {
        $preview = new Preview($this->client);

        $preview->fetch('http://www.example.com/directory');

        $images = $preview->images();

        //relative path test
        $this->assertEquals('http://www.example.com/directory/files/mobile/1_magbrowser.jpg', $images[0]);

        $this->assertEquals('https://thing.com/image.jpg', $images[1]);
        
        $this->assertEquals('http://www.example.com/images/img2.png', $images[2]);
    }
    
    /**
     * @test
     */
    public function it_renders_a_preview()
    {
        $preview = new Preview($this->client);

        $preview->fetch('http://www.example.com');
        
        $preview = $preview->render();
        
        $this->assertContains('Meta title', $preview);
    }

    /**
     * @test
     */
    public function it_renders_a_preview_without_meta()
    {
        $body = file_get_contents(__DIR__ . '/data/noMeta.html');

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $body)
        ]);

        $handler = HandlerStack::create($mock);

        $this->client = new Client(['handler' => $handler]);

        $preview = new Preview($this->client);

        $preview->fetch('http://www.example.com');
        
        $preview = $preview->render();
        
        $this->assertContains('Document', $preview);
    }

    /**
     * @test
     */
    public function it_renders_templates_dynamically()
    {
        $preview = new Preview($this->client);

        $preview->fetch('http://www.example.com');

        $preview = $preview->render('thumbnail');

        $this->assertContains('class="thumbnail"', $preview);
    }
}
<?php

namespace Jclyons52\PagePreview;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public $client;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->client = $this->getMockClient(__DIR__ . '/data/test.html');
    }

    /**
     * @return HandlerStack
     */
    protected function getMockClient($template)
    {
        $body = file_get_contents($template);

        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $body),
            new Response(404, ['X-Foo' => 'Bar']),
        ]);

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
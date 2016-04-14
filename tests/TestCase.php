<?php

namespace Jclyons52\PagePreview;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class TestCase extends \PHPUnit_Framework_TestCase
{
    public $previewBuilder;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->previewBuilder = $this->getMockPreviewBuilder(__DIR__ . '/data/test.html');
    }

    /**
     * @return HandlerStack
     */
    protected function getMockPreviewBuilder($template)
    {
        $httpMock = new MainHttpInterfaceMock($template);
        return new PreviewBuilder($httpMock);
    }
}

class MainHttpInterfaceMock implements HttpInterface
{
    public function __construct($file)
    {
        $this->file = $file;
    }

    public function get($url)
    {
        $body = @file_get_contents($this->file);

        return $body;
    }
}
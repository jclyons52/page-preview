<?php

namespace Jclyons52\PagePreview;

use Jclyons52\PagePreview\Cache\Cache;
use Jclyons52\PHPQuery\Document;
use Psr\Cache\CacheItemPoolInterface;

class PreviewManager
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * Url object
     * @var Url
     */
    private $url;

    /**
     * @var HttpInterface
     */
    protected $http;


    public function __construct(HttpInterface $http, CacheItemPoolInterface $cache = null)
    {
        $this->http = $http;
        if ($cache !== null) {
            $this->cache = new Cache($cache);
        }
    }

    /**
     * Instantiate class with dependencies
     * @param CacheItemPoolInterface $cache
     * @return static
     */
    public static function create(CacheItemPoolInterface $cache = null)
    {
        $http = new Http();
        return new PreviewManager($http, $cache);
    }

    /**
     * @param string $url
     * @return Preview
     * @throws \Exception
     */
    public function fetch($url = null)
    {
        if ($url instanceof Url) {
            $this->url = $url;
        }
        if (is_string($url)) {
            $this->url = new Url($url);
        }
        if ($this->url === null) {
            throw new \Exception('failed to get url');
        }

        $body = $this->http->get($this->url->original);

        if ($body === false) {
            throw new \Exception('failed to load page');
        }

        return $this->getPreview($body);
    }

    public function findUrl($text)
    {
        $this->url = Url::findFirst($text);

        return $this;
    }

    public function findOrFetch($url)
    {
        if ($this->cache === null) {
            return $this->fetch($url);
        }

        return $this->cache->get($url);
    }

    public function cache(Preview $preview, $expiresAt = null)
    {
        $this->cache->set($preview, $expiresAt);
    }
    /**
     * returns an instance of Preview
     * @return Preview
     */
    private function getPreview($body)
    {
        $document = new Document($body);

        $this->crawler = new Crawler($document);

        $data =$this->crawler->getPreviewData($this->url);

        $media = new Media($this->http);

        return new Preview($media, $data);
    }
}

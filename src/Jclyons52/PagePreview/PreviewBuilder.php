<?php

namespace Jclyons52\PagePreview;

use Jclyons52\PHPQuery\Document;

class PreviewBuilder
{
    /**
     * @var Crawler
     */
    public $crawler;

    /**
     * Url object
     * @var Url
     */
    public $url;

    /**
     * @var HttpInterface
     */
    protected $http;


    public function __construct(HttpInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Instantiate class with dependencies
     * @return static
     */
    public static function create()
    {
        $http = new Http();
        return new PreviewBuilder($http);
    }
    
    /**
     * @param string $url
     * @return Preview
     * @throws \Exception
     */
    public function fetch($url)
    {
        $this->url = new Url($url);

        $body = $this->http->get($url);

        if ($body === false) {
            throw new \Exception('failed to load page');
        }

        $document = new Document($body);

        $this->crawler = new Crawler($document);

        return $this->getPreview();
    }

    /**
     * returns an instance of Preview
     * @return Preview
     */
    public function getPreview()
    {
        $title = $this->crawler->title();

        $images = $this->images();

        $description = $this->crawler->meta('description');

        $meta = $this->crawler->meta();

        $keywords =  $this->crawler->metaKeywords();

        if ($keywords !== []) {
            $meta['keywords'] = $keywords;
        }

        $media = new Media($this->http);
        
        return new Preview($media, [
            'title' => $title,
            'images' => $images,
            'description' => $description,
            'url' => $this->url->original,
            'meta' => $meta,
        ]);
    }

    public function images()
    {
        $urls = $this->crawler->images();
        $result = [];
        foreach ($urls as $url) {
            $result[] = $this->url->formatRelativeToAbsolute($url);
        }
        return $result;
    }
}

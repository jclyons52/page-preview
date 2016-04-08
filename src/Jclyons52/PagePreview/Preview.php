<?php

namespace Jclyons52\PagePreview;

use GuzzleHttp\Client;
use Jclyons52\PHPQuery\Document;
use League\Plates\Engine;

class Preview
{
    protected $result;

    protected $client;

    public $document;

    public $url;

    protected $urlComponents;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return Document
     */
    public function fetch($url)
    {
        $this->url = $url;

        $this->urlComponents = parse_url($url);

        $this->result = $this->client->request('GET', $url);

        $body = $this->result->getBody()->getContents();

        $this->document = new Document($body);

        return $this->document;
    }

    /**
     * @return mixed
     */
    public function title()
    {
        return $this->document->querySelector('title')->text();
    }

    /**
     * @return mixed
     */
    public function metaDescription()
    {
        return $this->document->querySelector('meta[name="description"]')->attr('content');
    }

    /**
     * @return mixed
     */
    public function metaKeywords()
    {
        $keywordString = $this->document->querySelector('meta[name="keywords"]')->attr('content');

        $keywords = explode(',', $keywordString);

        return array_map(function ($word) {
            return trim($word);

        }, $keywords);
    }

    /**
     * @return mixed
     */
    public function metaTitle()
    {
        return $this->document->querySelector('meta[name="title"]')->attr('content');
    }

    /**
     * @return array
     */
    public function meta()
    {
        $metaTags = $this->document->querySelectorAll('meta');
        $values = [];
        foreach ($metaTags as $meta) {
            $values[$meta->attr('name')] = $meta->attr('content');
        }
        return $values;
    }

    /**
     * @return array
     */
    public function images()
    {
        $images = $this->document->querySelectorAll('img');
        $urls = $images->attr('src');
        $result = [];
        foreach ($urls as $url) {
            $result[] = $this->formatUrl($url);
        }
        return $result;
    }

    public function render($type = 'card')
    {
        $title = $this->metaTitle();

        $image = $this->images()[0];

        $description = $this->metaDescription();

        $templates = new Engine(__DIR__ . '/Templates');

        return $templates->render('card', [ 'title' => $title, 'image' => $image, 'body' => $description, 'url' => $this->url ]);
    }

    /**
     * @param string $url
     * @return array
     */
    private function formatUrl($url)
    {
        $path = array_key_exists('path', $this->urlComponents) ? $this->urlComponents['path'] : '';

        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return $url;
        }
        if (substr($url, 0, 1) === '/') {
            return 'http://' . $this->urlComponents['host'] . $url;
        }
        return 'http://' . $this->urlComponents['host'] .$path . '/' . $url;
    }
}

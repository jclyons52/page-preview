<?php

namespace Jclyons52\PagePreview;

use GuzzleHttp\Client;
use Jclyons52\PHPQuery\Document;

class PreviewBuilder
{
    /**
     * Guzzle client
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * PHPQuery document object that will be used to select elements
     * @var \Jclyons52\PHPQuery\Document
     */
    public $document;

    /**
     * reference to the url parameter the user passes into the fetch method
     * @var string
     */
    public $url;

    /**
     * destructured array of url components from parse_url
     * @var array
     */
    protected $urlComponents;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Instantiate class with dependencies
     * @return static
     */
    public static function create()
    {
        $client = new Client();

        return new static($client);
    }


    /**
     * @param string $url
     * @return Document
     * @throws \Exception
     */
    public function fetch($url)
    {
        $this->url = $url;

        $urlComponents = parse_url($url);

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("url {$this->url} is invalid");
        }
        $this->urlComponents = $urlComponents;

        $result = $this->client->request('GET', $url);

        $body = $result->getBody()->getContents();

        $this->document = new Document($body);

        return $this->getPreview();
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->document->querySelector('title')->text();
    }

    /**
     * @return mixed
     */
    public function metaKeywords()
    {
        $keywordsElement = $this->document->querySelector('meta[name="keywords"]');

        if (!$keywordsElement) {
            return [];
        }

        $keywordString = $keywordsElement->attr('content');

        $keywords = explode(',', $keywordString);

        return array_map(function ($word) {
            return trim($word);

        }, $keywords);
    }

    /**
     * @param string $element
     * @return array
     */
    public function meta($element = null)
    {
        $selector = "meta";
        if ($element !== null) {
            $selector .= "[name='{$element}']";
            $metaTags =  $this->document->querySelector($selector);
            if ($metaTags === null) {
                return null;
            }
            return  $metaTags->attr('content');
        }
        $metaTags = $this->document->querySelectorAll($selector);

        return $this->metaTagsToArray($metaTags);
    }

    /**
     * get source attributes of all image tags on the page
     * @return array<String>
     */
    public function images()
    {
        $images = $this->document->querySelectorAll('img');

        if ($images === []) {
            return [];
        }

        $urls = $images->attr('src');
        $result = [];
        foreach ($urls as $url) {
            $result[] = $this->formatUrl($url);
        }
        return $result;
    }

    /**
     * returns a string of the link preview html
     * @param  string $type     template name to be selected
     * @param  string $viewPath path to templates folder
     * @return string           html for link preview
     */
    public function getPreview()
    {
        $title = $this->title();

        $images = $this->images();

        $description = $this->meta('description');

        $meta = $this->meta();

        $keywords =  $this->metaKeywords();

        if ($keywords !== []) {
            $meta['keywords'] = $keywords;
        }

        return new Preview([
            'title' => $title,
            'images' => $images,
            'description' => $description,
            'url' => $this->url,
            'meta' => $meta,
        ]);
    }

    /**
     * @param string $url
     * @return string
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

    /**
     * @param $metaTags
     * @return array
     */
    private function metaTagsToArray($metaTags)
    {
        $values = [];
        foreach ($metaTags as $meta) {
            $name = $meta->attr('name');
            if ($name === '') {
                $name = $meta->attr('property');
            }
            $content = $meta->attr('content');
            if ($name === '' || $content == '') {
                continue;
            }
            $values[$name] = $content;
        }

        return $values;
    }
}

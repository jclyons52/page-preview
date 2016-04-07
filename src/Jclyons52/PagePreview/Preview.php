<?php

namespace Jclyons52\PagePreview;

use GuzzleHttp\Client;
use Jclyons52\PHPQuery\Document;

class Preview
{
    protected $result;

    protected $client;

    public $document;

    protected $url;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetch($url)
    {

        $this->url = parse_url($url);

        $this->result = $this->client->request('GET', $url);

        $this->document = new Document($this->result->getBody());

        return $this->document;
    }

    public function title()
    {
        return $this->document->querySelector('title')->text();
    }

    public function metaDescription()
    {
        return $this->document->querySelector('meta[name="description"]')->attr('content');
    }

    public function metaKeywords()
    {
        $keywordString = $this->document->querySelector('meta[name="keywords"]')->attr('content');

        $keywords = explode(',', $keywordString);

        return array_map(function($word){ return trim($word); }, $keywords);
    }

    public function metaTitle()
    {
        return $this->document->querySelector('meta[name="title"]')->attr('content');
    }

    public function meta()
    {
        $metaTags = $this->document->querySelectorAll('meta');

        foreach ($metaTags as $meta) {
            $values[$meta->attr('name')] = $meta->attr('content');
        }
        return $values;
    }

    public function images()
    {
        $images = $this->document->querySelectorAll('img');
        $urls = $images->attr('src');
        foreach($urls as $url){
            $result[] = $this->formatUrl($url);
        }
        return $result;
    }

    /**
     * @param $url
     * @param $result
     * @param $path
     * @return array
     */
    private function formatUrl($url)
    {
        $path = array_key_exists('path', $this->url) ? $this->url['path'] : '';

        if (filter_var($url, FILTER_VALIDATE_URL) !== false) return $url;

        if (strpos($url, $this->url['host'])) return 'http://'. $url;
        var_dump($url);
        if (substr($url, 0, 1) === '/') return 'http://' . $this->url['host'] . $url;

        return 'http://' . $this->url['host'] .$path . '/' . $url;

    }
}

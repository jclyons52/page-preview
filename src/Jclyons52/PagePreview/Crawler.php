<?php

namespace Jclyons52\PagePreview;

use Jclyons52\PHPQuery\Document;

class Crawler
{
    /**
     * PHPQuery document object that will be used to select elements
     * @var \Jclyons52\PHPQuery\Document
     */
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @return array
     */
    public function getPreviewData($url)
    {
        $title = $this->title();

        $images = $this->formatImages($url);

        $description = $this->meta('description');

        $meta = $this->meta();

        $keywords = $this->metaKeywords();

        if ($keywords !== []) {
            $meta['keywords'] = $keywords;
        }

        return [
            'title' => $title,
            'images' => $images,
            'description' => $description,
            'url' => $url->original,
            'meta' => $meta,
        ];
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

        $urls = array_filter($urls, function ($url) {
            $url = trim($url);
            return $url;
        });

        return $urls;
    }

    /**
     * @return string
     */
    private function title()
    {
        return $this->document->querySelector('title')->text();
    }

    /**
     * @return mixed
     */
    private function metaKeywords()
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
    private function meta($element = null)
    {
        $selector = "meta";
        if ($element === null) {
            $metaTags = $this->document->querySelectorAll($selector);
            return $this->metaTagsToArray($metaTags);
        }

        $selector .= "[name='{$element}']";
        $metaTags =  $this->document->querySelector($selector);
        if ($metaTags === null) {
            return null;
        }
        return  $metaTags->attr('content');
    }

    /**
     * @param \Jclyons52\PHPQuery\Support\NodeCollection $metaTags
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

    /**
     * @param $url Url
     * @return mixed
     */
    private function formatImages($url)
    {
        return array_map(function ($imageUrl) use ($url) {
            return $url->formatRelativeToAbsolute($imageUrl);
        }, $this->images());
    }
}

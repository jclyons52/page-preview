<?php

namespace Jclyons52\PagePreview;

use Jclyons52\PHPQuery\Document;

class Crawler
{
    private $document;
    
    public function __construct(Document $document)
    {
        $this->document = $document;
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
        return $images->attr('src');
        
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
}

<?php

namespace Jclyons52\PagePreview;

class Url
{
    public $original;
    
    public $components;
    
    public function __construct($url)
    {
        $this->original = $url;
        $this->components = $this->parse($url);
    }

    public static function findFirst($text)
    {
        if ($urls = self::extract($text)) {
            return new self($urls[0]);
        }
        
        return null;
    }
    
    public static function findAll($text)
    {
        if ($urls = self::extract($text)) {
            return array_map(function ($url) {
                return new self($url);
            }, $urls);
        }
        return null;
    }
    
    public static function extract($text)
    {
        if (preg_match_all('!https?://\S+!', $text, $matches) === 0) {
            return null;
        }
        
        return $matches[0];
    }
    
    public function parse($url)
    {
        $urlComponents = parse_url($url);

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("url {$url} is invalid");
        }
        
        return $urlComponents;
    }
    
    public function formatRelativeToAbsolute($url)
    {
        if (substr($url, 0, 5) === "data:") {
            return $url;
        }

        $path = array_key_exists('path', $this->components) ? $this->components['path'] : '';

        if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
            return $url;
        }
        if (substr($url, 0, 1) === '/') {
            return 'http://' . $this->components['host'] . $url;
        }

        $host = trim($this->components['host'], '/') . '/';
        $path = trim($path, '/') . '/';

        return 'http://' . $host . $path . $url;
    }
}

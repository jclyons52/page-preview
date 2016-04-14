<?php

namespace Jclyons52\PagePreview;

class Media
{
    protected $http;
    
    public function __construct(HttpInterface $http)
    {
        $this->http = $http;
    }

    public function get($url)
    {
        if (strpos($url, "youtu") !== false) {
            $link = $this->youtubeFormat($url);
            if ($link !== false) {
                return $link;
            }
        }
        if (strpos($url, "vimeo") !== false) {
            $link = $this->vimeoFormat($url);
            if ($link !== false) {
                return $link;
            }
        }
        return [];
    }

    /**
     * @param $url
     * @return array|bool
     */
    private function youtubeFormat($url)
    {
        $id = null;
        $re = "/https?:\\/\\/(?:www\\.)?youtu(?:be\\.com\\/(watch\\?)?".
            "(.*?&?)?v=|\\.be\\/)([\\w\\-]+)(&(amp;)?[\\w\\?=]*)?/i";
        if (preg_match($re, $url, $matching)) {
            $id = $matching[3];
        } else {
            $re = "/https?:\\/\\/(?:www\\.)?youtube\\.com\\/embed\\/([^&|^?|^\\/]*)/i";
            if (preg_match($re, $url, $matching)) {
                $id = $matching[1];
            }
        }
        if ($id !== null) {
            return [
                'url' => 'https://www.youtube.com/embed/' . $id,
                'thumbnail' => "http://i2.ytimg.com/vi/{$id}/default.jpg",
            ];
        }
        return false;
    }

    /**
     * @param $url
     * @param $matches
     * @return array
     */
    private function vimeoFormat($url)
    {
        $re = "/https?:\\/\\/(?:www\\.|player\\.)?vimeo.com\\/" .
            "(?:channels\\/(?:\\w+\\/)?|groups\\/([^\\/]*)\\/videos\\/".
            "|album\\/(\\d+)\\/video\\/|video\\/|)(\\d+)(?:$|\\/|\\?)?/";
        preg_match_all($re, $url, $matches);

        if (!$matches[3]) {
            return false;
        }
        $imgId = $matches[3][0];

        $hash = $this->http->get("http://vimeo.com/api/v2/video/{$imgId}.json");

        return [
            'url'        => "http://player.vimeo.com/video/{$imgId}",
            'thumbnail' => $hash[0]->thumbnail_large,
        ];
    }
}

<?php

namespace Jclyons52\PagePreview;

class Media
{
    public static function get($url)
    {
        if (strpos($url, "youtu") !== false) {
            $link = static::youtubeFormat($url);
            if ($link !== false) {
                return $link;
            }
        }
        if (strpos($url, "vimeo") !== false) {
            return static::vimeoFormat($url);
        }
        return [];
    }

    /**
     * @param $url
     * @return array|bool
     */
    private static function youtubeFormat($url)
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
    private static function vimeoFormat($url)
    {
        $re = "/https?:\\/\\/(?:www\\.|player\\.)?vimeo.com\\/" .
            "(?:channels\\/(?:\\w+\\/)?|groups\\/([^\\/]*)\\/videos\\/".
            "|album\\/(\\d+)\\/video\\/|video\\/|)(\\d+)(?:$|\\/|\\?)?/";
        preg_match_all($re, $url, $matches);

        if (!$matches[3]) {
            return false;
        }

        $imgId = $matches[3][0];

        $hash = static::fetch("http://vimeo.com/api/v2/video/{$imgId}.json");

        return [
            'url'        => "http://player.vimeo.com/video/{$imgId}",
            'thumbnail' => $hash[0]->thumbnail_large,
        ];
    }

    /**
     * @param $url
     * @return mixed
     */
    protected static function fetch($url)
    {
        return json_decode(file_get_contents($url));
    }
}

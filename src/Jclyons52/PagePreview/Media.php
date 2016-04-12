<?php

namespace Jclyons52\PagePreview;

class Media
{
    public static function get($url)
    {
        if (strpos($url, "youtu") !== false) {
            $link = self::youtubeId($url);
            if($link !== false) return $link;
        }
        return [];
    }

    /**
     * @param $url
     * @return array|bool
     */
    private static function youtubeId($url)
    {
        $re = "/https?:\\/\\/(?:www\\.)?youtu(?:be\\.com\\/(watch\\?)?(.*?&?)?v=|\\.be\\/)([\\w\\-]+)(&(amp;)?[\\w\\?=]*)?/i";
        if (preg_match($re, $url, $matching)) {
            return ['https://www.youtube.com/embed/' . $matching[3]];
        }
        $re = "/https?:\\/\\/(?:www\\.)?youtube\\.com\\/embed\\/([^&|^?|^\\/]*)/i";
        if (preg_match($re, $url, $matching)) {
            return ['https://www.youtube.com/embed/' . $matching[1]];
        }

        return false;
    }
}
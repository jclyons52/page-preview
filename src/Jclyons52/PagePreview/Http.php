<?php

namespace Jclyons52\PagePreview;

class Http implements HttpInterface
{
    public function get($url)
    {
        return @file_get_contents($url);
    }
}

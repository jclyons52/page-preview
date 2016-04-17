<?php

namespace Jclyons52\PagePreview\Cache;

use Jclyons52\PagePreview\Preview;
use Psr\Cache\CacheItemPoolInterface;

class Cache
{
    private $pool;

    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    public function get($key)
    {
        $item = $this->pool->getItem(md5($key));

        $preview = unserialize($item->get());

        if ($preview instanceof Preview) {
            return $preview;
        }
    }

    public function set(Preview $preview)
    {
        $item = $this->pool->getItem(md5($preview->url));

        $item->set(serialize($preview));

        $this->pool->save($item);
    }
}

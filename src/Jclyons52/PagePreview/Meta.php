<?php

namespace Jclyons52\PagePreview;

class Meta extends \ArrayObject
{
    public function og()
    {
        foreach ($this as $key => $value) {
            if (strpos($key, 'og:') > -1) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function twitter()
    {
        $result = [];
        foreach ($this as $key => $value) {
            if (strpos($key, 'twitter:') > -1) {
                $result[$key] = $value;
            }
        }
        $result = $this->unFlatten($result);
        return $result['twitter'];
    }

    public function unFlatten($array = null)
    {
        if ($array === null) {
            $array = $this;
        }
        $result = [];
        foreach ($array as $key => $value) {
                $keys = explode(':', $key);
                $mu = $this->deepen($keys, $value);
                $result = array_merge_recursive($result, $mu);
        }
        return $result;
    }

    public function deepen($keys, $value, $index = 0, $carry = [])
    {
        if (array_key_exists($index + 1, $keys)) {
            $carry[$keys[$index]] = $this->deepen($keys, $value, $index + 1);
        } else {
            $carry[$keys[$index]] = $value;
        }
        return $carry;
    }
}

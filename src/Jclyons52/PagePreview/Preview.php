<?php

namespace Jclyons52\PagePreview;

use League\Plates\Engine;

class Preview
{
    public $url;
    
    public $title;
    
    public $description;
    
    public $images;

    public $meta;
    
    public $media = false;
    
    private $viewPath;
    
    public function __construct(Media $mediaObj, $data)
    {
        $this->url = $data['url'];
        
        $this->title = $data['title'];
        
        $this->description = $data['description'];
        
        $this->images = $data['images'];
        
        $this->meta = new Meta($data['meta']);

        $media = $mediaObj->get($data['url']);
        
        if ($media !== []) {
            $this->media = $media;
        }
        
        $this->viewPath = __DIR__ . '/Templates';
    }

    /**
     * @param string $viewPath
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @param string $type
     * @return string
     */
    public function render($type = null)
    {
        $templates = new Engine($this->viewPath);

        if ($type === null) {
            if ($this->media === false) {
                $type = 'media';
            } else {
                $type = 'thumbnail';
            }
        }

        return $templates->render($type, $this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {

        $title = $this->title;

        if (array_key_exists('title', $this->meta)) {
            $title = $this->meta['title'];
        }
        if (count($this->images) < 1) {
            $this->images[0] = null;
        }
        return [
            'title' => $title,
            'images' => $this->images,
            'description' => $this->description,
            'url' => $this->url,
            'meta' => $this->meta,
            'media' => $this->media,
        ];
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}

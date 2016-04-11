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
    
    private $viewPath;
    
    public function __construct($data)
    {
        $this->url = $data['url'];
        
        $this->title = $data['title'];
        
        $this->description = $data['description'];
        
        $this->images = $data['images'];
        
        $this->meta = new Meta($data['meta']);
        
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
    public function render($type = 'media')
    {
        $templates = new Engine($this->viewPath);

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

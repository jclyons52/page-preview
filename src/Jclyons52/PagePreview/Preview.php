<?php

namespace Jclyons52\PagePreview;

use League\Plates\Engine;

class Preview
{
    public $url;
    
    public $title;
    
    public $body;
    
    public $images;
    
    private $viewPath;
    
    public function __construct($data)
    {
        $this->url = $data['url'];
        
        $this->title = $data['title'];
        
        $this->body = $data['body'];
        
        $this->images = $data['images'];
        
        $this->meta = $data['meta'];
        
        $this->viewPath = __DIR__ . '/Templates';
    }
    
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }
    
    public function render($type = 'media')
    {
        if (count($this->images) > 0) {
            $image = $this->images[0];
        } else {
            $image = null;
        }
        
        $templates = new Engine($this->viewPath);

        return $templates->render($type, [
            'title' => $this->title,
            'image' => $image,
            'body' => $this->body, 
            'url' => $this->url
        ]);
    }
    
    public function toArray()
    {
        
    }
    
    public function toJson()
    {
        
    }
}
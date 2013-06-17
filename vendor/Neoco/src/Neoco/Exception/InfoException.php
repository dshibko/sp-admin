<?php

namespace Neoco\Exception;

class InfoException extends \Exception
{

    private $content;
    private $title;

    public function __construct($content, $title = '')
    {
        $this->content = $content;
        $this->title = $title;
        parent::__construct(!empty($title) ? $title : $content);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getTitle()
    {
        return $this->title;
    }

}

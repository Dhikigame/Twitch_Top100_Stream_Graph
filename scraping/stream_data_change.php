<?php

class Stream_Data_Change {

    private $title;
    private $started_at;

    public function __construct($title, $started_at) {
        $this->title = $title;
        $this->started_at = $started_at;
    }

    public function title_change($before_title) {
        $this->title = htmlspecialchars($before_title, ENT_QUOTES, 'UTF-8');

        return $this->title;
    }

    public function started_at_change($before_started_at) {
        $this->started_at = str_replace("T", " ", $before_started_at);
        $this->started_at = str_replace("Z", "", $this->started_at);

        return $this->started_at;
    }

}
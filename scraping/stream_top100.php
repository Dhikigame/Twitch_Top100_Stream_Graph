<?php
require_once("stream_data_change.php");

class Stream_Scraping_Top100 {

    private $stream_info_top100;
    private $stream_info_ranking_part;

    public function __construct($stream_info) {
        $this->stream_info_top100 = $stream_info;
    }

    public function stream_data_rank() {
        for($i = 0; $i <= 99; $i++) {
            $this->stream_data_part($this->stream_info_top100[$i], $i+1);
            // if($this->stream_info_ranking_part[$index]['id'] == null) {
            //     break;
            // }
        }

        return $this->stream_info_ranking_part;
    }

    public function stream_data_part($stream_info, $rank) {
        $index = $rank - 1;
        $stream_data_change = new Stream_Data_Change($stream_info['title'], $stream_info['started_at']);

        $this->stream_info_ranking_part[$index]['id'] = $stream_info['id'];
        $this->stream_info_ranking_part[$index]['user_id'] = $stream_info['user_id'];
        $this->stream_info_ranking_part[$index]['user_login'] = $stream_info['user_login'];
        $this->stream_info_ranking_part[$index]['game_id'] = $stream_info['game_id'];
        $this->stream_info_ranking_part[$index]['game_name'] = $stream_info['game_name'];
        $this->stream_info_ranking_part[$index]['type'] = $stream_info['type'];
        $this->stream_info_ranking_part[$index]['title'] = $stream_data_change->title_change($stream_info['title']);
        $this->stream_info_ranking_part[$index]['viewer_count'] = $stream_info['viewer_count'];
        $this->stream_info_ranking_part[$index]['started_at'] = $stream_data_change->started_at_change($stream_info['started_at']);
        $this->stream_info_ranking_part[$index]['language'] = $stream_info['language'];
        $this->stream_info_ranking_part[$index]['thumbnail_url'] = $stream_info['thumbnail_url'];
        for($i = 0; $i < count($stream_info['tag_ids']); $i++) {
            $this->stream_info_ranking_part[$index]['tag_ids'][$i] = $stream_info['tag_ids'][$i];
        }
        if($stream_info['is_mature'] == "true") {
            $this->stream_info_ranking_part[$index]['is_mature'] = 1;
        } else {
            $this->stream_info_ranking_part[$index]['is_mature'] = 0;
        }
    }
}
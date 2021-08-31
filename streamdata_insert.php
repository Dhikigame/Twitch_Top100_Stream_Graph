<?php
require_once("info/secretkey.php");
require_once("scraping/stream_top100.php");
require_once("db_streamdata/db.php");


class Stream_Info {

    private $client_id;
    private $access_token;
    private $twitch_api;
    private $stream_data_part;

    public function __construct() {
        $this->client_id = client_id();
        $this->access_token = access_token();
        $this->twitch_api = 'https://api.twitch.tv/helix/streams?first=100';
    }

    public function cmd_create() {
        $cmd = "curl -X GET '" . $this->twitch_api . "' -H 'Authorization: Bearer " . $this->access_token  . "' -H 'Client-Id: " . $this->client_id . "'";
        return $cmd;
    }

    public function stream_data_get($cmd) {
        exec($cmd, $opt);
        $arr = json_decode($opt[0], true);

        return $arr['data'];
    }

    public function stream_data_scraping($stream_info) {
        $stream_data_part = new Stream_Scraping_Top100($stream_info);
        $this->stream_data_part = $stream_data_part->stream_data_rank();
    }

    public function db() {
        $db = new DB($this->stream_data_part);
        $db->db_connection();
        $db->db_insert();
        $db->db_close();
    }
}

$stream_info = new Stream_Info();
/* Twitchストリームの視聴者数の多いチャンネルTOP100の情報を取得 */
$cmd = $stream_info->cmd_create();
$stream_info_top100 = $stream_info->stream_data_get($cmd);
/* チャンネルTOP100の情報を順位付けで個別に情報を分ける */
$stream_info->stream_data_scraping($stream_info_top100);
/* データベースに登録する */
$stream_info->db();

?>
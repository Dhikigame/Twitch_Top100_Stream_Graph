<?php
if(PHP_OS == "Linux") { 
    require_once("/home/centos/twitch_top100_stream_graph/info/db_info.php");
    require_once("/home/centos/twitch_top100_stream_graph/info/ipaddress_info.php");
} else {
    require_once("./info/db_info.php");
    require_once("./info/ipaddress_info.php");
}


class DB {

    private $stream_info;
    private $db_link;
    private $db_selected;

    public function __construct($stream_info) {
        $this->stream_info = $stream_info;
    }

    public function db_connection() {
        $IPADDR = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:gethostbyname(gethostname());
        $search_ipaddress = new IPAddress();
        $search_dbinfo = new DB_Connection();

        if($IPADDR == $search_ipaddress->server_ipaddress()) { 
            $this->db_link = mysqli_connect($search_dbinfo->server_ipaddress_db(), $search_dbinfo->server_db("user"), $search_dbinfo->server_db("password")); //DBサーバ自身に接続
        } else {
            $this->db_link = mysqli_connect($search_dbinfo->local_ipaddress_db(), $search_dbinfo->local_db("user"), $search_dbinfo->local_db("password")); //DBサーバ自身に接続
        }

        /* DB存在するか確認 */
        $database_list = mysqli_query($this->db_link, "SHOW DATABASES");
        while ($row = mysqli_fetch_row($database_list)) {
            if (($row[0] != "information_schema") && ($row[0] != "mysql")) {
                echo $row[0]."\r\n";
            }

            if($row[0] == $search_dbinfo->select_db()) {
                $database_found = TRUE;
                echo "【Twitch TOP100 DATABASE found!】"."\r\n";
                break;
            }
            $database_found = FALSE;
        }
        // Create database
        if($database_found !== TRUE) {
            $sql = "CREATE DATABASE " . $search_dbinfo->select_db();
            $this->db_link->query($sql);
            echo "データーベースを作成しました：". $search_dbinfo->select_db() ."\r\n";
            $this->db_table_create($search_dbinfo);
        } else {
            //　DB selected
            $this->db_selected = mysqli_select_db($this->db_link, $search_dbinfo->select_db());
        }
    }

    public function db_table_create($search_dbinfo) {
        //　DB selected
        $this->db_selected = mysqli_select_db($this->db_link, $search_dbinfo->select_db());
        // SQL Created
        for($i = 1; $i <= 100; $i++) {
            $sql = "CREATE TABLE top".$i."(
                id BIGINT,
                user_id INT(10),
                user_login VARCHAR(255),
                game_id INT(10),
                game_name VARCHAR(255),
                type VARCHAR(50),
                title VARCHAR(255),
                viewer_count INT(10),
                started_at DATETIME,
                currentdate DATETIME,
                language VARCHAR(50),
                thumbnail_url VARCHAR(255),
                tag_ids_1 VARCHAR(100),
                tag_ids_2 VARCHAR(100),
                tag_ids_3 VARCHAR(100),
                tag_ids_4 VARCHAR(100),
                tag_ids_5 VARCHAR(100),
                is_mature BOOLEAN
                )";
            $query = mysqli_query($this->db_link, $sql);
            if (!$query){
                echo "テーブルを作成しました：top". $i."\r\n";
            }
            $sql = "CREATE TABLE stream_data(
                id BIGINT,
                user_id INT(10),
                user_login VARCHAR(255),
                game_id INT(10),
                game_name VARCHAR(255),
                type VARCHAR(50),
                title VARCHAR(255),
                viewer_count INT(10),
                started_at DATETIME,
                currentdate DATETIME,
                language VARCHAR(50),
                thumbnail_url VARCHAR(255),
                tag_ids_1 VARCHAR(100),
                tag_ids_2 VARCHAR(100),
                tag_ids_3 VARCHAR(100),
                tag_ids_4 VARCHAR(100),
                tag_ids_5 VARCHAR(100),
                is_mature BOOLEAN
                )";
            $query = mysqli_query($this->db_link, $sql);
        }
    }

    public function db_insert() {
        // SQL Created
        for($i = 1; $i <= 100; $i++) {
            $current_time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO top".$i."(
                id, user_id, user_login, game_id, game_name,
                type, title, viewer_count, started_at, currentdate, language,
                thumbnail_url, tag_ids_1, tag_ids_2, tag_ids_3, tag_ids_4,
                tag_ids_5, is_mature
            ) VALUES (
                ".$this->stream_info[$i-1]['id'].", ".$this->stream_info[$i-1]['user_id'].", '".$this->stream_info[$i-1]['user_login']."', ".$this->stream_info[$i-1]['game_id'].", '".$this->stream_info[$i-1]['game_name']."',
                '".$this->stream_info[$i-1]['type']."', '".$this->stream_info[$i-1]['title']."', ".$this->stream_info[$i-1]['viewer_count'].", '".$this->stream_info[$i-1]['started_at']."', '".$current_time."', '".$this->stream_info[$i-1]['language']."',
                '".$this->stream_info[$i-1]['thumbnail_url']."', '".$this->stream_info[$i-1]['tag_ids'][0]."', '".$this->stream_info[$i-1]['tag_ids'][1]."', '".$this->stream_info[$i-1]['tag_ids'][2]."', '".$this->stream_info[$i-1]['tag_ids'][3]."', 
                '".$this->stream_info[$i-1]['tag_ids'][4]."', ".$this->stream_info[$i-1]['is_mature']."
            )";
            echo $sql ."\r\n";
            $query = mysqli_query($this->db_link, $sql);
            $sql = "INSERT INTO stream_data(
                id, user_id, user_login, game_id, game_name,
                type, title, viewer_count, started_at, currentdate, language,
                thumbnail_url, tag_ids_1, tag_ids_2, tag_ids_3, tag_ids_4,
                tag_ids_5, is_mature
            ) VALUES (
                ".$this->stream_info[$i-1]['id'].", ".$this->stream_info[$i-1]['user_id'].", '".$this->stream_info[$i-1]['user_login']."', ".$this->stream_info[$i-1]['game_id'].", '".$this->stream_info[$i-1]['game_name']."',
                '".$this->stream_info[$i-1]['type']."', '".$this->stream_info[$i-1]['title']."', ".$this->stream_info[$i-1]['viewer_count'].", '".$this->stream_info[$i-1]['started_at']."', '".$current_time."', '".$this->stream_info[$i-1]['language']."',
                '".$this->stream_info[$i-1]['thumbnail_url']."', '".$this->stream_info[$i-1]['tag_ids'][0]."', '".$this->stream_info[$i-1]['tag_ids'][1]."', '".$this->stream_info[$i-1]['tag_ids'][2]."', '".$this->stream_info[$i-1]['tag_ids'][3]."', 
                '".$this->stream_info[$i-1]['tag_ids'][4]."', ".$this->stream_info[$i-1]['is_mature']."
            )";
            echo $sql ."\r\n";
            $query = mysqli_query($this->db_link, $sql);
        }
    }

    public function db_close() {
        mysqli_close($this->db_link);
    }
}

?>
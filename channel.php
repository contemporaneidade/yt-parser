<?php

require_once('common/function/cleanYT_id.php');

$ch_path = "files/channels/";
$out_path = "files/videos/";
$out_file = "videos.csv";

$files = scandir($ch_path);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) == "html") {
        $channel = trim(substr($file, 0, -5));

        $handle = @fopen($ch_path.$file, "r");
        if ($handle) {
            $vid = array();
            $views = array();
            $tag_duration = array();
            $duration = array();
            
            $yt_id = "";
            $yt_id2 = "";

            $open_duration = false;
            
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                
                $pattern[0] = "/aria-label=\"(.*)\" title=\"(.*)\" href=\"(https:\/\/www\.youtube\.com\/watch\?v=(.*))\">(.*)<\/a>/";
                if (preg_match_all($pattern[0], $buffer, $vid)) {
                    $yt_id = cleanYT_id($vid[3][0]);

                    $arr_ch[$channel][$yt_id]["yt_id"] = $yt_id;
                    $arr_ch[$channel][$yt_id]["title"] = "\"".str_replace("\"", "'", $vid[5][0])."\"";
                    $arr_ch[$channel][$yt_id]["url"] = "https://www.youtube.com/watch?v=".$yt_id;
                    
                    // pegando a quantidade de visualizações
                    $pattern[1] = "/ .* (.*) visualizações/"; 
                    if (preg_match_all($pattern[1], $vid[1][0], $views)) {
                        $arr_ch[$channel][$yt_id]["views"] = $views[1][0];
                    }
                }

                // update: pegando a minutagem do vídeo em outra parte do html da página (2018-11-10)
                $pattern[2] = "/<a id=\"thumbnail\" class=\"(.*)\" aria-hidden=\"(.*)\" tabindex=\"(.*)\" href=\"(https:\/\/www\.youtube\.com\/watch\?v=(.*))\">/";
                if (preg_match_all($pattern[2], $buffer, $tag_duration)) {
                    $open_duration = true;
                    $yt_id2 = cleanYT_id($tag_duration[4][0]);
                }

                $pattern[3] = "/[0-9]{1,3}:[0-9]{1,2}:[0-9]{1,2}/";
                $pattern[4] = "/[0-9]{1,2}:[0-9]{1,2}/";
                if ($open_duration) {
                    if (preg_match_all($pattern[3], $buffer, $duration) || preg_match_all($pattern[4], $buffer, $duration)) {
                        $arr_ch[$channel][$yt_id2]["duration"] = $duration[0][0];
                        
                        $open_duration = false;
                    }
                }
            }
            fclose($handle);
        }

        // gerando um output com os dados extraídos
        if (isset($arr_ch)) {
            $handle = fopen($out_path.$out_file, 'a');
            if ($handle) {
                while(list($k, $v) = each($arr_ch)) {
                    foreach ($v as $item) {
                        if (isset($item["yt_id"])) {
                            $line = $k."; ".$item["yt_id"]."; ".$item["title"]."; ".$item["duration"]."; ".$item["views"]."; ".$item["url"]."\r\n";
                            fwrite($handle, $line);
                        }
                    }
                }
            }
            fclose($handle);
        }
    }
}
?>
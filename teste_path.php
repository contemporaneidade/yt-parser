<?php

$file = "oi.html";
$handle = @fopen($file, "r");
if ($handle) {
    $open_duration = false;
    $yt_id = "";
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);

        //<a id="thumbnail" class="yt-simple-endpoint inline-block style-scope ytd-thumbnail" aria-hidden="true" tabindex="-1" rel="null" href="https://www.youtube.com/watch?v=iuRYfdmvquk">
        $pattern[0] = "/<a id=\"thumbnail\" class=\"(.*)\" aria-hidden=\"(.*)\" tabindex=\"(.*)\" href=\"(https:\/\/www\.youtube\.com\/watch\?v=(.*))\">/";
        if (preg_match_all($pattern[0], $buffer, $tag_duration)) {
            $open_duration = true;
            $yt_id = $tag_duration[5][0];
        }

        //$pattern[1] = "/[0-9]{1,2}:[0-9]{1,2}/";
        $pattern[1] = "/[0-9]{1,3}:[0-9]{1,2}:[0-9]{1,2}/";
        $pattern[2] = "/[0-9]{1,2}:[0-9]{1,2}/";
        if ($open_duration) {
            if (preg_match_all($pattern[1], $buffer, $duration) || preg_match_all($pattern[2], $buffer, $duration)) {
                $arr_ch["Pirula"][$yt_id]["yt_id"] = $duration[0][0];
                
                $open_duration = false;
            }
        }

        //echo $buffer;
    }
}

//print_r($duration);
print_r($arr_ch);

?>
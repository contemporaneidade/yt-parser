<?php

function getFileName($url, $title) {
    parse_str(parse_url($url, PHP_URL_QUERY), $queries);
    return date("Y-m-h")."--".$queries["v"]."--".preg_replace('/\W+/u', '_', $title).".html";
}

?>
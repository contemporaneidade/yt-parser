<?php

function cleanYT_id($id) {
    parse_str(parse_url($id, PHP_URL_QUERY), $queries); // gambiarra para limpar a url, caso alguém já tenha começado a assitir algum vídeo
    return $queries["v"];
}

?>
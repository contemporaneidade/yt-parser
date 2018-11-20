<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

$row = 1;
if (($handle = fopen("./files/videos/videos.csv", "r")) !== FALSE) {
    
    // criando um arquivo para escrever a saída
    $handle2 = fopen("./files/output/".date("Y-m-d_h-i-s")."_videos.csv", 'w');
    if ($handle2) {
        
        // Escrevendo o cabecalho do arquivo
        $cabecalho = "channel; ch_video_title; ch_video_duration; ch_video_views; URL; yt_id; title; likes; dislikes; published; views; description; dwn_dt\n";
        fwrite($handle2, $cabecalho);

        // iniciando o google chrome
        $host = 'http://localhost:4444/wd/hub'; 
        $capabilities = DesiredCapabilities::chrome();
        $driver = RemoteWebDriver::create($host, $capabilities, 5000);

        /* expressões regulares utilizadas */
        // likes e dislikes
        $pattern[0] = "/<yt-formatted-string id=\"text\" class=\"style-scope ytd-toggle-button-renderer style-text\" aria-label=\"(.*) likes\">(.*)<\/yt-formatted-string>/";
        $pattern[1] = "/<yt-formatted-string id=\"text\" class=\"style-scope ytd-toggle-button-renderer style-text\" aria-label=\"(.*) dislikes\">(.*)<\/yt-formatted-string>/";
        
        // data de publicação
        $pattern[2] = "/<span class=\"date style-scope ytd-video-secondary-info-renderer\" slot=\"date\">Published on (.*) ([0-9]{1,2}), ([0-9]{4})<\/span>/";

        // descrição
        $pattern[3] = "/<div id=\"description\" slot=\"content\" class=\"style-scope ytd-video-secondary-info-renderer\">/";
        $pattern[4] = "/<div id=\"collapsible\" class=\"style-scope ytd-metadata-row-container-renderer\" hidden=\"\"><\/div>/";

        // visualizações
        $pattern[5] = "/<span class=\"view-count style-scope yt-view-count-renderer\">(.*) (visualizações|views)<\/span><span class=\"short-view-count style-scope yt-view-count-renderer\">/";

        /* fim expressões regulares */
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $content = "";
            $vid_url = "";
            $vid_title = "";
            $content = "";
            
            $arr_lk = array();
            $likes = "";
            
            $arr_dislk = array();
            $dislikes = "";
            
            $arr_views = array();
            $views = "";

            $dt = array();
            $dt_pub = "";

            $desc_ini = array();
            $desc_fim = array();
            $description = "";

            $line = "";
            
            // printando o número do vídeo na lista
            echo "Video no. ".$row."\n";

            // navegar para o vídeo
            $vid_url = trim($data[5]);
            $driver->get($vid_url);

            // esperando até 30 segundos as infos do video carregarem
            $driver->wait(30)->until(
                WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                    WebDriverBy::id('upload-info')
                )
            );

            // printando a URL da página
            $vid_url = $driver->getCurrentURL();
            echo "URL: ".$vid_url."\n";

            // printando o título da página
            $vid_title = $driver->getTitle();
            echo "Pagina: ".trim($vid_title)."\n";

            sleep(30);

            // não consegui pegar as infos com xpath, então estou pegando o código fonte da página para utilizar expressões regulares
            $content = $driver->getPageSource();

            // pegando os likes
            if (preg_match($pattern[0], $content, $arr_lk)) {
                $likes = $arr_lk[1];
                echo "Likes: ".$likes."\n";
            }

            // pegando os dislikes
            if (preg_match($pattern[1], $content, $arr_dislk)) {
                $dislikes = $arr_dislk[1];
                echo "Dislikes: ".$dislikes."\n";
            }

            // pegando as visualizações
            if (preg_match($pattern[5], $content, $arr_views)) {
                $views = $arr_views[1];
                echo "Views: ".$views."\n";
            }

            // pegando a data de publicação
            if (preg_match($pattern[2], $content, $dt)) {
                $dt_pub = date('Y-m-d', strtotime($dt[2]." ".$dt[1]." ".$dt[3]));
                echo "Publicacao: ".$dt_pub."\n ======================== \n\n";
            }

            if (preg_match($pattern[3], $content, $desc_ini, PREG_OFFSET_CAPTURE) && preg_match($pattern[4], $content, $desc_fim, PREG_OFFSET_CAPTURE)) {
                $max_char = (integer)$desc_fim[0][1]-(integer)$desc_ini[0][1];
                $description = trim(strip_tags(substr($content, $desc_ini[0][1], $max_char)));
                $description2 =  preg_replace("/\r\n|\r|\n/",'||', "\"".str_replace("\"", "'", $description)."\""); // limpando a descrição
            }

            sleep(10);

            // escrevendo a saída
            $line = $data[0].";".$data[2].";".$data[3].";".$data[4].";".$data[5].";".$data[1].";".$vid_title."; ".$likes."; ".$dislikes."; ".$dt_pub."; ".$views."; ".$description2."; ".date("Y/m/d h:i:s")."\r\n";
            fwrite($handle2, $line);

            ++$row;
        }
        fclose($handle);

        // fechado o browser
        $driver->quit();
    }
}

?>
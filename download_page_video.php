<?php

namespace Facebook\WebDriver;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');
require_once('common/function/getFileName.php');

$row = 1;
if (($handle = fopen("./input/videos.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
          
        // start Chrome with 5 second timeout
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = DesiredCapabilities::chrome();
        $driver = RemoteWebDriver::create($host, $capabilities, 5000);

        // navigate to URL
        //$driver->get('https://www.youtube.com/watch?v=Nco_kh8xJDs');
        $driver->get(trim($data[0]));

        // adding cookie
        /*$driver->manage()->deleteAllCookies();
        $cookie = new Cookie('cookie_name', 'cookie_value');
        $driver->manage()->addCookie($cookie);

        $cookies = $driver->manage()->getCookies();
        print_r($cookies);
        exit;*/

        // click the link 'About'
        //$link = $driver->findElement(
        //    WebDriverBy::id('menu_about')
        //);
        //$link->click();

        // wait until the page is loaded
        //$driver->wait()->until(
        //    WebDriverExpectedCondition::titleContains('About')
        //);
        
        //$driver->manage()->timeouts()->implicitlyWait(100);
        
        sleep(10);
        
        // montando o nome do arquivo com o código fonte
        //$vid_url = $driver->getCurrentURL();
        //$vid_aux = explode("v=", $data[0]);
        //$vid_id = 
        
        // print the title of the current page
        $title = $driver->getTitle();
        echo "The title is '" . $title . "'\n";

        // print the URI of the current page
        $vid_url = $driver->getCurrentURL();
        echo "The current URI is '" . $vid_url . "'\n";

        // montando o nome do arquivo com o código fonte    
        $out_file = getFileName($vid_url, $title);
    

        // gravando um arquivo com o código fonte
        $handle2 = fopen("./files/html_videos/".$out_file, "w+");
        if ($handle2) {
            fwrite($handle2, $driver->getPageSource());
            fclose($handle2);
        }

        //echo "\n\n =================================== \n\n";

        //echo "HTML Source: ".$driver->getPageSource();

        // procurando a quantidade de gostei
        //$driver->findElement(WebDriverBy::id('q'))

        // write 'php' in the search box
        /*$driver->findElement(WebDriverBy::id('q'))
            ->sendKeys('php') // fill the search box
            ->submit(); // submit the whole form

            // wait at most 10 seconds until at least one result is shown
        $driver->wait(10)->until(
            WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
                WebDriverBy::className('gsc-result')
            )
        );*/

        // close the browser
        $driver->quit();

        ++$row;
        exit;
    }
}

?>
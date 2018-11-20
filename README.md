# yt-parser - feito para [GEMAA](http://gemaa.iesp.uerj.br/).

## Descrição
Script para baixar dados de vídeos do YT, tais como: data de publicação, duração, likes, dislikes, visualizaçãoes, título e descrição.

O código utiliza a biblioteca php-webdriver. Para saber mais: 

[php-webdriver](https://github.com/facebook/php-webdriver)

[Selenium project](https://github.com/SeleniumHQ/selenium/)

## Rodando

1) Vá até a página do canal que deseja coletar os dados dos vídeos, clique em "Vídeos" e deslize a página até ao final. Depois que todos os vídeos tiverem aparecido na tela, clique em "Salvar como" e salve em 'files/channels';
1.1) Faça isso para quantos canais quiser coletar os dados;

2) Vá até o terminal e rode:

```
    php channel.php
```

2.1) Assim que terminar de puxar os vídeos dos canais, ele criará uma saída com todos os links de vídeos extraídos: 'files/videos/videos.csv';

3) Se a saída tiver sido criada corretamente é só rodar:

    php video.php

4) Aguarde porque o processo irá demorar um bocado;

4.1) Uma saída será criada em: 'files/output';

5) Fim.

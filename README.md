# yt-parser - feito para [GEMAA](http://gemaa.iesp.uerj.br/).

## Descrição
Parser para coletar dados de vídeos do YT, tais como: data de publicação, duração do vídeo, likes, dislikes, visualizaçãoes, título e descrição.

O código utiliza a biblioteca php-webdriver. Para saber mais: 

[php-webdriver](https://github.com/facebook/php-webdriver)

[Selenium project](https://github.com/SeleniumHQ/selenium/)

## Rodando

1) Vá até a página do canal que deseja coletar os dados dos vídeos, clique em "Vídeos" e deslize a página até ao final. Depois que todos os vídeos tiverem aparecido na tela, clique em "Salvar como" e salve em: 'files/channels';

2) Faça isso para quantos canais quiser coletar os dados;

3) Vá até o terminal e rode:

```
    php channel.php
```

4) Assim que terminar de puxar os vídeos dos canais, ele criará uma saída com todos os links de vídeos extraídos: 'files/videos/videos.csv';

5) Se a saída tiver sido criada corretamente é só rodar:

```
    php video.php
```

6) Aguarde porque o processo irá demorar;

7) Uma saída será criada em: 'files/output';

8) Fim.

![Logomarca](../readme/logo.png)

---

### Exemplos:

* Temos alguns exemplos de como utilizar nossa API, antes de executar qualquer arquivo, será necessário realizar algumas configurações.

**É NECESSÁRIO EXECUTAR O AUTOLOAD PARA CARREGAR A PASTA VENDOR.**

---

#### Pegando como exemplo o arquivo *get.php*:

* O arquivo está solicitando o endpoint para retornar os endereços ips do roteador.

* Vamos entender o que está acontecendo com essa solicitação: 

  * **http://SEU-IP-OU-DOMINIO/rest:** URL do seu Servidor.
  * **/mikrotik/1:** Estaremos listando as informações do roteador com o Id 1.
  * **/ip/address:** Informação solicitada.

```
<?php 

include __DIR__ . "/vendor/autoload.php";

GET("http://SEU-IP-OU-DOMINIO/rest/mikrotik/1/ip/address");

function GET($URL)
{
    $auth = new \GuzzleHttp\Client(['auth' => ["SEU-USUARIO-DE-API", "SUA-SENHA-DE-API"], 'verify' => false]);
    $response = $auth->request("GET", $URL);

    if ($response->getStatusCode() == 200) {
        echo $response->getBody();
    }
}

```

Voce terá o seguinte retorno:

```
[
    {
        ".id": "*1",
        "actual-interface": "ether1",
        "address": "172.23.16.2\/24",
        "disabled": "false",
        "dynamic": "false",
        "interface": "ether1",
        "invalid": "false",
        "network": "172.23.16.0"
    }
]
```

---


### :sparkling_heart: Nos Ajude a Crescer
>Se este Material foi útil para você, me ajude se inscrevendo no meu canal do YouTube.
>
>(https://youtube.com/techlabs94?sub_confirmation=1)
> 
>Isso me incentiva a trazer mais materiais como este e muitos outros de redes e tecnologia.
> 
>## ![YouTube Channel Subscribers](https://img.shields.io/youtube/channel/subscribers/UCWN6suTq5sZGqnSLos992Yw?style=social)
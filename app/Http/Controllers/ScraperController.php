<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    private $dados = [];

    public function scraper()
    {
        $client = new CLient();
        $url = 'https://www.copagril.com.br/precos';
        $page = $client->request('GET', $url);


        // Recupera todos os anos
        $page->filter('.nav')->first()->each(function ($item) {
           $item->filter('li')->each(function ($ano){
            $this->anos[] = $ano->text();
           });
        });


        // Para cada ano roda um foreach
        foreach($this->anos as $ano){

            $url_ano = 'https://www.copagril.com.br/precos/'.$ano;
            $this->ano = $ano;

            // Recupera todos os meses registrados de cada ano, pois ano de 2022 só tem 11 meses
            $page = $client->request('GET', $url_ano);
            $page->filter('.nav')->last()->each(function ($item) {
                $item->filter('li')->each(function ($mes) {
                    $this->meses[] = $mes->text();
                });
            });

            $numero_meses = count($this->meses);

            // Roda uma consulta para cada mês do ano
            // Aplicaçao retorna timeout se usar o $numero_meses
            for ($i = 1; $i <= 10; $i++) {

                $url_ano_mes = $url_ano."/".$i;

                $page = $client->request('GET', $url_ano_mes);

                echo $url_ano_mes = $url_ano."/".$i;


                $this->mes = $i;

                $page->filter('table')->each(function ($dados) {

                    $dados->filter('tr')->each(function ($linha) {

                        $linha->filter('td')->each(function ($celula) {

                            $this->dados[$this->ano][$this->mes][] = [$celula->text()];
                        
                         });
                    });
                });
            }

            $this->meses = array();
        }

        return $this->dados;
    }

    
}

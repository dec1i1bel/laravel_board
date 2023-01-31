<?php

namespace App\View\Components;

use Illuminate\View\Component;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Blade;

/**
 * Получает и рендерит данные в json из api по url, переданному в конструктор
 */
class ExternalApi extends Component
{
    private $url;
    private $headers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->url = 'https://community-open-weather-map.p.rapidapi.com/weather?q=Moscow%2Cru&lat=0&lon=0&callback=test&id=2172797&lang=ru&units=metric';
        $this->headers = [
            'X-RapidAPI-Key' => '371ef07306msh4c6de730e39801dp1616ccjsn600fb9f97d16',
            'X-RapidAPI-Host' => 'community-open-weather-map.p.rapidapi.com',
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $json = $this->getJsonExternalApiData();

        return view('components.external-api', [
            'json' => $json
        ]);
    }

    private function getJsonExternalApiData()
    {
        $client = new Client;

        $response = $client->get($this->url, [
            'headers' => $this->headers
        ]);
        
        $statusCode = $response->getStatusCode();

        if ($statusCode = 200) {
            $body = $response->getBody();
            $res = $body->getContents();
        } else {
            $res = 'error';
        }

        return $res;
    }
}

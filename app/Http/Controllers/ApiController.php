<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Obter os tickers do Gate.io.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGateioTickers()
    {
        // URL da API do Gate.io para obter os tickers de mercado
        $url = "https://api.gateio.ws/api/v4/spot/tickers";

        // Realizar a requisição HTTP usando o Laravel HTTP Client
        $response = Http::get($url);

        // Verificar se a resposta foi bem-sucedida
        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Erro ao obter dados do Gate.io'], 500);
    }

    /**
     * Obter os tickers do Macx (MEXC).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMacxTickers()
    {
        // URL da API do Macx (MEXC)
        $url = "https://contract.mexc.com/api/v1/contract/ticker";

        // Realizar a requisição HTTP usando o Laravel HTTP Client
        $response = Http::get($url);

        // Verificar se a resposta foi bem-sucedida
        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Erro ao obter dados do Macx'], 500);
    }
}

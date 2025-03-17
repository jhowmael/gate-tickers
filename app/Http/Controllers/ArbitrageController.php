<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArbitrageController extends Controller
{
    // Método para salvar os dados de arbitragem
    public function saveArbitrageData(Request $request)
    {
        // Validar os dados recebidos
        $validated = $request->validate([
            'currency_pair' => 'required|string',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'profit_percent' => 'required|numeric',
            'base_volume' => 'required|numeric',
            'volume24' => 'required|numeric',
            'funding_rate' => 'required|numeric',
        ]);

        // Aqui você pode salvar os dados no banco, por exemplo:
        // ArbitrageData::create($validated);

        // Retornar uma resposta de sucesso
        return response()->json(['message' => 'Dados de arbitragem salvos com sucesso']);
    }

    // Método para exibir a página de arbitragem salva
    public function showSavedArbitrage()
    {
        return view('arbitrage.saved');  // Retorna uma página que confirma que os dados foram salvos
    }
}

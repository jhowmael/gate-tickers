@extends('layouts.web')

@section('content')
    <h1>A firma</h1>

    <div id="arbitrage-results" class="ticker-container">
        <!-- Os resultados de arbitragem serão exibidos aqui -->
    </div>

    <div id="error-message" class="error"></div>

    <div id="loading-message" class="loading">Carregando dados...</div>

    <!-- Incluir a biblioteca JQuery para facilitar a requisição AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function openTwoWindows(link1, link2) {
        // Obtém a largura e altura da tela do navegador
        var screenWidth = window.innerWidth;
        var screenHeight = window.innerHeight;

        // Abrir a primeira janela com o primeiro link (à esquerda)
        var window1 = window.open(link1, '_blank', 
            'width=' + Math.floor(screenWidth / 2) + 
            ', height=' + screenHeight + 
            ', left=0, top=0');

        // Abrir a segunda janela com o segundo link (à direita)
        var window2 = window.open(link2, '_blank', 
            'width=' + Math.floor(screenWidth / 2) + 
            ', height=' + screenHeight + 
            ', left=' + Math.floor(screenWidth / 2) + 
            ', top=0');
    }

    // Função para atualizar os dados da API
    function updateTickers() {
        $('#loading-message').show(); // Exibe o carregando

        // Realiza as duas requisições AJAX de forma independente
        $.ajax({
            url: '{{ url('/get_gateio_tickers') }}', // Rota Laravel Gate.io
            method: 'GET',
            dataType: 'json',
            success: function(responseGateio) {
                console.log("Resposta da Gate.io:", responseGateio);  // Log da resposta da Gate.io
                
                $.ajax({
                    url: '{{ url('/get_macx_tickersperpetuo') }}', // Rota Laravel Macx
                    method: 'GET',
                    dataType: 'json',
                    success: function(responseMacx) {
                        console.log("Resposta da Macx:", responseMacx);  // Log da resposta da Macx

                        // Verifique se a resposta da Macx é um objeto e se contém os dados esperados
                        if (responseMacx && typeof responseMacx === 'object' && responseMacx.data) {
                            console.log("Estrutura da Macx:", responseMacx.data);
                            
                            let macxData = {};
                            let macxTickers = responseMacx.data;

                            macxTickers.forEach(function(tickerMacx) {
                                let symbolMacx = tickerMacx.symbol.trim().toUpperCase();
                                macxData[symbolMacx] = tickerMacx;
                            });

                            let arbitrageResultsHTML = '';

                            responseGateio.forEach(function(tickerGateio) {
                                let gateioSymbol = tickerGateio.currency_pair.trim().toUpperCase();

                                if (macxData[gateioSymbol]) {
                                    let buyGateio = tickerGateio.lowest_ask;
                                    let sellMacx = macxData[gateioSymbol].bid1;
                                    let base_volume = tickerGateio.base_volume;
                                    let volume24 = macxData[gateioSymbol].volume24;
                                    let fundingRatpercentage = macxData[gateioSymbol].fundingRate*100;

                                    let profitGateioToMacx = sellMacx - buyGateio;

                                    if (profitGateioToMacx > 0) {
                                        let arbitrageClassGateioToMacx = profitGateioToMacx > 0 ? 'positive' : 'negative';
                                        let profitPercentage = (profitGateioToMacx / buyGateio) * 100;

                                        if (profitPercentage >= 0.3 && profitPercentage <= 20) {
                                            arbitrageResultsHTML += `
                                                <div class="arbitrage-card">
                                                    <div class="arbitrage-header">${tickerGateio.currency_pair}</div>
                                                    <div class="arbitrage-profit-percent ${arbitrageClassGateioToMacx}">
                                                        ${profitPercentage.toFixed(2)}%
                                                    </div>
                                                    <div class="arbitrage-body">
                                                        <p><strong>Compra na Gate.io:</strong> ${buyGateio} USDT</p>
                                                        <p><strong>Venda na Mecx :</strong> ${sellMacx} USDT</p>
                                                        <p><strong>Volume Gate.io:</strong> ${base_volume} USDT</p>
                                                        <p><strong>Volume Mecx:</strong> ${volume24} USDT</p>
                                                        <p id="finaciamento"><strong>Mecx Taxa Finaciamento:</strong> ${fundingRatpercentage}%</p>
                                                        <a href="javascript:void(0);" onclick="openTwoWindows('https://www.gate.io/pt-br/trade/${gateioSymbol}', 'https://futures.mexc.com/pt-PT/exchange/${gateioSymbol}')" class="button-link">Fazer Arbitragem</a>
                                                    </div>
                                                </div>
                                            `;
                                        }
                                    }
                                }
                            });

                            if (arbitrageResultsHTML) {
                                $('#arbitrage-results').html(arbitrageResultsHTML);
                            } else {
                                $('#arbitrage-results').html('<p>Nenhum lucro positivo encontrado entre 0,3% e 20%.</p>');
                            }
                        } else {
                            $('#error-message').html('Estrutura de dados da API Macx inesperada.');
                        }

                        $('#loading-message').hide(); // Oculta o carregando
                    },
                    error: function(xhr, status, error) {
                        $('#error-message').html('Erro ao carregar os dados da API Macx.');
                        $('#loading-message').hide();
                    }
                });
            },
            error: function(xhr, status, error) {
                $('#error-message').html('Erro ao carregar os dados da API Gate.io.');
                $('#loading-message').hide();
            }
        });
    }

    updateTickers();
    setInterval(updateTickers, 10000);
</script>

@endsection
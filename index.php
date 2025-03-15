<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate.io Tickers</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .ticker-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .arbitrage-card {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            position: relative;
            transition: transform 0.3s ease;
        }

        .arbitrage-card:hover {
            transform: translateY(-5px);
        }

        .arbitrage-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: #34495e;
            margin-bottom: 15px;
        }

        .arbitrage-body {
            font-size: 1rem;
            color: #7f8c8d;
            line-height: 1.6;
        }

        .arbitrage-profit {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .positive {
            color: #27ae60;
        }

        .negative {
            color: #e74c3c;
        }

        .arbitrage-profit-percent {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #27ae60;
        }

        .error {
            color: #e74c3c;
            font-weight: bold;
            text-align: center;
            margin-top: 30px;
        }

        .loading {
            text-align: center;
            font-size: 1.2rem;
            color: #3498db;
        }

        .button-link {
            display: block;
            text-align: center;
            background-color: #2980b9;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 30px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .button-link:hover {
            background-color: #1c598d;
            transform: scale(1.05);
        }

        .button-link:active {
            background-color: #1a4c75;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2rem;
            }

            .arbitrage-card {
                padding: 15px;
            }

            .button-link {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }

    </style>
</head>
<body>

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
                url: 'get_gateio_tickers.php', // API Gate.io
                method: 'GET',
                dataType: 'json',
                success: function(responseGateio) {
                    console.log("Resposta da Gate.io:", responseGateio);  // Log da resposta da Gate.io
                    
                    $.ajax({
                        url: 'get_macx_tickersperpetuo.php', // API Macx
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
                                                            <p><strong>Venda na Macx:</strong> ${sellMacx} USDT</p>
                                                            <p><strong>Volume Gate.io:</strong> ${base_volume} USDT</p>
                                                            <p><strong>Volume Macx:</strong> ${volume24} USDT</p>
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

</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate.io Tickers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .ticker-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .arbitrage-card {
            background-color: #f0f8ff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            position: relative;
        }

        .arbitrage-header {
            font-size: 1.25rem;
            font-weight: bold;
        }

        .arbitrage-body {
            margin-top: 10px;
            color: #555;
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
            top: 10px;
            right: 20px;
            font-size: 1.1rem;
            font-weight: bold;
            color: #27ae60;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

    <h1>Gate.io Tickers</h1>

    <div id="arbitrage-results" class="ticker-container">
        <!-- Os resultados de arbitragem serão exibidos aqui -->
    </div>

    <div id="error-message" class="error"></div>

    <!-- Incluir a biblioteca JQuery para facilitar a requisição AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Função para atualizar os dados da API
        function updateTickers() {
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
                                
                                // Criar um objeto onde a chave é o símbolo da Macx
                                let macxData = {};

                                // Se os dados da Macx estiverem dentro de 'data', pegamos os tickers corretamente
                                let macxTickers = responseMacx.data;

                                // Preencher o objeto com os dados da Macx
                                macxTickers.forEach(function(tickerMacx) {
                                    let symbolMacx = tickerMacx.symbol.trim().toUpperCase();
                                    macxData[symbolMacx] = tickerMacx; // O símbolo é a chave e o valor é o ticker completo
                                });

                                // Criar os resultados de arbitragem
                                let arbitrageResultsHTML = '';

                                // Agora, podemos percorrer a resposta da Gate.io e buscar diretamente no objeto da Macx
                                responseGateio.forEach(function(tickerGateio) {
                                    let gateioSymbol = tickerGateio.currency_pair.trim().toUpperCase();

                                    // Verifica se o símbolo da Gate.io está no objeto da Macx
                                    if (macxData[gateioSymbol]) {
                                        let buyGateio = tickerGateio.lowest_ask;
                                        let sellMacx = macxData[gateioSymbol].bid1;
                                        let base_volume = tickerGateio.base_volume;
                                        let volume24 = macxData[gateioSymbol].volume24;


                                        // Calcula o lucro da arbitragem
                                        let profitGateioToMacx = sellMacx - buyGateio;

                                        // Verifica se o lucro é positivo
                                        if (profitGateioToMacx > 0) {
                                            let arbitrageClassGateioToMacx = profitGateioToMacx > 0 ? 'positive' : 'negative';

                                            // Calcula a porcentagem de lucro
                                            let profitPercentage = (profitGateioToMacx / buyGateio) * 100;

                                            // Filtro de porcentagem: entre 0,3% e 20%
                                            if (profitPercentage >= 0.3 && profitPercentage <= 20) {
                                                // Monta o HTML com os resultados da arbitragem (somente dentro da faixa de lucro)
                                                arbitrageResultsHTML += `
                                                    <div class="arbitrage-card">
                                                        <div class="arbitrage-header">${tickerGateio.currency_pair}</div>
                                                        <div class="arbitrage-profit-percent ${arbitrageClassGateioToMacx}">
                                                            ${profitPercentage.toFixed(2)}%
                                                        </div>
                                                        <div class="arbitrage-body">
                                                            <p>Compra na Gate.io: ${buyGateio} USDT</p>
                                                            <p>Venda na Macx: ${sellMacx} USDT</p>
                                                            <p>Volume Gate.io: ${base_volume} USDT</p>
                                                            <p>Volume Macx: ${volume24} USDT</p>
                                                        </div>
                                                    </div>
                                                `;
                                            }
                                        }
                                    }
                                });

                                // Exibe os resultados de arbitragem no HTML (somente positivos dentro da faixa de lucro)
                                if (arbitrageResultsHTML) {
                                    $('#arbitrage-results').html(arbitrageResultsHTML);
                                } else {
                                    $('#arbitrage-results').html('<p>Nenhum lucro positivo encontrado entre 0,3% e 20%.</p>');
                                }
                            } else {
                                $('#error-message').html('Estrutura de dados da API Macx inesperada.');
                                console.log('Resposta inesperada da API Macx:', responseMacx);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#error-message').html('Erro ao carregar os dados da API Macx.');
                            console.error('Erro na requisição da API Macx:', error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    $('#error-message').html('Erro ao carregar os dados da API Gate.io.');
                    console.error('Erro na requisição da API Gate.io:', error);
                }
            });
        }

        // Chama a função updateTickers para carregar os dados da API ao carregar a página
        updateTickers();

        // Atualiza os dados a cada 20 segundos (20000 milissegundos)
        setInterval(updateTickers, 20000);
    </script>

</body>
</html>

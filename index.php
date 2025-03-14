<?php
// Você pode manter este bloco PHP se quiser manipular mais informações no back-end.
// Mas aqui, vamos apenas carregar a página normalmente.
?>

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

        .ticker-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .ticker-card:hover {
            transform: scale(1.05);
        }

        .ticker-header {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .ticker-body {
            margin-top: 10px;
            color: #555;
        }

        .ticker-body p {
            margin: 5px 0;
        }

        .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #27ae60;
        }

        .change {
            color: #e74c3c;
        }

        .positive {
            color: #27ae60;
        }

        .negative {
            color: #e74c3c;
        }

        .info {
            font-size: 0.9rem;
            color: #7f8c8d;
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

    <div id="tickers" class="ticker-container">
        <!-- Os dados dos tickers serão exibidos aqui -->
    </div>

    <div id="error-message" class="error"></div>

    <!-- Incluir a biblioteca JQuery para facilitar a requisição AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Função para atualizar os dados da API
        function updateTickers() {
            $.ajax({
                url: 'get_gateio_tickers.php', // Arquivo PHP para buscar os dados da API
                method: 'GET',
                success: function(data) {
                    // Limpar o erro caso os dados sejam recebidos corretamente
                    $('#error-message').html('');

                    // Converter os dados para formato JSON
                    let tickers = JSON.parse(data);
                    
                    // Verificar se os dados foram recebidos corretamente
                    if (Array.isArray(tickers)) {
                        let tickersHTML = '';

                        // Criar a estrutura HTML para cada ticker
                        tickers.forEach(function(ticker) {
                            let priceChangeClass = ticker.change_percentage > 0 ? 'positive' : 'negative';

                            tickersHTML += `
                                <div class="ticker-card">
                                    <div class="ticker-header">${ticker.currency_pair}</div>
                                    <div class="ticker-body">
                                        <p class="price">${ticker.last} USDT</p>
                                        <p class="change ${priceChangeClass}">Variação: ${ticker.change_percentage}%</p>
                                        <p class="info">Maior 24h: ${ticker.high_24h} USDT</p>
                                        <p class="info">Menor 24h: ${ticker.low_24h} USDT</p>
                                    </div>
                                </div>
                            `;
                        });

                        // Atualiza a div #tickers com os novos dados
                        $('#tickers').html(tickersHTML);
                    } else {
                        // Caso os dados não sejam válidos, exibe uma mensagem de erro
                        $('#error-message').html('Erro ao carregar os dados. Tente novamente.');
                    }
                },
                error: function() {
                    // Exibe uma mensagem de erro se a requisição falhar
                    $('#error-message').html('Erro na requisição. Tente novamente.');
                }
            });
        }

        // Chama a função updateTickers para carregar os dados da API ao carregar a página
        updateTickers();

        // Atualiza os dados a cada 5 segundos (5000 milissegundos)
        setInterval(updateTickers, 1000);
    </script>

</body>
</html>

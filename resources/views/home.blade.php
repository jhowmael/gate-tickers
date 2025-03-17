@extends('layouts.web')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="dashboard-header text-center">
                    <h1>Dashboard de Operações</h1>
                    <p>Visualize o desempenho das suas operações de arbitragem</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Card de Resumo das Operações -->
            <div class="col-md-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <h4>Total de Operações</h4>
                        <p id="totalOperations">0</p>
                    </div>
                </div>
            </div>

            <!-- Card de Lucro Total -->
            <div class="col-md-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <h4>Lucro Total</h4>
                        <p id="totalProfit">0%</p>
                    </div>
                </div>
            </div>

            <!-- Card de ROI Médio -->
            <div class="col-md-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <h4>ROI Médio</h4>
                        <p id="averageROI">0%</p>
                    </div>
                </div>
            </div>

            <!-- Card de Progresso Médio -->
            <div class="col-md-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <h4>Progresso Médio</h4>
                        <p id="averageProgress">0%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div id="operationsList" class="operations-list">
                    <!-- As operações serão listadas aqui -->
                </div>
            </div>
        </div>
        <div id="error-message" class="error"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.onload = function() {
            var operations = JSON.parse(localStorage.getItem('operations')) || [];

            // Atualiza o resumo com as informações das operações
            updateSummary(operations);

            // Exibe as operações
            displayOperations(operations);
        };

        function updateSummary(operations) {
            var totalOperations = operations.length;
            var totalProfit = 0;
            var totalROI = 0;
            var totalProgress = 0;

            operations.forEach(function(operation) {
                totalProfit += parseFloat(operation.lucroFinal);
                totalROI += parseFloat(operation.entryProfit); // ROI baseado no lucro de entrada
                totalProgress += parseFloat(operation.lucroFinal); // Progresso baseado no lucro final de cada operação
            });

            // Cálculos do resumo
            var averageROI = totalROI / totalOperations || 0;
            var averageProgress = totalProgress / totalOperations || 0;

            document.getElementById('totalOperations').textContent = totalOperations;
            document.getElementById('totalProfit').textContent = (totalProfit * 10).toFixed(2) + '$'; // Lucro total em dólares
            document.getElementById('averageROI').textContent = averageROI.toFixed(2) + '%';
            document.getElementById('averageProgress').textContent = averageProgress.toFixed(2) + '%';
        }

        function displayOperations(operations) {
            var operationsList = document.getElementById('operationsList');
            operationsList.innerHTML = ''; // Limpa a lista antes de renderizar novamente

            if (operations.length > 0) {
                operations.forEach(function(operation, index) {
                    var operationItem = document.createElement('div');
                    operationItem.classList.add('operation-item');
                    // Calcular lucro em dólares (baseado em 1000 dólares de entrada)
                    var profitInDollars = (parseFloat(operation.lucroFinal) / 100) * 1000;

                    operationItem.innerHTML = `
                        <div class="card operation-card">
                            <div class="card-body">
                                <button class="close-btn" onclick="deleteOperation(${index})">X</button>
                                <h5>Operação ${index + 1}</h5>
                                <p><strong>Banca Inicial:</strong> $${operation.bancaInicial}</p>
                                <p><strong>Lucro Final:</strong> $${profitInDollars.toFixed(2)}</p>
                                <p><strong>Lucro de Entrada:</strong> ${operation.entryProfit}%</p>
                                <p><strong>Lucro de Saída:</strong> ${operation.exitProfit}%</p>
                            </div>
                        </div>
                    `;
                    operationsList.appendChild(operationItem);
                });
            } else {
                operationsList.innerHTML = 'Nenhuma operação registrada.';
            }
        }

        function deleteOperation(index) {
            var operations = JSON.parse(localStorage.getItem('operations')) || [];
            operations.splice(index, 1); // Remove a operação
            localStorage.setItem('operations', JSON.stringify(operations)); // Atualiza o localStorage
            updateSummary(operations); // Atualiza o resumo
            displayOperations(operations); // Atualiza a lista de operações
        }
    </script>

    <style>
        .container {
            margin-top: 30px;
        }
        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: #333;
        }
        .dashboard-header p {
            font-size: 1.2rem;
            color: #777;
        }
        .summary-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .summary-card .card-body {
            text-align: center;
            padding: 30px;
        }
        .summary-card h4 {
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 15px;
        }
        .summary-card p {
            font-size: 2rem;
            font-weight: 600;
            color: #fff;
        }
        .operations-list {
            margin-top: 40px;
        }
        .operation-card {
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .operation-card .card-body {
            padding: 20px;
        }
        .operation-item h5 {
            font-size: 1.25rem;
            color: #fff;
        }
        .operation-item p {
            color: #fff;
            font-size: 1rem;
            margin: 5px 0;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 18px;
            color: #ff0000;
            cursor: pointer;
        }
        .close-btn:hover {
            color: #cc0000;
        }
        .loading, .error {
            text-align: center;
            margin-top: 20px;
            color: #999;
        }
    </style>
@endsection

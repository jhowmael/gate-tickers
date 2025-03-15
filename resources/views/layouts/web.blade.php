<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickers</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/821b65200f.js" crossorigin="anonymous"></script>
    <style>
        /* Estilo para garantir que o rodapé fique no final da tela */
        html,
        body {
            height: 100%;
            margin: 0;
            background-color: gray;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .content {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .flex-grow-1 {
            flex: 1;
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
        #finaciamento{
            color : #e74c3c;
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
    <div class="content d-flex flex-column">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">TICKERS</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Sobre nós</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('arbitration') ? 'active' : '' }}" href="{{ route('arbitration') }}">Arbitragem</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('operation') ? 'active' : '' }}" href="{{ route('operation') }}">Operação</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contato</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                        <li class="nav-item">
                            <a class="btn btn-light me-2" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light" href="{{ route('register') }}">Registrar</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">{{ auth()->user()->name }}</a>
                        </li>
                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light">Logout</button>
                            </form>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="flex-grow-1">
            <div class="container">
                @yield('content')
            </div>
        </div>

        <!-- Rodapé -->
        <footer class="bg-dark text-white text-center py-3 mt-auto">
            <div class="container">
                <p class="mb-0">&copy; {{ date('Y') }} TICKERS. Todos os direitos reservados.</p>
                <p class="mb-0">
                    <a href="{{ route('contact') }}" class="text-white">Contato</a> |
                </p>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
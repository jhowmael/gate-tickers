@extends('layouts.web')

@section('content')
    <div class="about-us">
        <h1>Sobre Nós</h1>

        <p>Somos uma empresa especializada no desenvolvimento de software para arbitragem de criptomoedas. Nosso objetivo é proporcionar ferramentas avançadas para identificar oportunidades de arbitragem no mercado de criptomoedas, ajudando nossos clientes a maximizar lucros por meio da análise de diferenças de preços entre diversas exchanges.</p>

        <h3>O Que Fazemos</h3>
        <p>Nossa plataforma utiliza algoritmos inteligentes para analisar múltiplos mercados de criptomoedas, possibilitando a identificação de momentos em que as diferenças de preços entre exchanges são significativas. Isso permite que os usuários aproveitem essas oportunidades para realizar transações lucrativas.</p>

        <h3>Nosso Compromisso</h3>
        <p>Nosso compromisso é com a excelência no desenvolvimento de software, com foco em precisão, rapidez e segurança. Buscamos sempre fornecer a melhor experiência de usuário, com uma interface intuitiva e soluções tecnológicas que atendem às necessidades de traders e investidores no mercado de criptomoedas.</p>

        <h3>Isenção de Responsabilidade</h3>
        <p>Embora ofereçamos ferramentas avançadas para arbitragem, é importante destacar que a utilização de nossas soluções não garante lucros e envolve riscos associados ao mercado de criptomoedas. Não nos responsabilizamos por perdas financeiras decorrentes do uso de nossas ferramentas. Recomendamos que nossos usuários realizem suas próprias pesquisas e, caso necessário, consultem um profissional financeiro antes de realizar qualquer operação de arbitragem.</p>

        <h3>Contato</h3>
        <p>Para mais informações, dúvidas ou sugestões, entre em contato conosco através de nosso e-mail <strong>contato@empresa.com</strong>.</p>
    </div>

    <!-- Adicionando estilo CSS -->
    <style>
        .about-us {
            width: 100%;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h3 {
            margin-top: 20px;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .about-us a {
            color: #007bff;
            text-decoration: none;
        }

        .about-us a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

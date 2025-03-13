<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao Manager Box</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-50 font-sans antialiased">

    <!-- Header -->
    <header class="bg-white py-6 shadow-md">
        <div class="container mx-auto flex items-center justify-between px-6">
            <a href="/" class="text-2xl font-semibold text-gray-800 hover:text-indigo-600 transition duration-300">
                ManagerBox
            </a>
            <nav class="space-x-6">
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 transition duration-300">Login</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-3 rounded-full hover:bg-indigo-700 transition duration-300">Sign Up</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-indigo-50 py-24">
        <div class="container mx-auto text-center">
            <h1 class="text-5xl font-extrabold text-gray-800 mb-6 leading-tight">
                Gerencie seu estoque de forma inteligente e eficiente
            </h1>
            <p class="text-xl text-gray-600 mb-12">
                Simplifique o controle dos seus produtos com a nossa solução completa, projetada para otimizar o seu negócio.
            </p>
            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-10 py-4 rounded-full font-semibold hover:bg-indigo-700 transition duration-300">Começar Agora</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-12">Recursos Principais</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="bg-white shadow-xl rounded-2xl p-8 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mx-auto mb-6">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Controle Total</h3>
                    <p class="text-gray-600">Acompanhe cada detalhe do seu estoque, desde a entrada até a saída de produtos.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 mt-4 inline-block">Saiba mais</a>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white shadow-xl rounded-2xl p-8 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mx-auto mb-6">
                        <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Relatórios Detalhados</h3>
                    <p class="text-gray-600">Gere relatórios precisos para otimizar suas decisões e evitar perdas.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 mt-4 inline-block">Saiba mais</a>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white shadow-xl rounded-2xl p-8 hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mx-auto mb-6">
                        <i class="fas fa-bell text-yellow-500 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Alertas Inteligentes</h3>
                    <p class="text-gray-600">Receba notificações quando seus produtos estiverem acabando ou próximos do vencimento.</p>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 mt-4 inline-block">Saiba mais</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-12">Escolha o plano ideal para você</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pricing Card 1 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Grátis</h3>
                    <p class="text-gray-600 mb-6">Ideal para pequenas operações</p>
                    <div class="text-3xl font-bold text-gray-800 mb-4">R$0</div>
                    <button class="bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-700 transition duration-300">Começar</button>
                </div>

                <!-- Pricing Card 2 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Popular</h3>
                    <p class="text-gray-600 mb-6">Para pequenas e médias empresas</p>
                    <div class="text-3xl font-bold text-gray-800 mb-4">R$29,90<span class="text-sm text-gray-600">/mês</span></div>
                    <button class="bg-blue-500 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition duration-300">Assinar</button>
                </div>

                <!-- Pricing Card 3 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition duration-300">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Enterprise</h3>
                    <p class="text-gray-600 mb-6">Para grandes operações</p>
                    <div class="text-3xl font-bold text-gray-800 mb-4">R$49,90<span class="text-sm text-gray-600">/mês</span></div>
                    <button class="bg-indigo-500 text-white px-6 py-3 rounded-full hover:bg-indigo-700 transition duration-300">Assinar</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-gray-100 py-20">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-12">O que nossos usuários dizem</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl shadow-md p-8 hover:shadow-xl transition duration-300">
                    <p class="text-gray-600 mb-6">"Este sistema de gerenciamento de estoque simplificou muito a minha vida. Agora consigo controlar tudo de forma eficiente."</p>
                    <div class="flex items-center justify-center mt-6">
                        <span class="font-semibold text-gray-800">João Silva, Empresário</span>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl shadow-md p-8 hover:shadow-xl transition duration-300">
                    <p class="text-gray-600 mb-6">"Os relatórios detalhados me ajudaram a tomar decisões mais informadas e a otimizar meu estoque. Recomendo!"</p>
                    <div class="flex items-center justify-center mt-6">
                        <span class="font-semibold text-gray-800">Maria Oliveira, Gerente</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-12">Perguntas Frequentes</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- FAQ 1 -->
                <div class="bg-white rounded-2xl shadow-md p-8 hover:shadow-xl transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Como faço para me cadastrar?</h3>
                    <p class="text-gray-600">Basta clicar no botão de registro e preencher seus dados.</p>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white rounded-2xl shadow-md p-8 hover:shadow-xl transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Posso cancelar a qualquer momento?</h3>
                    <p class="text-gray-600">Sim! O cancelamento pode ser feito a qualquer momento sem custos adicionais.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-indigo-600 text-white text-center">
        <div class="container mx-auto">
            <h2 class="text-3xl font-semibold mb-8">Quer saber mais? Entre em contato!</h2>
            <p class="text-lg mb-12">Estamos prontos para te ajudar.</p>
            <a href="#" class="bg-white text-indigo-600 px-10 py-4 rounded-full font-semibold hover:bg-gray-200 transition duration-300 inline-block">Fale Conosco</a>
        </div>
    </section>

    {{-- <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto text-center">
            <p>© 2025 ManagerBox. Todos os direitos reservados.</p>
            <div class="mt-4">
                <a href="#" class="text-gray-300 hover:text-white mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-300 hover:text-white mx-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-300 hover:text-white mx-2"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer> --}}
    @include('layouts.footer')

</body>

</html>

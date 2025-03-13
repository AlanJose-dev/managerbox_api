<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2">
            <div class="text-center text-2xl font-semibold">{{auth()->user()->relatedCompany->fantasy_name}}</div>
            <nav>
            <nav class="flex-1"> <!-- Adicionado flex-1 para ocupar todo o espaço disponível -->
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('items.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Estoque</a>
                <a href="{{ route('categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Categorias</a>
                <a href="{{ route('stock-movements.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"> <i class="fas fa-history mr-2"></i>Movimentações</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <div class="flex-1 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-semibold">Bem-vindo, {{ auth()->user()->name }}!</h1>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Sair</button>
                    </form>
                </div>

                @if ($errors->any())
                    <div class="text-red-500">
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                @endif
            
            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">Total de Produtos</h2>
                    <p class="text-gray-600 text-2xl">{{ $totalItemsInStock }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">Movimentações Recentes</h2>
                    <p class="text-gray-600 text-2xl">{{ $totalStockMovements }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">Produtos em Baixa</h2>
                    <p class="text-gray-600 text-2xl">{{ $lowStockCount }}</p>
                </div>
            </div>
            
            <!-- Tabela de Itens -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-lg font-bold">Itens em Estoque</h2>
                @if(!$items->isEmpty())
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Nome</th>
                            <th class="py-2 px-4 border-b">Quantidade</th>
                            <th class="py-2 px-4 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="py-2 px-4 border-b text-center">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b text-center">{{ $item->current_quantity }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('items.show', $item->id) }}" class="text-white p-2 rounded-lg bg-blue-700 hover:bg-blue-800 mx-2">Detalhes</a>
                                    <a href="{{ route('items.movements', $item->id) }}" class="text-white p-2 rounded-lg bg-blue-800 hover:bg-blue-950 mx-2">Movimentações</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center">
                        <p>Sua empresa ainda não possui itens cadastrados. 
                            <a href="{{route('items.create')}}" class="text-blue-500 cursor-pointer hover:underline">Adicionar Novo Item</a>
                        </p>
                    </div>
                @endif
            </div>
            
            <!-- Tabela de Movimentações -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-bold mb-4">Últimas Movimentações</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Produto</th>
                                <th class="py-2 px-4 border-b">Responsável</th> <!-- Nova coluna -->
                                <th class="py-2 px-4 border-b">Quantidade</th>
                                <th class="py-2 px-4 border-b">Tipo</th>
                                <th class="py-2 px-4 border-b">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movements as $movement)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $movement->item?->name }}</td>
                                    <td class="py-2 px-4 border-b">{{ $movement->user?->name }}</td> <!-- Novo campo -->
                                    <td class="py-2 px-4 border-b">{{ $movement->quantity }}</td>
                                    <td class="py-2 px-4 border-b">{{ $movement->movement_type }}</td>
                                    <td class="py-2 px-4 border-b">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Categorias -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-lg font-bold mb-4">Categorias</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($categories as $category)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text">Produtos: {{ $category->items_count }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

                        <!-- Bloco para Produtos em Baixa -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h2 class="text-lg font-bold mb-4">Produtos em Baixa</h2>
                @if ($lowStockItems->isEmpty())
                    <p>Nenhum produto em baixa.</p>
                @else
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Nome</th>
                                <th class="py-2 px-4 border-b">Quantidade</th>
                                <th class="py-2 px-4 border-b">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockItems as $item)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                    <td class="py-2 px-4 border-b">
                                        <span class="text-danger font-weight-bold">{{ $item->current_quantity }}</span>
                                        <small class="text-danger"> (Baixo estoque)</small>
                                    </td>
                                    <td class="py-2 px-4 border-b">
                                        <a href="{{ route('items.show', $item->id) }}" class="text-blue-500">Detalhes</a>
                                        <a href="{{ route('items.movements', $item->id) }}" class="text-blue-500">Movimentações</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <footer class="bg-white py-4 mt-6 shadow-md mt-auto">
                <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
                    <div class="md:flex md:justify-between">
                        <div class="mb-6 md:mb-0">
                            <a href="#" class="flex items-center">
                                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-black">ManagerBox</span>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-8 sm:gap-12 sm:grid-cols-3">
                            <div>
                                <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-black">Suporte</h2>
                                <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                    <li class="mb-4"><a href="#" class="hover:underline">Contato</a></li>
                                </ul>
                            </div>
                            <div>
                                <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-black">Recursos</h2>
                                <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                    <li class="mb-4"><a href="https://github.com/AlanJose-dev/managerbox_api" class="hover:underline">Documentação</a></li>
                                    <li class="mb-4"><a href="https://github.com/AlanJose-dev/managerbox_api" class="hover:underline">Sobre</a></li>
                                </ul>
                            </div>
                            <div>
                                <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-black">Equipe</h2>
                                <ul class="text-gray-500 dark:text-gray-400 font-medium">
                                    <li class="mb-4"><a href="https://github.com/AlanJose-dev" class="hover:underline">Alan José</a></li>
                                    <li class="mb-4"><a href="https://github.com/EmersonLima03" class="hover:underline">Emerson Lima</a></li>
                                    <li class="mb-4"><a href="https://github.com/user3" class="hover:underline">Davi Henrique</a></li>
                                    <li class="mb-4"><a href="https://github.com/GabrielVnM08" class="hover:underline">Gabriel Vidal</a></li>
                                    <li><a href="https://github.com/user5" class="hover:underline">Sócrates Luna</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
                    <div class="sm:flex sm:items-center sm:justify-between">
                        <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2025 ManagerBox. Todos os direitos reservados.</span>
                        <div class="flex mt-4 sm:justify-center sm:mt-0 space-x-8">
                            <a href="#" class="text-gray-500 hover:text-gray-900">
                                <span class="sr-only">LinkedIn</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16 0a4 4 0 0 1 4 4v12a4 4 0 0 1-4 4H4a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4h12ZM7 16V7H4v9h3ZM5.5 5.5A1.5 1.5 0 1 0 5.5 2a1.5 1.5 0 0 0 0 3.5ZM16 16h-3V11c0-1.1-.9-2-2-2s-2 .9-2 2v5h-3V7h3v1c.6-.8 1.5-1 2.5-1 2 0 3.5 1.5 3.5 3.5V16Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="https://github.com/AlanJose-dev/managerbox_api" class="text-gray-500 hover:text-gray-900">
                                <span class="sr-only">GitHub</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 0a10 10 0 0 0-3 19.5c.5.1.7-.2.7-.5v-1.8c-2.6.5-3.1-1.2-3.1-1.2a2.5 2.5 0 0 0-1-1.3c-.8-.6.1-.6.1-.6a2 2 0 0 1 1.5 1 2 2 0 0 0 2.7.8c.1-.4.3-.8.6-1.2-2.2-.2-4.5-1-4.5-4.8a3.8 3.8 0 0 1 1-2.6c-.1-.3-.4-1.3.1-2.6 0 0 .8-.3 2.7 1a9.4 9.4 0 0 1 5 0c1.9-1.3 2.7-1 2.7-1 .5 1.3.2 2.3.1 2.6a3.8 3.8 0 0 1 1 2.6c0 3.8-2.3 4.6-4.5 4.8.3.2.5.7.5 1.5v2.2c0 .3.2.6.7.5A10 10 0 0 0 10 0Z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</body>
</html>

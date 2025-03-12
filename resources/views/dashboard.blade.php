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
            <div class="text-center text-2xl font-semibold">ManagerBox</div>
            <nav>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('items.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Itens</a>
                <a href="{{ route('categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Categorias</a>
                <a href="{{ route('stock-movements.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700"> <i class="fas fa-history mr-2"></i>Histórico Completo</a>
            </nav>
        </div>
        
        <!-- Main Content -->
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
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->current_quantity }}</td>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ route('items.show', $item->id) }}" class="text-blue-500">Detalhes</a>
                                    <a href="{{ route('items.movements', $item->id) }}" class="text-blue-500">Movimentações</a>
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

        </div>
    </div>
</body>
</html>

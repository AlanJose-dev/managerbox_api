<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@2.0.0"></script>
    <style>
        /* Estilos para o layout */
        .main-content {
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        .chart-container {
            height: 300px;
            position: relative;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .chartjs-legend {
            padding: 10px 0;
            text-align: center;
        }
        .chartjs-legend li {
            display: inline-block;
            margin: 0 10px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gray-900 text-white w-64 space-y-6 py-7 px-2 flex-shrink-0">
            <div class="text-center text-2xl font-semibold">ManagerBox</div>
            <nav class="space-y-2">
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('items.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Itens</a>
                <a href="{{ route('categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Gerenciar Categorias</a>
                <a href="{{ route('stock-movements.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <i class="fas fa-history mr-2"></i>Histórico Completo
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <div class="flex justify-between items-center p-4 bg-white shadow-sm">
                <h1 class="text-2xl font-semibold">Bem-vindo, {{ auth()->user()->name }}!</h1>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">
                        Sair
                    </button>
                </form>
            </div>

            <!-- Conteúdo Rolável -->
            <div class="main-content p-6 space-y-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Cards Superiores -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold">Total de Produtos</h2>
                        <p class="text-2xl text-gray-600 mt-2">{{ $totalItemsInStock }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold">Movimentações</h2>
                        <p class="text-2xl text-gray-600 mt-2">{{ $totalStockMovements }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold">Produtos em Baixa</h2>
                        <p class="text-2xl text-gray-600 mt-2">{{ $lowStockCount }}</p>
                    </div>
                </div>

                <!-- Filtro de Tempo -->
                <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
                    <label for="period" class="mr-2">Período:</label>
                    <select name="period" id="period" class="border rounded py-2 px-3">
                        <option value="last_30_days">Últimos 30 dias</option>
                        <option value="last_6_months">Últimos 6 meses</option>
                        <option value="current_year">Ano atual</option>
                    </select>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Filtrar</button>
                </form>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Gráfico de Movimentações por Tipo -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Movimentações por Tipo</h2>
                        <div class="chart-container">
                            <canvas id="movementTypesChart"></canvas>
                        </div>
                    </div>
                     <!-- Gráfico de Movimentações Mensais -->
                     <div class="bg-white p-4 rounded-lg shadow">
                         <h2 class="text-lg font-semibold mb-4">Movimentações Mensais</h2>
                         <div class="chart-container">
                             <canvas id="monthlyMovementsChart"></canvas>
                         </div>
                     </div>
                 </div>

            <!-- Gráfico de Produtos em Baixa -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h2 class="text-lg font-semibold mb-4">Produtos em Baixa (Top 5)</h2>
                <div class="chart-container">
                    <canvas id="lowStockItemsChart"></canvas>
                </div>
            </div>

           <!-- Valor Total do Estoque e Variação -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Valor Total do Estoque</h2>
                    <p class="text-2xl text-gray-600">R$ {{ number_format($totalStockValue, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Variação de Estoque (Mês Anterior)</h2>
                    <p class="text-2xl text-gray-600">{{ $stockVariation }} itens</p>
                </div>
            </div>
                  

                   <!-- Mapa de Calor por Dia da Semana -->
<div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-4">Mapa de Calor - Movimentações</h2>
    <div class="chart-container">
        <canvas id="heatmapChart"></canvas>
    </div>
</div>

          <!-- Curva ABC -->
           <div class="bg-white p-4 rounded-lg shadow">
               <h2 class="text-lg font-semibold mb-4">Curva ABC de Produtos</h2>
                   <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                   <div class="bg-green-100 p-3 rounded">
                       <h3 class="font-semibold">Classe A</h3>
                       <ul>
                           @foreach($abcA as $item)
                               <li>{{ $item->name }}</li>
                           @endforeach
                       </ul>
                   </div>
                   <div class="bg-yellow-100 p-3 rounded">
                       <h3 class="font-semibold">Classe B</h3>
                       <ul>
                           @foreach($abcB as $item)
                               <li>{{ $item->name }}</li>
                           @endforeach
                       </ul>
                   </div>
                   <div class="bg-red-100 p-3 rounded">
                       <h3 class="font-semibold">Classe C</h3>
                       <ul>
                           @foreach($abcC as $item)
                               <li>{{ $item->name }}</li>
                           @endforeach
                       </ul>
                   </div>
               </div>
           </div>

                <!-- Tabelas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Últimos Itens</h2>
                        <div class="table-container">
                            @if($items->isEmpty())
                                <p class="text-gray-500">Nenhum item cadastrado</p>
                            @else
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left p-2">Nome</th>
                                            <th class="text-left p-2">Quantidade</th>
                                            <th class="text-left p-2">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="p-2">{{ $item->name }}</td>
                                            <td class="p-2">{{ $item->current_quantity }}</td>
                                            <td class="p-2 space-x-2">
                                                <a href="{{ route('items.show', $item->id) }}" class="text-blue-500 hover:text-blue-700">Detalhes</a>
                                                <a href="{{ route('items.movements', $item->id) }}" class="text-blue-500 hover:text-blue-700">Movimentações</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Últimas Movimentações</h2>
                        <div class="table-container">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left p-2">Produto</th>
                                        <th class="text-left p-2">Tipo</th>
                                        <th class="text-left p-2">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movements as $movement)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="p-2">{{ $movement->item?->name ?? 'N/A' }}</td>
                                        <td class="p-2">
                                            <span class="px-2 py-1 rounded-full text-sm 
                                                {{ $movement->movement_type === 'checkin' 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-red-100 text-red-800' }}">
                                                {{ $movement->movement_type === 'checkin' ? 'Entrada' : 'Saída' }}
                                            </span>
                                        </td>
                                        <td class="p-2">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Alertas de Estoque Crítico -->
                @if($criticalStockAlerts->isNotEmpty())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Alerta de Estoque Crítico!</strong>
                        <span class="block sm:inline">Os seguintes produtos atingiram um nível de estoque crítico:</span>
                        <ul>
                            @foreach($criticalStockAlerts as $item)
                                <li>{{ $item->name }} ({{ $item->current_quantity }} em estoque)</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                 <!-- Ações Rápidas -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-4">Ações Rápidas</h2>
                        <a href="{{ route('items.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Adicionar Novo Item
                        </a>
                </div>

                <!-- Seção Inferior -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Categorias</h2>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($categories as $category)
                            <div class="bg-gray-50 p-3 rounded">
                                <h3 class="font-medium">{{ $category->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->items_count }} itens</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="text-lg font-semibold mb-4">Produtos em Baixa</h2>
                        <div class="table-container">
                            @if($lowStockItems->isEmpty())
                                <p class="text-gray-500">Nenhum produto em baixa</p>
                            @else
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="text-left p-2">Nome</th>
                                            <th class="text-left p-2">Quantidade</th>
                                            <th class="text-left p-2">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowStockItems as $item)
                                        <tr class="hover:bg-gray-50 border-b">
                                            <td class="p-2">{{ $item->name }}</td>
                                            <td class="p-2 text-red-600 font-medium">
                                                {{ $item->current_quantity }}
                                                <span class="text-gray-500 text-sm">(mín: {{ $item->minimum_quantity }})</span>
                                            </td>
                                            <td class="p-2 space-x-2">
                                                <a href="{{ route('items.show', $item->id) }}" class="text-blue-500 hover:text-blue-700">Detalhes</a>
                                                <a href="{{ route('items.movements', $item->id) }}" class="text-blue-500 hover:text-blue-700">Movimentações</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        // Inicializa um gráfico com Chart.js
        function initChart(ctx, type, labels, data, colors) {
            return new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total',
                        data: data,
                        backgroundColor: colors,
                        borderColor: type === 'line' ? colors[0] : undefined,
                        borderWidth: type === 'line' ? 2 : 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: type === 'pie',
                            position: 'top',
                        }
                    }
                }
            });
        }

         // Gráfico de Produtos em Baixa
    if (document.getElementById('lowStockItemsChart')) {
        const ctx = document.getElementById('lowStockItemsChart').getContext('2d');
        const lowStockData = @json($lowStockItemsChart);
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: lowStockData.map(item => item.name),
                datasets: [{
                    label: 'Quantidade em Estoque',
                    data: lowStockData.map(item => item.current_quantity),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

        // Gráfico de Movimentações por Tipo
        if(document.getElementById('movementTypesChart')) {
            const ctx = document.getElementById('movementTypesChart').getContext('2d');
            initChart(ctx, 'pie', 
                @json($movementTypesLabels), 
                @json($movementTypesValues),
                ['#3B82F6', '#EF4444']
            );
        }
        // Gráfico de Movimentações Mensais
        if(document.getElementById('monthlyMovementsChart')) {
            const ctx = document.getElementById('monthlyMovementsChart').getContext('2d');
            initChart(ctx, 'line', 
                @json($monthlyMovementsLabels), 
                @json($monthlyMovementsValues),
                ['#3B82F6']
            );
        }
        // Gráfico de Desempenho por Categoria
        if (document.getElementById('categoryPerformanceChart')) {
            const ctx = document.getElementById('categoryPerformanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($categoryPerformance->pluck('name')),
                    datasets: [{
                        label: 'Quantidade Total',
                        data: @json($categoryPerformance->pluck('quantity')),
                        backgroundColor: '#4CAF50',
                        borderColor: '#388E3C',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

       
        // Mapa de Calor por Dia da Semana
if (document.getElementById('heatmapChart')) {
    const heatmapCtx = document.getElementById('heatmapChart').getContext('2d');
    const heatmapData = @json($heatmapData);

    new Chart(heatmapCtx, {
        type: 'matrix',
        data: {
            datasets: [{
                label: 'Movimentações',
                data: heatmapData.map(item => ({
                    x: item.day,
                    y: item.hour,
                    v: item.total
                })),
                backgroundColor: function(ctx) {
                    const value = ctx.raw.v;
                    if (value === undefined || value === null) {
                        return 'rgba(220, 220, 220, 0.5)'; // Cor para células sem valor
                    }
                    const alpha = Math.min(value / 20, 1); // Ajuste a intensidade
                    return `rgba(54, 162, 235, ${alpha})`; // Cor azul com intensidade
                },
                borderWidth: 1,
                width: ({chart}) => (chart.chartArea || {}).width / 7 - 10,
                height: ({chart}) => (chart.chartArea || {}).height / 24 - 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'category',
                    labels: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    title: {
                        display: true,
                        text: 'Dia da Semana'
                    }
                },
                y: {
                    type: 'category',
                    labels: [...Array(24).keys()].map(i => i + 'h'),
                    title: {
                        display: true,
                        text: 'Hora do Dia'
                    },
                    reverse: true // Inverter a ordem das horas
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        title: () => '',
                        label: (context) => {
                            const day = context.dataset.data[context.dataIndex].x;
                            const hour = context.dataset.data[context.dataIndex].y;
                            const value = context.dataset.data[context.dataIndex].v;
                            return `${day}, ${hour}: ${value} movimentações`;
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        }
    });
}
});
</script>
</body>
</html>

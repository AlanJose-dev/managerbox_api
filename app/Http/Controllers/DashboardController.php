<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\StockMovement;
use App\Models\Category;
use App\Models\ItemInStock;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Autenticação do usuário
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->withErrors('error', 'Você precisa estar autenticado para acessar o dashboard.');
        }

        // ID da empresa
        $companyId = Session::get('company_id');

        // Contagem de itens com estoque baixo
        $lowStockCount = DB::table('items_in_stock')
            ->where('company_id', $companyId)
            ->where('current_quantity', '<', 5)
            ->count();

        // Produtos em baixa
        $lowStockItems = ItemInStock::where('company_id', $companyId)
            ->where('current_quantity', '<', 5)
            ->get();

        // Contagem total de itens em estoque
        $totalItemsInStock = ItemInStock::where('company_id', $companyId)->count();

        // Contagem total de movimentações de estoque
        $totalStockMovements = StockMovement::where('company_id', $companyId)->count();

        // Contagem de produtos ativos
        $activeProductsCount = ItemInStock::where('company_id', $companyId)
            ->where('is_active', true)
            ->count();

        // Últimos itens cadastrados
        $items = DB::table('items_in_stock')
            ->where('company_id', $companyId)
            ->where('deleted_at', null)
            ->latest()
            ->take(5)
            ->get();

        // Últimas movimentações
        $movements = StockMovement::with('item')
            ->where('company_id', $companyId)
            ->latest()
            ->take(10)
            ->get();

        // Categorias
        $categories = Category::withCount('items')->get();

        // Preparar dados para o gráfico de movimentações por tipo
        $movementTypesData = StockMovement::select('movement_type', DB::raw('count(*) as total'))
            ->where('company_id', $companyId)
            ->groupBy('movement_type')
            ->get()
            ->pluck('total', 'movement_type')
            ->toArray();

        $movementTypesLabels = array_keys($movementTypesData);
        $movementTypesValues = array_values($movementTypesData);

        // Gráfico de Movimentações Mensais (com filtro de tempo)
        $period = $request->input('period', 'last_6_months'); // Valor padrão

        $startDate = match ($period) {
            'last_30_days' => now()->subDays(30),
            'last_6_months' => now()->subMonths(6),
            'current_year' => now()->startOfYear(),
            default => now()->subMonths(6),
        };

        $monthlyMovementsData = StockMovement::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total'))
            ->where('company_id', $companyId)
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $monthlyMovementsLabels = array_keys($monthlyMovementsData);
        $monthlyMovementsValues = array_values($monthlyMovementsData);

        // Curva ABC
        $abcData = ItemInStock::select('name', DB::raw('current_quantity * cost_price as total_value'))
            ->where('company_id', $companyId)
            ->orderByDesc('total_value')
            ->get();

        // Classificação ABC
        $totalValue = $abcData->sum('total_value');
        $cumulativePercentage = 0;
        $abcA = collect();
        $abcB = collect();
        $abcC = collect();

        foreach ($abcData as $item) {
            $percentage = ($item->total_value / $totalValue) * 100;
            $cumulativePercentage += $percentage;

            if ($cumulativePercentage <= 80) {
                $abcA->push($item);
            } elseif ($cumulativePercentage <= 95) {
                $abcB->push($item);
            } else {
                $abcC->push($item);
            }
        }

        // Desempenho por Categoria
        $categoryPerformance = Category::withSum('items', 'current_quantity')
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'quantity' => (int) $category->items_sum_current_quantity,
                ];
            });
            
        // Mapa de Calor por Dia da Semana
        $heatmapData = StockMovement::select(
            DB::raw("DAYNAME(created_at) as day"),
            DB::raw("HOUR(created_at) as hour"),
            DB::raw("count(*) as total")
        )
        ->where('company_id', $companyId)
        ->groupBy('day', 'hour')
        ->get()
        ->map(function ($item) {
            return [
                'day' => substr($item->day, 0, 3),
                'hour' => $item->hour . 'h',
                'total' => $item->total
            ];
        })
        ->toArray();

        // Gráfico de Produtos em Baixa
        $lowStockItemsChart = ItemInStock::where('company_id', $companyId)
            ->whereColumn('current_quantity', '<=', 'minimum_quantity')
            ->orderBy('current_quantity')
            ->limit(5)
            ->get();

        // Alertas de estoque crítico
        $criticalStockAlerts = ItemInStock::where('company_id', $companyId)
            ->whereRaw('current_quantity <= minimum_quantity * 0.2')
            ->get();

        // Valor total do estoque
        $totalStockValue = ItemInStock::where('company_id', $companyId)
            ->sum(DB::raw('current_quantity * cost_price'));

        // Variação de estoque
        $currentStock = ItemInStock::where('company_id', $companyId)->sum('current_quantity');
        $lastMonthStock = ItemInStock::where('company_id', $companyId)
            ->where('created_at', '<', now()->subMonth())
            ->sum('current_quantity');

        $stockVariation = $currentStock - $lastMonthStock;

        return view('dashboard', [
            'lowStockCount' => $lowStockCount,
            'lowStockItems' => $lowStockItems,
            'totalItemsInStock' => $totalItemsInStock,
            'totalStockMovements' => $totalStockMovements,
            'activeProductsCount' => $activeProductsCount,
            'items' => $items,
            'movements' => $movements,
            'categories' => $categories,
            'movementTypesLabels' => $movementTypesLabels,
            'movementTypesValues' => $movementTypesValues,
            'monthlyMovementsLabels' => $monthlyMovementsLabels,
            'monthlyMovementsValues' => $monthlyMovementsValues,
            'abcA' => $abcA,
            'abcB' => $abcB,
            'abcC' => $abcC,
            'categoryPerformance' => $categoryPerformance,
            'heatmapData' => $heatmapData,
            'lowStockItemsChart' => $lowStockItemsChart,
            'criticalStockAlerts' => $criticalStockAlerts,
            'totalStockValue' => $totalStockValue,
            'stockVariation' => $stockVariation,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ItemInStock;
use App\Models\StockMovement;
use App\Traits\Http\SendJsonResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockMovementsExport;
use Illuminate\Support\Facades\DB;


class StockMovementController extends Controller
{
    use SendJsonResponses;

    /**
     * Display a listing of the resource.
     */
    // Histórico geral
    public function index()
    {
        $movements = StockMovement::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('stock-movements.index', compact('movements'));
    }

    public function itemHistory($id)
    {
        $item = ItemInStock::findOrFail($id);
        $movements = StockMovement::where('item_in_stock_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('items.movements', compact('item', 'movements')); // Ajuste aqui
    }

    public function store(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'movement_type' => 'required|in:checkin,checkout',
                'quantity' => 'required|numeric|min:1',
            ]);

            $item = ItemInStock::findOrFail($id);
            $user = auth()->user();

            if ($request->movement_type == 'checkin') {
                $item->current_quantity += $request->quantity;
            } elseif ($request->movement_type == 'checkout') {
                if ($item->current_quantity < $request->quantity) {
                    return back()->with('error', 'Estoque insuficiente para essa saída.');
                }
                $item->current_quantity -= $request->quantity;
            }

            $item->save();

            StockMovement::create([
                'movement_type' => $request->movement_type,
                'quantity' => $request->quantity,
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'item_in_stock_id' => $id,
            ]);

            DB::commit();

            return back()->with('success', 'Movimentação registrada com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->with('error', 'Ocorreu um erro ao registrar a movimentação: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StockMovement $stockMovement)
    {
        try {
            //        return View::make('')->with($stockMovement)->render();
        } catch (\Exception $exception) {
            Log::error($exception);
            return $this->sendErrorResponse();
        }
    }

    public function export()
    {
        return Excel::download(new StockMovementsExport, 'movimentacoes.xlsx');
    }
}

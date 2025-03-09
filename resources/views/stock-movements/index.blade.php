@extends('layouts.app')
@section('title', 'Histórico de Movimentações')
@section('content')
<div class="mb-6 bg-gray-50 p-4 rounded-lg">
    <form method="GET" class="flex gap-4 items-end">
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Período</label>
                    <input type="date" name="start_date" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tipo</label>
                    <select name="type" class="form-select">
                        <option value="">Todos</option>
                        <option value="checkin">Entrada</option>
                        <option value="checkout">Saída</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn bg-blue-500 text-white">
                        Filtrar
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Histórico Completo de Movimentações</h1>
            <div class="flex gap-2">
                <a href="{{ route('stock-movements.export') }}" 
                   class="btn bg-blue-500 text-white hover:bg-blue-600">
                    Exportar
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 border-b">Data</th>
                        <th class="py-3 px-4 border-b">Item</th>
                        <th class="py-3 px-4 border-b">Responsável</th>
                        <th class="py-3 px-4 border-b">Tipo</th>
                        <th class="py-3 px-4 border-b">Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr>
                        <td class="py-2 px-4 border-b text-center">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $movement->item?->name ?? 'Item não disponível' }}</td>
                        <td class="py-2 px-4 border-b text-center">{{ $movement->user?->name ?? 'Usuário não disponível' }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <span class="px-2 py-1 rounded-full text-xs 
                                {{ $movement->movement_type === 'checkin' 
                                    ? 'bg-green-100 text-green-800' 
                                    : 'bg-red-100 text-red-800' }}">
                                {{ $movement->movement_type === 'checkin' ? 'Entrada' : 'Saída' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b text-center">{{ $movement->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center">Nenhuma movimentação registrada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection

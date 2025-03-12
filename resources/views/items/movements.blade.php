@extends('layouts.app')

@section('title', 'Movimentação de Estoque')

@section('content')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="container">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Movimentação de <b>Estoque</b> - {{ $item->name }}</h2>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('items.index') }}" class="btn btn-secondary">
                            <i class="material-icons">&#xe5c4;</i> Voltar para Listagem
                        </a>
                    </div>
                </div>
            </div>

            <!-- Histórico de Movimentações -->
            <h4 class="text-lg font-semibold text-gray-700 mb-3">Histórico</h4>
            <div class="bg-white shadow-sm rounded-lg">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Quantidade</th>
                            <th>Responsável</th> <!-- Nova coluna -->
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movements as $movement)
                            <tr>
                                <td>{{ $movement->movement_type == 'checkin' ? 'Entrada' : 'Saída' }}</td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->user?->name ?? 'Usuário não disponível' }}</td>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Formulário de Movimentação -->
            <h4 class="text-lg font-semibold text-gray-700 mt-4 mb-3">Nova Movimentação</h4>
            <div class="bg-white p-4 shadow-sm rounded-lg">
                <form action="{{ route('items.movements.store', $item->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="movement_type" class="form-label font-bold">Tipo de Movimentação:</label>
                        <select name="movement_type" class="form-select" required>
                            <option value="checkin">Entrada</option>
                            <option value="checkout">Saída</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label font-bold">Quantidade:</label>
                        <input type="number" name="quantity" class="form-control" required min="1" step="0.01">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="material-icons">&#xE147;</i> Registrar Movimentação
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@endsection

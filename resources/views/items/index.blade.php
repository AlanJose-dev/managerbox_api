@extends('layouts.app')

@section('title', 'Itens em Estoque')

@section('content')
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<div class="container">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Gerenciar <b>Estoque</b></h2>
                    </div>
                    <div class="col-sm-6 text-end">
                        <a href="{{ route('items.create') }}" class="btn btn-success mr-2">Adicionar Novo Item</a>
                        <a href="{{ route('items.export.csv') }}" class="btn btn-primary mr-2">Exportar CSV</a>
                        <a href="{{ route('items.export.pdf') }}" class="btn btn-danger">Exportar PDF</a>
                    </div>
                </div>
            </div>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="" method="GET">
                <div class="form-group mb-3">
                    <label for="search">Pesquisar:</label>
                    <input type="text" id="search" name="search" class="form-control" placeholder="Nome, Categoria ou Quantidade" value="{{ request()->input('search') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="category_id">Filtrar por Categoria:</label>
                    <select name="category_id" class="form-control">
                        <option value="">Todos</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == request()->input('category_id') ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="lowStock" name="lowStock" value="true" {{ request()->input('lowStock') ? 'checked' : '' }}>
                    <label class="form-check-label" for="lowStock">Mostrar apenas produtos em baixa</label>
                </div>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>

            @if ($items->isEmpty())
                <p>Nenhum item em estoque.</p>
            @else
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if ($item->current_quantity < $item->minimum_quantity)
                                        <span class="text-danger font-weight-bold">{{ $item->current_quantity }}</span>
                                        <small class="text-danger"> (Baixo estoque)</small>
                                    @else
                                        {{ $item->current_quantity }}
                                    @endif
                                </td>
                                <td>
                                    <!-- Detalhes -->
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-info btn-sm mr-2">
                                        <i class="material-icons">&#xE417;</i> Detalhes
                                    </a>
                                    <!-- Editar -->
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-primary btn-sm mr-2">
                                        <i class="material-icons">&#xE254;</i> Editar
                                    </a>
                                    <!-- Movimentar Estoque -->
                                    <a href="{{ route('items.movements', $item->id) }}" class="btn btn-warning btn-sm mr-2">
                                        <i class="material-icons">&#xE8CB;</i> Movimentar
                                    </a>
                                    <!-- Excluir -->
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="material-icons">&#xE872;</i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@endsection

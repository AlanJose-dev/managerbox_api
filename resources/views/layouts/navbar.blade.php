<nav class="bg-white border-b border-gray-200 shadow-md">
    <div class="container mx-auto px-6 py-3 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">{{auth()->user()->relatedCompany->fantasy_name}}</a>

        <div class="flex space-x-6">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Dashboard</a>
            <a href="{{ route('items.index') }}" class="text-gray-600 hover:text-gray-900">Estoque</a>
            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900">Categorias</a>
            <a href="{{ route('stock-movements.index') }}" class="text-gray-600 hover:text-gray-900">Movimentações</a>
        </div>

        <div class="flex items-center space-x-4">
            <span class="text-gray-700">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Sair
                </button>
            </form>
        </div>
    </div>
</nav>

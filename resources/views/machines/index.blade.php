<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-wine-600 leading-tight">
            {{ __('Nueva Reserva') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna Izquierda: Formulario de Reserva -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-wine-500">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-wine-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Agendar Servicio
                        </h3>
                        
                        <form action="{{ route('reservations.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Selecciona una Máquina</label>
                                <select name="machine_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-wine-500 focus:ring-wine-500 text-gray-700">
                                    <option value="">-- Elige una máquina disponible --</option>
                                    @foreach ($machines as $machine)
                                        @if($machine->status === 'available')
                                            <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                                <input type="date" name="reservation_date" required min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-wine-500 focus:ring-wine-500 text-gray-700">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Inicio</label>
                                    <input type="time" name="start_time" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-wine-500 focus:ring-wine-500 text-gray-700">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fin</label>
                                    <input type="time" name="end_time" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-wine-500 focus:ring-wine-500 text-gray-700">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cantidad de Prendas (Aprox)</label>
                                <input type="number" name="garments_quantity" required min="1" max="50" placeholder="Ej: 15" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-wine-500 focus:ring-wine-500 text-gray-700">
                            </div>

                            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-wine-600 hover:bg-wine-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wine-500 transition-all duration-200 transform hover:-translate-y-1">
                                Confirmar Reserva
                            </button>
                        </form>
                    </div>
                    
                    @if($errors->any())
                        <div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm" role="alert">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Hubo un problema con tu reserva:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Columna Derecha: Estado de Máquinas -->
                <div class="lg:col-span-2">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Estado de las Máquinas
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($machines as $machine)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center space-x-4 transition-all duration-200 hover:shadow-md hover:-translate-y-1">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full flex items-center justify-center {{ $machine->status === 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($machine->status === 'available')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $machine->name }}</h4>
                                    <p class="text-sm font-medium {{ $machine->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $machine->status === 'available' ? 'Disponible para reserva' : 'Fuera de servicio' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>

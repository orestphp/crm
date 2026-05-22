<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ticket #') }}{{ $ticket->id }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                &larr; Назад до списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex flex-wrap justify-between items-center border-b pb-4 mb-6">
                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span class="ms-2 px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $ticket->status == 'new' ? 'bg-red-100 text-red-800' : ($ticket->status == 'in process' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ strtoupper($ticket->status) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        Created at: <span class="font-medium text-gray-800">{{ $ticket->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Customer:</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <p class="text-sm text-gray-600">Name: <span class="font-medium text-gray-900">{{ $ticket->customer->name ?? 'Unknown' }}</span></p>
                        <p class="text-sm text-gray-600">Email: <span class="font-medium text-gray-900">{{ $ticket->customer->email ?? '-' }}</span></p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-2">Description</h3>
                    <div class="bg-white border rounded-lg p-4 text-gray-800 whitespace-pre-line leading-relaxed shadow-inner">{{ $ticket->text }}</div>
                </div>

                <div class="flex justify-end space-x-3 border-t pt-4">
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                        Back
                    </a>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        Edit
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

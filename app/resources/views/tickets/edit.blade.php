<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Ticket #') }}{{ $ticket->id }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                &larr; Назад до списку
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600">
                        <strong>Customer:</strong> {{ $ticket->customer->name ?? 'Unknown' }} ({{ $ticket->customer->email ?? '-' }})
                    </p>
                </div>

                <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')

                    <div class="mb-6">
                        <label for="text" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="text" id="text" rows="5" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('text') border-red-500 @enderror">{{ old('text', $ticket->text) }}</textarea>
                        @error('text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Ticket Status
                        </label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-500 @enderror">
                            <option value="new" {{ old('status', $ticket->status) == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in process" {{ old('status', $ticket->status) == 'in process' ? 'selected' : '' }}>In Process</option>
                            <option value="processed" {{ old('status', $ticket->status) == 'processed' ? 'selected' : '' }}>Processed</option>
                        </select>
                        @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ trim($message, '"') }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4 border-t pt-4">
                        <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green">
                             Save
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



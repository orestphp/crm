<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Ticket #') }}{{ $ticket->id }}
            </h2>
            <a href="{{ route('tickets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                &larr; Back to List
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

                    @if($ticket->getMedia('attachments')->isEmpty())
                        <p class="text-sm text-gray-500 bg-gray-50 rounded-md border border-dashed border-gray-300 p-4">
                            No files attached to this ticket.
                        </p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($ticket->getMedia('attachments') as $media)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg shadow-sm group hover:border-indigo-300 transition">
                                    <div class="flex items-center space-x-3 overflow-hidden">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-md overflow-hidden flex items-center justify-center border border-gray-200">
                                            @if(str_starts_with($media->mime_type, 'image/'))
                                                <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}" class="w-full h-full object-cover">
                                            @elseif($media->mime_type === 'application/pdf')
                                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>

                                        <div class="overflow-hidden">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $media->file_name }}">
                                                {{ $media->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ number_format($media->size / 1024 / 1024, 2) }} MB
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex-shrink-0 ml-2">
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="inline-flex items-center p-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition" title="View or Download">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

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



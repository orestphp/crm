<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                    <div class="mb-6">
                        <label for="text" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="text" id="text" rows="5" placeholder="Опишіть деталі звернення клієнта..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('text') border-red-500 @enderror">{{ old('text') }}</textarea>
                        @error('text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4 border-t pt-4">
                        <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Скасувати
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

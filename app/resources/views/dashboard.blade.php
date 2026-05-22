<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>
                        Welcome to Laravel Breeze dashboard
                    </p>
                    <br>
                    <p>{{ __("You're logged in!") }}</p>
                    <br>

                    @role('admin')
                        <p>* Admin: admin@admin.com / password</p>
                        <p>* Manager: user@user.com / password (all registered users are managers)</p>
                    @endrole

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

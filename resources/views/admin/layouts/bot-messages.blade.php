<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registered bot messages
        </h2>
    </x-slot>
    @include('admin.bot-message-form')
    @yield('content')
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action=" {{ route('users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                                value="{{ old('username', $user->username) }}" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                value="{{ old('email', $user->email) }}" required autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>


                        <div class="flex items-center justify-end mt-4">

                            <button type="submit"
                                class="inline-block font-bold py-2 px-4 bg-indigo-700 text-white rounded-full shadow-lg hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 transition">
                                Update User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category List') }}
        </h2>
        <br>

        <a href="{{ route('category.create') }}"
            class="inline-block font-bold py-2 px-4 bg-indigo-700 text-white rounded-full shadow-lg hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 transition">
            Add New
        </a>


    </x-slot>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">


                <div class="p-6 text-gray-900">
                    <!-- Flash Message -->
                    @if (session('message'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                    <table class="table-auto border border-black">
                        <thead>
                            <tr>
                                <th class="border border-black px-4 py-2">No</th>
                                <th class="border border-black px-4 py-2">Nama</th>
                                <th class="border border-black px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="border border-black px-4 py-2">{{ $loop->iteration }}</td>
                                    <td class="border border-black px-4 py-2">{{ $category->name }}</td>
                                    <td class="border border-black px-4 py-2">
                                        <div class="hidden md:flex flex-row items-center gap-x-1">
                                            <a href="{{ route('category.edit', $category) }}"
                                                class="inline-block font-bold py-1 px-2 bg-green-700 text-white shadow-lg hover:bg-green-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-block font-bold py-1 px-2 bg-red-700 text-white shadow-lg hover:bg-red-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="border border-black px-4 py-2">Belum ada data terbaru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

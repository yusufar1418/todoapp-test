<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">

            {{ $title_page }}
            @if ($title_page == 'To Do List')
                <h3>
                    Tasks to accomplish today: Don't forget to always maintain a healthy work-life balance!</h3>
            @elseif ($title_page == 'Schedule List')
                <h3>Tasks to complete after today: Let's organize your schedule well, and don't forget to always
                    maintain a good work-life balance.</h3>
            @elseif ($title_page == 'History List')
                <h3>Tasks completed after today:Don't forget to always thank yourself for maintaining a good work-life
                    balance.</h3>
            @elseif ($title_page == 'All Task List')
                <h3>Remember to stay organized and prioritize maintaining a healthy work-life balance.</h3>
            @endif

        </h2>
        <br>

        @if ($title_page == 'To Do List')
            <a href="{{ route('task.create') }}"
                class="inline-block font-bold py-2 px-4 bg-indigo-700 text-white rounded-full shadow-lg hover:bg-indigo-800 focus:ring-4 focus:ring-indigo-300 transition">
                Add New
            </a>
        @endif


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
                                <th class="border border-black px-4 py-2">Title</th>
                                <th class="border border-black px-4 py-2">Category</th>
                                <th class="border border-black px-4 py-2">Date</th>
                                <th class="border border-black px-4 py-2">Status</th>
                                <th class="border border-black px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tasks as $task)
                                @if ($task->status == 0)
                                    <tr>
                                    @elseif ($task->status == 1)
                                    <tr class="bg-green-100">
                                    @elseif ($task->status == 2)
                                    <tr class="bg-red-100">
                                @endif

                                <td class="border border-black px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="border border-black px-4 py-2">{{ $task->title }}</td>
                                <td class="border border-black px-4 py-2">{{ $task->category->name }}</td>
                                <td class="border border-black px-4 py-2">{{ $task->task_at }}</td>
                                @if ($task->status == 0)
                                    <td class="border border-black px-4 py-2">Waiting</td>
                                @elseif ($task->status == 1)
                                    <td class="border border-black px-4 py-2">Complete</td>
                                @elseif ($task->status == 2)
                                    <td class="border border-black px-4 py-2">Cancel</td>
                                @endif

                                <td class="border border-black px-4 py-2">
                                    @if ($task->status == 0)
                                        <div class="hidden md:flex flex-row items-center gap-x-1">
                                            <a href="{{ route('task.edit', $task) }}"
                                                class="inline-block font-bold py-1 px-2 bg-blue-700 text-white shadow-lg hover:bg-blue-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('task.cancel', $task->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this task?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-block font-bold py-1 px-2 bg-yellow-700 text-white shadow-lg hover:bg-yellow-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                    Cancel
                                                </button>
                                            </form>
                                            <form action="{{ route('task.complete', $task->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to complete this task?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-block font-bold py-1 px-2 bg-green-700 text-white shadow-lg hover:bg-green-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                    Complete
                                                </button>
                                            </form>
                                            <form action="{{ route('task.destroy', $task->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-block font-bold py-1 px-2 bg-red-700 text-white shadow-lg hover:bg-red-800 focus:ring-4 focus:ring-indigo-300 transition">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-black px-4 py-2">Belum ada data terbaru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

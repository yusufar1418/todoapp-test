<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = Carbon::now('Asia/Jakarta'); // Mendapatkan tanggal hari ini
        $title_page = "To Do List";
        $userId = Auth::id(); // ID user yang sedang login
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereDate('task_at', $today)
            ->orderByDesc('task_at') // Urutkan berdasarkan tanggal tugas
            ->paginate(10);



        return view('task.index', compact('tasks', 'title_page'));
    }

    public function schedule()
    {
        $today = Carbon::now('Asia/Jakarta'); // Mendapatkan tanggal hari ini
        $title_page = "Schedule List";
        $userId = Auth::id(); // ID user yang sedang login
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereDate('task_at', '>', $today)
            ->orderByDesc('task_at') // Urutkan berdasarkan tanggal tugas
            ->paginate(10);
        return view('task.index', compact('tasks', 'title_page'));
    }

    public function history()
    {
        $today = Carbon::today(); // Mendapatkan tanggal hari ini
        $title_page = "History List";
        $userId = Auth::id(); // ID user yang sedang login
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->whereDate('task_at', '<', $today)
            ->orderByDesc('task_at') // Urutkan berdasarkan tanggal tugas
            ->paginate(10);
        return view('task.index', compact('tasks', 'title_page'));
    }

    public function all()
    {
        $title_page = "All Task List";
        $userId = Auth::id(); // ID user yang sedang login
        $tasks = Task::whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->orderByDesc('task_at') // Urutkan berdasarkan tanggal tugas
            ->paginate(10);
        return view('task.index', compact('tasks', 'title_page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $users = User::all();

        return view('task.create', compact('categories', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required'],
            'task_at' => ['required', 'date'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'], // Pastikan user_ids adalah array dan tiap ID adalah ID pengguna yang valid
        ]);

        $slug = Str::slug($request->title);

        // Cek jika slug sudah ada, jika ada tambahkan angka di akhir
        $originalSlug = $slug;
        $count = 1;
        while (Task::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }


        $task = Task::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'task_at' => $request->task_at,
            'status' => 0,
        ]);

        $userId = Auth::id();

        // Cek apakah ada user_ids yang dipilih
        if ($request->has('user_ids') && !empty($request->user_ids)) {
            // Jika ada user_ids yang dipilih, tambahkan user yang login ke dalam array user_ids
            $userIds = array_merge($request->user_ids, [$userId]);
        } else {
            // Jika tidak ada user_ids yang dipilih, hanya masukkan user yang login
            $userIds = [$userId];
        }


        // Sinkronkan task dengan user_ids
        $task->users()->sync($userIds);



        return redirect()->route('task.index')->with('message', 'Task added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $categories = Category::all();
        $task->task_at = Carbon::parse($task->task_at);
        $users = User::all();
        return view('task.edit', compact('task', 'categories', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required'],
            'task_at' => ['required', 'date'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'], // Pastikan user_ids adalah array dan tiap ID adalah ID pengguna yang valid
        ]);

        // Membuat slug
        if ($request->title != $task->title) {
            $slug = Str::slug($request->title);

            // Cek jika slug sudah ada, jika ada tambahkan angka di akhir
            $originalSlug = $slug;
            $count = 1;
            while (Task::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'task_at' => $request->task_at,
                'slug' => $slug,
            ]);
        } else {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'task_at' => $request->task_at,
            ]);
        }

        $userId = Auth::id();

        // Cek apakah ada user_ids yang dipilih
        if ($request->has('user_ids') && !empty($request->user_ids)) {
            // Jika ada user_ids yang dipilih, tambahkan user yang login ke dalam array user_ids
            $userIds = array_merge($request->user_ids, [$userId]);
        } else {
            // Jika tidak ada user_ids yang dipilih, hanya masukkan user yang login
            $userIds = [$userId];
        }

        // Sinkronkan task dengan user_ids
        $task->users()->sync($userIds);


        return redirect()->route('task.index')->with('message', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('task.index')->with('message', 'Task deleted successfully.');
    }

    public function task_cancel(string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->status != 0) {
            return redirect()->route('task.index')->with('error', 'Only pending tasks can be canceled.');
        }

        $task->update([
            'status' => 2, // Set status menjadi 2 untuk canceled
        ]);

        return redirect()->route('task.index')->with('message', 'Task canceled successfully.');
    }

    public function task_complete(string $id)
    {
        $task = Task::findOrFail($id);

        if ($task->status != 0) {
            return redirect()->route('task.index')->with('error', 'Only pending tasks can be complete.');
        }

        $task->update([
            'status' => 1, // Set status menjadi 2 untuk complete
        ]);

        return redirect()->route('task.index')->with('message', 'Task completed successfully.');
    }
}

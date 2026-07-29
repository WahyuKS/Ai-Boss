<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function complete(Task $task)
    {
        // Pastikan hanya pemilik tugas yang bisa mencentangnya
        if ($task->user_id === Auth::id()) {
            $task->update(['is_completed' => true]);
            return response()->json(['success' => true, 'message' => 'Tugas selesai!']);
        }

        return response()->json(['success' => false], 403);
    }
}

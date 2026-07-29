<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SavedContent;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;

        // 1. Hitung total tugas (Task)
        $totalTasks = Task::where('user_id', $userId)->count();
        $completedTasks = Task::where('user_id', $userId)->where('is_completed', true)->count();
        $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // 2. Hitung total penggunaan AI (semua modul)
        $totalGenerations = SavedContent::where('user_id', $userId)->count();

        // 3. Ambil statistik penggunaan per modul untuk Chart/Grafik
        // Hasilnya jadi array: ['CS Center' => 5, 'Content Studio' => 12, dll]
        $moduleStats = SavedContent::where('user_id', $userId)
            ->select('module_name', DB::raw('count(*) as total'))
            ->groupBy('module_name')
            ->pluck('total', 'module_name')
            ->toArray();

        // Daftar modul standar agar grafik tetap muncul meski datanya 0
        $labels = ['CS Center', 'Content Studio', 'Live Script', 'Playbook', 'Workflow', 'Profit Studio'];
        $data = [];
        foreach ($labels as $label) {
            $data[] = $moduleStats[$label] ?? 0;
        }

        return view('analytics', compact(
            'totalTasks', 'completedTasks', 'taskProgress',
            'totalGenerations', 'labels', 'data'
        ));
    }
}

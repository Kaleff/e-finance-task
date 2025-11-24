<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StatsService
{
    public function getStats(): array
    {
        $stats = DB::selectOne("
            SELECT
                COUNT(DISTINCT p.id) AS total_projects,
                COUNT(t.id) AS total_tasks,
                COUNT(CASE WHEN t.status = 'done' THEN 1 END) AS completed_tasks,
                ROUND(
                    (COUNT(CASE WHEN t.status = 'done' THEN 1 END) * 100.0) /
                    NULLIF(COUNT(t.id), 0),
                    2
                ) AS completion_percentage
            FROM projects p
            LEFT JOIN tasks t ON p.id = t.project_id
            WHERE p.deleted_at IS NULL
        ");

        return [
            'total_projects' => (int) $stats->total_projects,
            'total_tasks' => (int) $stats->total_tasks,
            'completed_tasks' => (int) $stats->completed_tasks,
            'completion_percentage' => (float) ($stats->completion_percentage ?? 0),
        ];
    }
}

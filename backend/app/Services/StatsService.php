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

    public function getProjectOverview(): array
    {
        $results = DB::select("
            WITH project_metrics AS (
                SELECT
                    t.project_id,
                    COUNT(*) AS total_tasks,
                    SUM(CASE WHEN t.status = 'done' THEN 1 ELSE 0 END) AS completed_tasks,
                    COUNT(DISTINCT t.assigned_to) AS team_size
                FROM tasks t
                GROUP BY t.project_id
            )
            SELECT
                p.name AS project_name,
                u.name AS owner_name,
                pm.total_tasks,
                pm.completed_tasks,
                ROUND((pm.completed_tasks * 100.0 / pm.total_tasks), 1) AS completion_rate,
                pm.team_size AS team_members,
                COUNT(DISTINCT tc.id) AS comment_count
            FROM projects p
            INNER JOIN users u ON p.owner_id = u.id
            INNER JOIN project_metrics pm ON p.id = pm.project_id
            LEFT JOIN tasks t ON p.id = t.project_id
            LEFT JOIN task_comments tc ON t.id = tc.task_id
            WHERE p.deleted_at IS NULL
            GROUP BY p.id, p.name, u.name, pm.total_tasks, pm.completed_tasks, pm.team_size
            ORDER BY completion_rate DESC
        ");

        return array_map(function ($project) {
            return [
                'project_name' => $project->project_name,
                'owner_name' => $project->owner_name,
                'total_tasks' => (int) $project->total_tasks,
                'completed_tasks' => (int) $project->completed_tasks,
                'completion_rate' => (float) $project->completion_rate,
                'team_members' => (int) $project->team_members,
                'comment_count' => (int) $project->comment_count,
            ];
        }, $results);
    }
}

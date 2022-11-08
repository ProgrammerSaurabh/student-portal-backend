<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function pieChart()
    {
        $data = collect(DB::select(
            'WITH cte AS (
            SELECT
                IF(AVG(assignments.score) > 3.5,
                1,
                0) AS status_
                FROM
                    assignments
                GROUP BY
                    assignments.user_id
        )
        SELECT
            COUNT(*) AS total,
            (COUNT(
            CASE WHEN status_ = 1 THEN
                1
            ELSE
                NULL
            END) * 100) / COUNT(*) AS passed_per,
            (COUNT(
                    CASE WHEN status_ = 0 THEN
                        1
                    ELSE
                        NULL
                    END) * 100) / COUNT(*) AS failed_per,
            COUNT(
                CASE WHEN status_ = 1 THEN
                    1
                ELSE
                    NULL
                END) AS passed,
            COUNT(
                CASE WHEN status_ = 0 THEN
                    1
                ELSE
                    NULL
                END) AS failed
        FROM
            cte'
        ))
            ->first();

        return response()->json([
            'total' => $data->total ?? 0,
            'passed_per' => round(floatval($data->passed_per), 2) ?? floatval(0),
            'failed_per' => round(floatval($data->failed_per), 2) ?? floatval(0),
            'passed' => $data->passed ?? 0,
            'failed' => $data->failed ?? 0,
        ]);
    }

    public function stackBar()
    {
        $data = collect(DB::select(
            'WITH cte AS (
            SELECT
                IF(AVG(assignments.score) > 3.5,
                1,
                0) AS status_,
                    users. `location`
                FROM
                    assignments
                    INNER JOIN users ON users.id = assignments.user_id
                GROUP BY
                    assignments.user_id
        )
        SELECT
            location,
            COUNT(*) AS total,
            (COUNT(
            CASE WHEN status_ = 1 THEN
                1
            ELSE
                NULL
            END) * 100) / COUNT(*) AS passed_per,
            (COUNT(
                    CASE WHEN status_ = 0 THEN
                        1
                    ELSE
                        NULL
                    END) * 100) / COUNT(*) AS failed_per,
            COUNT(
                CASE WHEN status_ = 1 THEN
                    1
                ELSE
                    NULL
                END) AS passed,
            COUNT(
                CASE WHEN status_ = 0 THEN
                    1
                ELSE
                    NULL
                END) AS failed
        FROM
            cte
        GROUP BY
            `location`'
        ))
            ->map(fn ($d) => [
                'location' => $d->location,
                'total' => $d->total ?? 0,
                'passed_per' => round(floatval($d->passed_per), 2) ?? floatval(0),
                'failed_per' => round(floatval($d->failed_per), 2) ?? floatval(0),
                'passed' => $d->passed ?? 0,
                'failed' => $d->failed ?? 0,
            ])
            ->toArray();

        return response()->json($data);
    }
}

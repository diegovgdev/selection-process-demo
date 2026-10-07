<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Evaluation;
use App\Models\SelectionProcess;
use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\DB;

class DemoResetter
{
    /**
     * Restore the initial fictitious dataset. Everything runs inside a single
     * transaction, so a failure leaves the previous data untouched. Only the
     * demo tables are emptied; nothing outside the app's own models is touched.
     */
    public function reset(): void
    {
        DB::transaction(function () {
            // Children first to respect foreign keys.
            ActivityLog::query()->delete();
            Evaluation::query()->delete();
            Application::query()->delete();
            Candidate::query()->delete();
            SelectionProcess::query()->delete();

            // Restart auto-increment ids so DEMO data is identical after every reset.
            DB::table('sqlite_sequence')->whereIn('name', [
                'activity_logs', 'evaluations', 'applications', 'candidates', 'selection_processes',
            ])->delete();

            app(DemoSeeder::class)->run();
        });
    }
}

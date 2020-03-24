<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables(['users','studies','academic_years',
            'periods','subjects','sections','files','planner_events',
            'schedules','classes','days','tasks']);

        $this->call(UserSeeder::class);
        $this->call(StudySeeder::class);
        $this->call(AcademicYearSeeder::class);
        $this->call(PeriodSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(SectionSeeder::class);
        $this->call(FileSeeder::class);
        $this->call(PlannerEventSeeder::class);
        $this->call(ScheduleSeeder::class);
        $this->call(ClassSeeder::class);
        $this->call(DaySeeder::class);
        $this->call(TaskSeeder::class);

    }

    protected function truncateTables(array $tables){
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach($tables as $table){
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}

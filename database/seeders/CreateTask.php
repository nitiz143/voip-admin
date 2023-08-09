<?php

namespace Database\Seeders;
use App\Models\CronJob;
use Illuminate\Database\Seeder;

class CreateTask extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            [
                'job_title' => 'It will run every minute',
                'job_time' => 'everyMinute',

            ],
            [
                'job_title' => 'It will run every five minute',
                'job_time' => 'hourly',

            ],
            [
                'job_title' => 'It will run daily',
                'job_time' => 'daily',

            ],
            [
                'job_title' => 'It will run every month',
                'job_time' => 'monthly',

            ]
        ];
        foreach ($tasks as $key => $task) {
            CronJob::create($task);
        }
    }
}

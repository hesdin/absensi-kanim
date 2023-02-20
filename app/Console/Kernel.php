<?php

namespace App\Console;

use App\Models\Absen;
use App\Models\User;
use App\Models\Cuti;

use Illuminate\Support\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // $schedule->call(function () {
        //   $hari = Carbon::today();
        //   $users = User::all();

        //     foreach ($users as $u) {
        //       if (!$u->has('absen')->first()) {
        //           $a = new Absen();
        //           $a->user_id = $u->id;
        //           $a->status_masuk = 'tidak hadir';
        //           $a->save();
        //       }
        //     }

        // })->everyMinute();

        $schedule->call(function () {

          $usersHasCuti = User::has('cuti')->get();

          foreach ($usersHasCuti as $u) {
            if ($usersHasCuti) {
              $a = new Absen();
              $a->user_id = $u->id;
              $a->status_masuk = 'cuti';
              $a->save();
            }
          }

        })->daily();

        $schedule->call(function () {
          $hari = Carbon::today();
          $users = User::whereDoesntHave('absen', function ($q) use ($hari) {
            $q->whereDate('created_at', $hari);
          })->get();

          foreach ($users as $u) {
                $a = new Absen();
                $a->user_id = $u->id;
                $a->status_masuk = 'tidak hadir';
                $a->save();
          }

        })->daily();


        // $schedule->call(function () {

        //   $cuti = Cuti::whereDate('date', '=', Carbon::today()->toDateString())->get();

        //   foreach ($cuti as $c) {
        //     $c->delete();          }

        // })->daily();


    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

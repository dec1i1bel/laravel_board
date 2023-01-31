<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:define', function() {
    $this->info('who is admin?...');

    $admin = User::where('id', '=', 1)->get()->first();
    $admName = $admin->name;
    $admEmail = $admin->email;

    $this->comment('user with name <'.$admin->name.'> and email <'.$admin->email.'> is admin');
});

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profile\AvatarController;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //---------------------Original welcome page-----------------------//
    return view('welcome');

    //---------------------Simple SQL queries--------------------------//
    //---------fetch user
    // $users = DB::select("select * from users where email=?", ['grhardy@icloud.com']);
    

    //---------insert user
    // $user = DB::insert('insert into users (name, email, password) values (?, ?, ?)', [
    //     'lisa', 
    //     'lisa251063@gmail.com', 
    //     'endj1963',
    // ]);

    //----------update user
    // $user = DB::update(
    //     "update users set email=? where id=?",[
    //     'lisa2@gmail.com',
    //     2,
    //     ]
    // );

    //--------delete a user
    // $user = DB::delete('delete from users where id=?',[
    //     2,
    // ]);

    // dd($user);

    //--------------------DB access using query builder-------------------//

    //-------Select
    // $users = DB::table("users")->get(); //select all
    // $users = DB::table("users")->where('id',1)->get();

    //--------insert
    // $user = DB::table('users')->insert([
    //     'name' => 'Lisa7',
    //     'email' => 'lisa75@gmail.com',
    //     'password' => 'endj1963',
    // ]);

    //--------update
    // $user = DB::table('users')
    //     ->where('id', 8)
    //     ->update(['email' => 'lisa77@gmail.com']
    // );

    //--------delete
    // $user = DB::table('users')
    //     ->where('id', 8)
    //     ->delete();

    //---------------db access using eloquent-------------------

    //--------select
    // $users = User::where('id',1)->first();
    // $user = User::find(11);

    //--------create
    // $user = User::create([
    //     'name' => 'DaveHardy',
    //     'email' => 'daveH@gmail.com',
    //     'password' => 'abc123',
    // ]);

    //--------update
    // $user = User::where('id', 9)->first();
    // $user->update([
    //     'email' => 'abc@gmail.com',
    // ]);

    //----------or
    // $user = User::find(9);
    // $user->update([
    //     'email' => 'james@gmail.com',
    // ]);

    //---------delete
    // $user = User::find(9);
    // $user->delete();

    // dd($user->name);
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user = User::firstOrCreate(['email' => $user->email], [
        'name'     => $user->name,
        'password' => 'password',
    ]);

    Auth::login($user);
    return redirect('/dashboard');
});

Route::middleware('auth')->group(function(){
    Route::resource('/ticket', TicketController::class);
        // return get('ticket.create', [TicketController::class, 'create'])->name('ticket.create');
        // return post('ticket.create', [TicketController::class, 'store'])->name('ticket.store');

});

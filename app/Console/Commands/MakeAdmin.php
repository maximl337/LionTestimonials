<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Hash;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:make {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user with the email an admin';

    protected $email;



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->email = $this->argument('email');

        $password = $this->secret('What is the Password?');

        if( Hash::check( $password, env('ADMIN_MANAGER_PASSWORD') ) ) {

           
            $user = User::where('email', $this->email)->first();

            $user->is_admin = true;

            $user->save();

            $this->info($user->first_name . ' now has admin access!');    

        } else {
           
            $this->error('Incorrect Password!');
        }

        
    }
}

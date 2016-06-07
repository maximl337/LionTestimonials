<?php

use Illuminate\Database\Seeder;

use App\User;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key 
    	
    	DB::table('users')->truncate();
    	DB::table('support_articles')->truncate();

        $data = [];

        $admin = [
        	'first_name' => 'Angad',
        	'last_name' => 'Dubey',
        	'email'		=> 'angad_dubey@hotmail.com',
        	'password'  => bcrypt('test123'),
            'verified_at' => Carbon::now()
        ];

        array_push($data, $admin);

        $user = [
        	'first_name' => 'Franz',
        	'last_name' => 'Kafka',
        	'email'		=> 'test@test.com',
        	'password'  => bcrypt('test123'),
            'verified_at' => Carbon::now()
        ];

        array_push($data, $user);

        $user2 = [
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'email'     => 'admin@sellwithreviews.com',
            'password'  => bcrypt('test123'),
            'verified_at' => Carbon::now()
        ];

        array_push($data, $user2);

        DB::table('users')->insert($data); 


        $u = User::where('email', 'angad_dubey@hotmail.com')->first();

        for($i=0; $i <15; $i++) {
        	$u->support_articles()->save(factory('App\SupportArticle')->make());
        }
        
        $u->is_admin = true;

        $u->save();

        $u2 = User::where('email', 'admin@sellwithreviews.com')->first();

        $u2->is_admin = true;

        $u2->save();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key 



    }
}

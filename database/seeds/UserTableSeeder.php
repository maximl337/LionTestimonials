<?php

use Illuminate\Database\Seeder;

use App\User;

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
        	'password'  => bcrypt('test123')
        ];

        array_push($data, $admin);

        $user = [
        	'first_name' => 'Franz',
        	'last_name' => 'Kafka',
        	'email'		=> 'test@test.com',
        	'password'  => bcrypt('test123')
        ];

        array_push($data, $user);

        DB::table('users')->insert($data); 



        $u = User::where('email', 'angad_dubey@hotmail.com')->first();

        for($i=0; $i <15; $i++) {
        	$u->support_articles()->save(factory('App\SupportArticle')->make());
        }
        
        $u->is_admin = true;

        $u->save();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key 



    }
}

<?php

use Illuminate\Database\Seeder;

use App\User;
use App\SupportArticle;
use Faker\Factory as Faker;

class SupportArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$faker = Faker::create();

        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key 
    	
    	//  DB::table('support_articles')->truncate();

        $u = User::where('email', 'angad_dubey@hotmail.com')->first();

        for ($i=0; $i < 15; $i++) { 
        	$u->support_articles()->save(new SupportArticle([
        			'title' => $faker->sentence,
        			'body' => $faker->paragraphs($nb = 3)
        		]));
        }
        

     	//	DB::table('support_articles')->insert(); 


        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key 
    }
}

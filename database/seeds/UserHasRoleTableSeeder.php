<?php

use Illuminate\Database\Seeder;
use App\Models\UserHasRole;

class UserHasRoleTableSeeder extends Seeder
{
	public function run(Faker\Generator $faker)
	{
		$csvPath = database_path() . DIRECTORY_SEPARATOR . 'seeds' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR . 'userHasRoles.csv';
        $items = csv_to_array($csvPath);

        foreach ($items as $key => $item)
        {
            UserHasRole::create([
                                
            ]);
        }
	}
}
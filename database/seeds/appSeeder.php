<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class appSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("apps")->insert([
            "id_app"=> 1,
            "app_name"=> "Coordinate Conversion",
            "is_private"=> 0,
            "description"=> "Converting coordinate systems",
            "documentation"=> "Not available",
            "image"=> "img/coordinate.png",
            "page"=> "trans-index",
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
        ] );

        DB::table("apps")->insert([
            "id_app"=> 2,
            "app_name"=> "GNSS Processing",
            "is_private"=> 1,
            "description"=> "Calculating rinnex data from GNSS measurements",
            "documentation"=> "Not available",
            "image"=> "img/gnss.png",
            "page"=> "gnss-index",
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
        ] );

        DB::table("apps")->insert([
            "id_app"=> 3,
            "app_name"=> "Geoid Calculator",
            "is_private"=> 0,
            "description"=> "Retrieving geoid values of Indonesian territory",
            "documentation"=> "Not available",
            "image"=> "img/geoid.jpg",
            "page"=> "geoid-index",
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
        ] );

        DB::table("apps")->insert([
            "id_app"=> 4,
            "app_name"=> "Adjustment Computation",
            "is_private"=> 1,
            "description"=> "Adjustment computation for land surveying",
            "documentation"=> "Not available",
            "image"=> "img/adj.png",
            "page"=> "adjustment-index",
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
        ] );

        DB::table("apps")->insert([
            "id_app"=> 5,
            "app_name"=> "Datum Transformation",
            "is_private"=> 1,
            "description"=> "Transforming datums using local data from local measurements.",
            "documentation"=> "Not available",
            "image"=> "img/datum-trans.png",
            "page"=> "datum-trans-index",
            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
        ] );
    }
}

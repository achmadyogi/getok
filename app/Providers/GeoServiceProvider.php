<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Engines\GeoClass;

class GeoServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('GeoClass', function(){
			return new GeoClass;
		});
	}
}

?>
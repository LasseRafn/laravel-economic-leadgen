<?php namespace LasseRafn\EconomicLeadgen;

use Illuminate\Support\ServiceProvider;

class EconomicLeadgenServiceProvider extends ServiceProvider
{
	/**
	 * Boot
	 */
	public function boot()
	{
		$configPath = __DIR__ . '/config/economic-leadgen.php';
		$this->mergeConfigFrom($configPath, 'economic-leadgen');

		$configPath = __DIR__ . '/config/economic-leadgen.php';

		if (function_exists('config_path')) {
			$publishPath = config_path('economic-leadgen.php');
		} else {
			$publishPath = base_path('config/economic-leadgen.php');
		}

		$this->publishes([$configPath => $publishPath], 'config');
	}
}
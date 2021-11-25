<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Blade::directive('money', function ($expression) {
			eval("\$params = [$expression];");
			if (count($params) == 1) {
				$amount = $params[0];
				$currency = "Rs.";
			} else {
				list($amount, $currency) = $params;
			}

			$amount = "round((float)($amount), \Config::get('constants.client_settings.decimal_places'))";

			if (strlen($currency)) {
				$amount = "\"${currency}\"" . " . ${amount}";
			}

            $amount = "preg_replace('/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i', '$1,', $amount)";

			return "<?php echo $amount; ?>";
        });

		Blade::directive('toPKR', function($expression) {
			eval("\$params = [$expression];");
			if (count($params) == 1) {
				$amount = $params[0];
				$exchangeRate = 1;
				$currency = "Rs.";
			} elseif (count($params) == 2) {
				list($amount, $exchangeRate) = $params;
				$currency = "Rs.";
			} else {
				list($amount, $exchangeRate, $currency) = $params;
			}

			$amount = "round((float)((new \App\Services\CurrencyService())->setExchangeRate(${exchangeRate})->setPrice(${amount})->convertToPKR()), \Config::get('constants.client_settings.decimal_places'))";

			if (strlen($currency)) {
				$amount = "\"${currency}\"" . " . ${amount}";
			}
			$amount = "preg_replace('/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i', '$1,', $amount)";
			return "<?php echo $amount; ?>";
		});

		Blade::directive('fromPKR', function($expression) {
			eval("\$params = [$expression];");
			if (count($params) == 1) {
				$amount = $params[0];
				$exchangeRate = 1;
				$currency = "";
			} elseif (count($params) == 2) {
				list($amount, $exchangeRate) = $params;
				$currency = "";
			} else {
				list($amount, $exchangeRate, $currency) = $params;
			}

			$amount = "round((float)((new \App\Services\CurrencyService())->setExchangeRate(${exchangeRate})->setPrice(${amount})->convertFromPKR()), \Config::get('constants.client_settings.decimal_places'))";

			if (strlen($currency)) {
				$amount = "\"${currency}\"" . " . ${amount}";
			}
			return "<?php echo $amount; ?>";
		});
    }
}

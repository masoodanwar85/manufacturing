<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Models\Setting;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SettingService {

	public static function getSettingQuery($settingTypeID, $foreignID) {
		$rawSQL = "
			SELECT setting.settingName,setting.settingCode,IFNULL(settingValue.settingValue,setting.defaultValue) AS settingValue
			FROM setting
			LEFT JOIN settingValue ON settingValue.settingID = setting.settingID AND settingValue.settingTypeID = ${settingTypeID} AND settingValue.foreignID = ${foreignID}
			WHERE setting.settingTypeID = ${settingTypeID}
		";
		return DB::select($rawSQL);
	}

	public static function getFormattedSettings($settings) {
		$arySettings = [];
		foreach ($settings as $setting) {
			$arySettings[$setting->settingCode] = $setting->settingValue;
		}
		return $arySettings;
	}

	public static function getUserSettings() {
		$userSettings = self::getSettingQuery(2,Auth::id());
		return self::getFormattedSettings($userSettings);
	}

	public static function getClientSettings() {
		$clientSettings = self::getSettingQuery(1,Auth::user()->clientID);
		return self::getFormattedSettings($clientSettings);
	}

	public static function initializeSettings() {
		$client_settings = Cache::remember('client_settings', 60, function() {
			return self::getClientSettings();
		});

		$user_settings = Cache::remember('user_settings', 60, function() {
			return self::getUserSettings();
		});

        config()->set('constants.client_settings', $client_settings);
		config()->set('constants.user_settings', $user_settings);
		View::share('globalSettings', Arr::dot(\Config::get('constants')));
	}
}

<?php

namespace App\Services;

class CurrencyService {

	private $exchangeRate;
	private $unitPrice;
	private $quantity;

	public function __construct() {
		$this->quantity = 1;
		$this->exchangeRate = 1;
	}

	public function setExchangeRate($exchangeRate) {
		$this->exchangeRate = $exchangeRate;
		return $this;
	}

	public function setPrice($price) {
		$this->unitPrice = $price;
		return $this;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;
		return $this;
	}

	public function convertToPKR() {
		return self::getAmountFormatted($this->simpleConvertToPKR());
	}

	public function simpleConvertToPKR() {
		return ($this->unitPrice * $this->quantity) / $this->exchangeRate;
	}

	public function convertFromPKR() {
		return self::getAmountFormatted($this->simpleConvertFromPKR());
	}

	public function simpleConvertFromPKR() {
		return $this->unitPrice * $this->quantity * $this->exchangeRate;
	}

	public static function strQueryFormulaToPKRConversion($tableAlias = 'purchaseOrderDetail') {
		return "${tableAlias}.quantityUnits * ${tableAlias}.perUnitPrice " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " ${tableAlias}.exchangeRate";
	}

	public static function strJSConvertToPKR() {
		return "
			function convertToPKR(exchangeRate, unitPrice, quantity = 1) {
				return (parseFloat(unitPrice) * parseInt(quantity) " . \Config::get('constants.client_settings.operatorToConvertToPKR') . " parseFloat(exchangeRate)).toFixed(".\Config::get('constants.client_settings.decimal_places').")
			}
		";
	}

	public static function getAmountFormatted($amount) {
		return round((float)($amount), \Config::get('constants.client_settings.decimal_places'));
	}

	public static function getCurrencyFormatted($amount, $currency = "Rs. ") {
		return $currency . self::getAmountFormatted($amount);
	}

	public function getMoneyTranslationInUrdu($amount) {
		$aryDigits = [
			"digit_0" => "صفر",
			"digit_1" => "ایک",
			"digit_2" => "دو",
			"digit_3" => "تین",
			"digit_4" => "چار",
			"digit_5" => "پانچ",
			"digit_6" => "چھ",
			"digit_7" => "سات",
			"digit_8" => "آٹھ",
			"digit_9" => "نو",
			"digit_10" => "دس",
			"digit_11" => "گیارہ",
			"digit_12" => "بارہ",
			"digit_13" => "تیرہ",
			"digit_14" => "چودہ",
			"digit_15" => "پندرہ",
			"digit_16" => "سولہ",
			"digit_17" => "سترہ",
			"digit_18" => "اٹھارہ",
			"digit_19" => "انیس",
			"digit_20" => "بیس",
			"digit_21" => "اکیس",
			"digit_22" => "بائیس",
			"digit_23" => "تئیس",
			"digit_24" => "چوبیس",
			"digit_25" => "پچیس",
			"digit_26" => "چھببیس",
			"digit_27" => "ستائیس",
			"digit_28" => "اٹھائیس",
			"digit_29" => "انتیس",
			"digit_30" => "تیس",
			"digit_31" => "اکتیس",
			"digit_32" => "بتیس",
			"digit_33" => "تینتیس",
			"digit_34" => "چوتیس",
			"digit_35" => "پینتیس",
			"digit_36" => "چھتیس",
			"digit_37" => "سنتیس",
			"digit_38" => "اڑتیس",
			"digit_39" => "انتالیس",
			"digit_40" => "چالیس",
			"digit_41" => "اکتالیس",
			"digit_42" => "بیالیس",
			"digit_43" => "تینتالیس / ترالیس",
			"digit_44" => "چوالیس",
			"digit_45" => "پینتالیس",
			"digit_46" => "چھیالیس",
			"digit_47" => "سینتالیس",
			"digit_48" => "  اڑتالیس",
			"digit_49" => "انچاس",
			"digit_50" => "پچاس",
			"digit_51" => "اکاون",
			"digit_52" => "باون",
			"digit_53" => "تریپن",
			"digit_54" => "چون",
			"digit_55" => "پچپن",
			"digit_56" => "چھپن",
			"digit_57" => "ستاون",
			"digit_58" => "اٹھاون",
			"digit_59" => "انسٹھ",
			"digit_60" => "ساٹھ",
			"digit_61" => "اکسٹھ",
			"digit_62" => "باسٹھ",
			"digit_63" => "تریسٹھ",
			"digit_64" => "چونسٹھ",
			"digit_65" => "پینسٹھ",
			"digit_66" => "چھیاسٹھ",
			"digit_67" => "سڑسٹھ",
			"digit_68" => "اڑسٹھ",
			"digit_69" => "انهتر",
			"digit_70" => "ستر",
			"digit_71" => "اکھتر",
			"digit_72" => "بہتر",
			"digit_73" => "تہتر",
			"digit_74" => "چوہتر",
			"digit_75" => "پچھتر",
			"digit_76" => "چھہتر",
			"digit_77" => "ستتر",
			"digit_78" => "اٹھتر",
			"digit_79" => "اناسی",
			"digit_80" => "اسی",
			"digit_81" => "اکاسی",
			"digit_82" => "بیاسی",
			"digit_83" => "تراسی",
			"digit_84" => "چوراسی",
			"digit_85" => "پچھاسی",
			"digit_86" => "چھیاسی",
			"digit_87" => "ستاسی",
			"digit_88" => "اٹھاسی",
			"digit_89" => "نواسی",
			"digit_90" => "نوے",
			"digit_91" => "اکانوے",
			"digit_92" => "بانوے",
			"digit_93" => "ترانوے",
			"digit_94" => "چورانوے",
			"digit_95" => "پچانوے",
			"digit_96" => "چھیانوے",
			"digit_97" => "ستانوے",
			"digit_98" => "اٹھانوے",
			"digit_99" => "ننانوے",
			"digit_100" => "سو",
			"digit_1000" => "ہزار",
			"digit_10000" => "دس ہزار",
			"digit_100000" => "لاکھ",
			"digit_1000000" => "دس لاکھ",
			"digit_10000000" => "کروڑ",
			"digit_100000000" => "دس کروڑ",
			"digit_1000000000" => "ارب",
			"digit_10000000000" => "دس ارب",
			"digit_100000000000" => "کھرب",
			"digit_1000000000000" => " دس کھرب"
		];


		$aryRangeIndex = [
			'length_3'  => [ 'range' => 100, 'index' => 1],
			'length_4'  => [ 'range' => 1000, 'index' => 1],
			'length_5'  => [ 'range' => 1000, 'index' => 2],
			'length_6'  => [ 'range' => 100000, 'index' => 1],
			'length_7'  => [ 'range' => 100000, 'index' => 2],
			'length_8'  => [ 'range' => 10000000, 'index' => 1],
			'length_9'  => [ 'range' => 10000000, 'index' => 2],
			'length_10' => [ 'range' => 1000000000, 'index' => 1],
			'length_11' => [ 'range' => 1000000000, 'index' => 2],
			'length_12' => [ 'range' => 100000000000, 'index' => 1],
			'length_13' => [ 'range' => 100000000000, 'index' => 2]
		];

		$number_to_convert = self::getAmountFormatted(abs($amount));
		// $converted_number = "";
		while ($number_to_convert != 0) {
			$length = strlen($number_to_convert);
			$constant_name = 'digit_' . $number_to_convert;
			if (array_key_exists($constant_name,$aryDigits)) {
				// $converted_number = $aryDigits[$constant_name];
				echo $aryDigits[$constant_name];
				break;
			} else {
				$aryCurrentRangeIndex = $aryRangeIndex['length_' . $length];
				$range = $aryCurrentRangeIndex['range'];
				$idxPoint = $aryCurrentRangeIndex['index'];
				$first_number = substr($number_to_convert,0,$idxPoint);
				$remaining_numbers = substr($number_to_convert,$idxPoint);

				// $converted_number .= $aryDigits['digit_' . $first_number] . ' ' . $aryDigits['digit_' . $range] . ' ';
				echo $aryDigits['digit_' . $first_number] . ' ' . $aryDigits['digit_' . $range] . ' ';

				$number_to_convert = (int) $remaining_numbers;
			}
		}
		// return $converted_number;
	}
}

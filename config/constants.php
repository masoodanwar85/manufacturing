<?php

    return [
        'account_heads' => [
            'assets' => 1,
            'liabilities' => 2,
            'capital' => 3,
            'revenue' => 4,
            'expense' => 5,
            'supplier' => 6,
			'staff' => 7,
			'customer' => 8,
			'godown' => 9,
			'transport' => 10,
			'cash' => 11,
			'petty_cash' => 12,
			'accounts_receivable' => 13,
			'bank_accounts' => 14,
			'accounts_payable' => 15,
			'salaries_payable' => 16,
			'owner_capital' => 17,
			'sales' => 18,
			'purchases' => 19,
			'salaries' => 20,
			'utility_bills' => 21,
			'rent' => 22,
			'sales_return_good' => 23,
			'office_supplies' => 24,
			'sales_return_bad' => 25,
			'purchase_commission' => 26,
			'salaries_advance' => 27,
			'cash_advance' => 28,
			'bank_charges' => 29,
			'conveyance_expense' => 30,
			'electricity_expense' => 31,
			'godown_rent' => 32,
			'office_equipment' => 33,
			'office_maintenace_repair' => 34,
			'miscellaneous_expense' => 35,
			'daily_expense' => 36,
			'iran_expense' => 37,
			'purchase_expense' => 38,
			'transport_expense' => 39,
			'loan_to_staff' => 40,
			'sales_tax' => 41,
			'custom_tax' => 42,
			'misc_purchase_expense' => 43,
			'income_tax' => 44,
			'excise_tax' => 45,
			'sales_commission' => 46,
            'customer_receivable' => 47,
			'godown_receivable' => 48,
			'transport_receivable' => 49,
			'staff_receivable' => 50,
			'supplier_receivable' => 51,
			'customer_payable' => 52,
			'staff_payable' => 53,
			'supplier_payable' => 54,
			'godown_payable' => 55,
			'transport_payable' => 56,
			'service_revenue' => 57,
			'service_expense' => 58
        ],
		'stock_status' => [
			'quetta_godown' => 1,
			'good_sales_return' => 2,
			'sold' => 3,
			'bad_sales_return' => 4,
			'damaged' => 5,
            'manufacturing' => 6,
			'isAvailableForSale' => '1,2',
			'aryIsAvailableForSale' => [1,2],
			'aryIsNotAvailableForSale' => [3,4,5,6],
			'isNotAvailableForSale' => '3,4,5,6',
			'isIncludeCustomers' => '2,3,4'
		],
        'production_stages' => [
			'draft' => 0,
			'in_process' => 1,
			'finished' => 2,
            'default_factory_id' => 2
		],
        'bank_instrument' => [
            'type' => [
                'cheque' => 1
            ],
            'status' => [
                'processing' => 1,
                'cleared' => 2,
                'bounced' => 3,
                'dishonoured' => 4
            ],
			'bad_status' => [3,4]
        ],
		'client_settings' => [
            'monthlySalaryDays' => 30
        ],
		'user_settings' => []
    ];

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ClientSeeder::class,
			FieldTypeSeeder::class,
			AccessLevelSeeder::class,
            ModulesSeeder::class,
            RolesSeeder::class,
            PrivilegeSeeder::class,
            RolePrivilegeSeeder::class,
            UserTypeSeeder::class,
            UserSeeder::class,
            UserRoleSeeder::class,
			SettingTypeSeeder::class,
			SettingSeeder::class,
			MeasurementUnitSeeder::class,
			MeasurementUnitConversionSeeder::class,
			TransactionTypeSeeder::class,
			BankSeeder::class,
			StaffTypeSeeder::class,
			AccountHeadSeeder::class,
			StockStatusSeeder::class,
			BankInstrumentTypeSeeder::class,
			BankInstrumentStatusSeeder::class,
            ProductTypeSeeder::class,
			CategorySeeder::class,
			ProductSeeder::class,
			BatchSeeder::class,
			GodownSeeder::class,
			TransportSeeder::class,
			SupplierSeeder::class,
			CustomerSeeder::class,
			// PurchaseOrderSeeder::class,
			StaffSeeder::class,
			BankAccountSeeder::class,
            LeaveTypeSeeder::class,
        ]);

		// \App\Models\Category::factory(5)->create();
		// \App\Models\Product::factory(10)->create();
		//\App\Models\Supplier::factory(10)->create();
    }
}

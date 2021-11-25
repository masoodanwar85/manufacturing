<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aryFieldTypes = [
            ['fieldTypeCode' => 'radio', 'fieldType' => 'Radio', 'sortOrder' => 1],
			['fieldTypeCode' => 'checkbox', 'fieldType' => 'Checkbox', 'sortOrder' => 2],
			['fieldTypeCode' => 'number', 'fieldType' => 'Number', 'sortOrder' => 3],
            ['fieldTypeCode' => 'text', 'fieldType' => 'Text', 'sortOrder' => 4],
			['fieldTypeCode' => 'textarea', 'fieldType' => 'Text Area', 'sortOrder' => 5],
			['fieldTypeCode' => 'select', 'fieldType' => 'Dropdown', 'sortOrder' => 6],
			['fieldTypeCode' => 'wysiwyg', 'fieldType' => 'WYSIWYG Editor', 'sortOrder' => 7]
        ];
        foreach ($aryFieldTypes as $fieldType) {
            \Illuminate\Support\Facades\DB::table('fieldType')->insert([
				'fieldTypeCode' => $fieldType['fieldTypeCode'],
				'fieldType' => $fieldType['fieldType'],
				'sortOrder' => $fieldType['sortOrder']
			]);
        }
    }
}

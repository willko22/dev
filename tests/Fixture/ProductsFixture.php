<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductsFixture
 */
class ProductsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'product_name' => '2e45299e-426c-4c6d-9aba-370ea6981771',
                'image_path' => 'Lorem ipsum dolor sit amet',
                'price' => 1,
                'dph' => 1,
                'category_ids' => 1,
            ],
        ];
        parent::init();
    }
}

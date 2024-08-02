<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity
 *
 * @property string $product_name
 * @property string|null $image_path
 * @property float|null $price
 * @property float|null $dph
//  * @property int|null $category_ids
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        // 'id' => true,
        'product_name' => true,
        'image_name' => true,
        'price' => true,
        'dph' => true,
    ];
    
}

<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */

// Check if the sort parameter is present in the URL
    if (!isset($_GET['sort'])) {
            
        // alphabeticaly sorted by category_name
        function compareByName($a, $b) {
            return strcmp($a->product_name, $b->product_name);
        }

        // Sort the array
        $products = iterator_to_array($products);
        usort($products, 'compareByName');
    }
?>

<style>
   .cut-off-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
   } 

</style>

<div class="products index content">
    <?= $this->Html->link(__('New Product'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Products') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('product_name', __('Product Name')) ?></th>
                    <th><?= $this->Paginator->sort('price', __('Price')) ?></th>
                    <th><?= $this->Paginator->sort('dph', __('Tax')) ?></th>
                    <th><?= $this->Paginator->sort('category_ids', __('Categories')) ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="cut-off-text" style="max-width: 400px;"><?= h($product->product_name) ?></td>
                    <!-- <td><?= h($product->image_name) ?></td> -->
                    <td><?= $product->price === null ? '' : $this->Number->format($product->price) ?></td>
                    <td><?= $product->dph === null ? '' : $this->Number->format($product->dph) ?></td>
                    <td style="max-width: 300px;">
                        <?php 
                            // debug($productsCategories);
                            if (!empty($productsCategories)) {
                                $category_names = "";
                                foreach ($productsCategories as $productCategory) {
                                    if ($productCategory->product_id == $product->id) {
                                        $category_names .= ", " . $categories[$productCategory->category_id];
                                    }
                                }
         
                                // remove the first comma
                                $category_names = substr($category_names, 2); 
                                $displayNames = $category_names;

                            } else {
                                $displayNames = "None";
                            }
                            echo $displayNames;

                        ?>
                    </td>
                    <td class="actions" >
                        <?= $this->Html->link(__('View'), ['action' => 'view', $product->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->product_name)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>

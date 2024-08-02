<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\ProductsCategory> $productsCategories
 */

  // Check if the sort parameter is present in the URL
    if (!isset($_GET['sort'])) {
    
        // sort by id
        function compareById($a, $b) {
            return $a->id - $b->id;
        }

        // // Sort the array
        $productsCategories = iterator_to_array($productsCategories);
        usort($productsCategories, 'compareById');
    
    }
    
?>
<div class="productsCategories index content">
    <?= $this->Html->link(__('New Products Category'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Products Categories') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <!-- <th><?= $this->Paginator->sort('id') ?></th> -->
                    <th><?= $this->Paginator->sort('product_id') ?></th>
                    <th><?= $this->Paginator->sort('category_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <!-- <?php debug($productsCategories); ?> -->
                <?php foreach ($productsCategories as $productsCategory): ?>
                <tr>
                    <!-- <td><?= $this->Number->format($productsCategory->id) ?></td> -->
                    <td><?= $productsCategory->hasValue('product') ? $this->Html->link($productsCategory->product->product_name, ['controller' => 'Products', 'action' => 'view', $productsCategory->product->id]) : '' ?></td>
                    <td><?= $productsCategory->hasValue('category') ? $productsCategory->category->category_name : '' ?></td>
                    <td class="actions">
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $productsCategory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $productsCategory->id)]) ?>
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

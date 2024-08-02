<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ProductsCategory $productsCategory
 * @var \Cake\Collection\CollectionInterface|string[] $products
 * @var \Cake\Collection\CollectionInterface|string[] $categories
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Products Categories'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="productsCategories form content">
            <?= $this->Form->create($productsCategory) ?>
            <fieldset>
                <legend><?= __('Add Products Category') ?></legend>
                <?php
                    echo $this->Form->control('product_id', ['options' => $products]);
                    echo $this->Form->control('category_id', ['options' => $categories]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

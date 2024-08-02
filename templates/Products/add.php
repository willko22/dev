<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
//  * @var iterable<\App\Model\Entity\Category> $categories
 */
?>


<div class="products form content">

    <?= $this->Form->create(null, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Add Product') ?></legend>
        <?php
            echo $this->Form->control('product_name', ['required' => true]);
            // choose file to upload
            echo $this->Form->control('image_name', ['type' => 'file', 'accept' => 'image/*']);
            echo $this->Form->control('price', ['type' => 'number']);
            echo $this->Form->control('dph', ['type' => 'number', 'label' => 'DPH']);   
            // echo $this->Form->control('category_ids');
            // category ids is a list of checkable categories
            if (!empty($categories)) {
                echo $this->Form->control('category_ids', [
                    'type' => 'select',
                    'multiple' => 'checkbox',
                    'options' => $categories,
                    'label' => 'Category'
                ]);
            }

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>


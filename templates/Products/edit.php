<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $product->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $product->product_name), 'class' => 'side-nav-item']
            ) ?>
        </div>
    </aside>
    <!-- placeholder="current data" -->
    <div class="column column-80">
        <div class="products form content">
            <?= $this->Form->create(null, ['type' => 'file']) ?>
            <fieldset>
                <legend><?= __('Edit: ' . $product->product_name ) ?></legend>
                <?php
                    echo $this->Form->control('product_name', ['placeholder' => $product->product_name, 'label' => __('Product Name')]);
                    echo $this->Form->control('image_name', ['type' => 'file', 'accept' => 'image/*', 'placeholder' => $product->image_name, 'label' => __('Image')]);
                    echo $this->Form->control('price', ['type' => 'number' ,'placeholder' => $product->price, 'label' => __('Price')]);
                    echo $this->Form->control('dph', ['type' => 'number', 'label' => 'DPH', 'placeholder' => $product->dph, 'label' => __('Tax')]);

                    if (!empty($productsCategories)) {
                        
                        $currentCategories = [];
                        foreach ($productsCategories as $productCategory) {
                            if ($productCategory->product_id == $product->id) {
                                $currentCategories[] = $productCategory->category_id;
                            }
                        } 
                    } else {
                        $currentCategories = [];
                    }

                    echo $this->Form->control('category_ids', [
                        'type' => 'select',
                        'multiple' => 'checkbox',
                        'options' => $categories,
                        'label' => __('Categories'),
                        'value' => $currentCategories
                    ]);
                    
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
            
        </div>
    </div>
</div>
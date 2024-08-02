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
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $product->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $product->id], ['confirm' => __('Are you sure you want to delete # {0}?', $product->product_name), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Product'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="products view content">
            <div class="row">
                <div class="column">
                    <h3><?= h($product->product_name) ?></h3>
                    <table>
                        <tr>
                            <th><?= __('Product Name') ?></th>
                            <td><?= h($product->product_name) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Price') ?></th>
                            <td><?= $product->price === null ? '' : $this->Number->format($product->price) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Dph') ?></th>
                            <td><?= $product->dph === null ? '' : $this->Number->format($product->dph) ?></td>
                        </tr>
                        <tr>
                            <th><?= __('Categories') ?></th>
                            <td>
                            <?php 

                                

                                if (!empty($productsCategories)) {

                                    //for each category id in the product, find the category name and display it
                                    $category_names = "";
                                    foreach ($productsCategories as $productCategory) {
                                        // for each category id find the category name
                                        // debug($productCategory);
                                        if ($productCategory->product_id == $product->id && array_key_exists($productCategory->category_id, $categories))
                                            $category_names .= htmlspecialchars($categories[$productCategory->category_id], ENT_QUOTES, 'UTF-8') . "<br>";
                                        
                                    }

                                    $category_names = $category_names;
                                    
                                } else {
                                    $category_names = "";
                                }
                                echo $category_names;
                                // echo $this->Text->format($category_names);
                            ?>
        
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="column" style="display: flex; justify-content: center; align-items: center;">
                    <?php 

                        if (!empty($product->image_name) && file_exists(WWW_ROOT . 'img/products/' . $product->image_name)) {
                            echo $this->Html->image('../webroot/img/products/' . $product->image_name, ['alt' => $product->product_name, 'style' => 'max-height: ' . 300 + (count(explode(",", $product->category_ids)) -1) * 24 . 'px; max-width: auto;']);
                        } else {
                            echo "No image";
                        }
                    ?>
                </div>
            </div>  
        </div>
    </div>
</div>

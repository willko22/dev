<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Category $category
 */
?>

<div class="column column-80">
    <div class="categories form content">
        <?= $this->Form->create($category) ?>
        <fieldset>
            <legend><?= __('Add Category') ?></legend>
            <?php
                echo $this->Form->control('category_name');
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>


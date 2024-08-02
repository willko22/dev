<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Model\Table\ProductsCategoriesTable;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 * @property \App\Model\Table\ProductsCategoriesTable $ProductsCategories
 * 
 */

class CategoriesController extends AppController
{
    /**
     * @var ProductsCategoriesTable
     */
    public $ProductsCategories; // Update the property type

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Categories->find();
        $categories = $this->paginate($query);

        $this->set(compact('categories'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id == null || !$this->Categories->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid category id. Cannot edit category.'));
            return $this->redirect(['action' => 'index']);
        }

        $category = $this->Categories->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $this->set(compact('category'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($id == null || !$this->Categories->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid category id. Cannot delete category.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);


        $this->ProductsCategories = TableRegistry::getTableLocator()->get('ProductsCategories');

        // delete all bonds with products
        $productsCategories = $this->ProductsCategories->find('all', [
            'conditions' => [
                'category_id' => $category->id
            ]
        ])->toArray();

        foreach ($productsCategories as $productsCategory) {
            $this->ProductsCategories->delete($productsCategory);
        }

        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

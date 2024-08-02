<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\ProductsCategoriesTable;

/**
 * ProductsCategories Controller
 *
 * @property \App\Model\Table\ProductsCategoriesTable $ProductsCategories
 * 
 */
class ProductsCategoriesController extends AppController
{
    /**
     * @var ProductsCategoriesTable
     */
    public $ProductsCategories;

    public function initialize(): void
    {
        parent::initialize();
        $this->ProductsCategories = $this->getTableLocator()->get('ProductsCategories');
        
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $query = $this->ProductsCategories->find()
            ->contain(['Products', 'Categories']);
        $productsCategories = $this->paginate($query);

        $this->set(compact('productsCategories'));
    }


    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productsCategory = $this->ProductsCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $productsCategory = $this->ProductsCategories->patchEntity($productsCategory, $this->request->getData());
            if ($this->ProductsCategories->save($productsCategory)) {
                $this->Flash->success(__('The products category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The products category could not be saved. Please, try again.'));
        }
        $products = $this->ProductsCategories->Products->find('list', limit: 200)->all();
        $categories = $this->ProductsCategories->Categories->find('list', limit: 200)->all();
        $this->set(compact('productsCategory', 'products', 'categories'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Products Category id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productsCategory = $this->ProductsCategories->get($id);
        if ($this->ProductsCategories->delete($productsCategory)) {
            $this->Flash->success(__('The products category has been deleted.'));
        } else {
            $this->Flash->error(__('The products category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

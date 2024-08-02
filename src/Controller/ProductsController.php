<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use App\Model\Table\CategoriesTable;
use App\Model\Table\ProductsCategoriesTable;
 
/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\CategoriesTable $Categories
 * @property \App\Model\Table\ProductsCategoriesTable $ProductsCategories
 */

class ProductsController extends AppController
{
    /**
     * @var CategoriesTable
     */
    public $Categories; // Update the property type

    /**
     * @var ProductsCategoriesTable
     */
    public $ProductsCategories; // Update the property type

    public function initialize(): void
    {
        parent::initialize();
        $this->Categories = TableRegistry::getTableLocator()->get('Categories');;
        $categories = $this->Categories->find('list', [
            'keyField' => 'id',
            'valueField' => 'category_name'
        ])->toArray();
        $this->set(compact('categories'));

        $this->ProductsCategories = TableRegistry::getTableLocator()->get('ProductsCategories');
        $productsCategories = $this->ProductsCategories->find('all', [
            'keyField' => 'id'
        ])->toArray();
        $this->set(compact('productsCategories'));

    }



    // private function saveFile(\Laminas\Diactoros\UploadedFile $file) : ?string
    // {
    //         // Check if the upload was successful
    //         if ($file->getError() === UPLOAD_ERR_OK) {
    //             // Process the uploaded file, e.g., move it to a target directory
    //             // debug($data['image_name']->getClientFilename());

    //             $imageName = $file->getClientFilename();
    //             $targetPath = '../webroot/img/products/' . $imageName ;
    //             $file->moveTo($targetPath);
    
    //             // Update $data['image_name'] to the path where the file was saved
    //             return $imageName;
    //         } else {
    //             // Set image_name to null if the upload was not successful
    //             return  null;
    //         }

    // }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {

        $query = $this->Products->find();
        $products = $this->paginate($query);

        $this->set(compact('products'));
    }

    
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $product = $this->Products->newEmptyEntity();
        if ($this->request->is('post')) {

            $data = $this->request->getData();
            // if $data['category_id'] is array, convert it to string
            $fileType = $data["image_name"]->getClientMediaType();
            $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($fileType, $validTypes) || empty($data["image_name"])) {
                if (is_array($data['category_ids']))
                    $data['category_ids'] = implode(',', $data['category_ids']);

                $data['image_name'] = $data['image_name']->getClientFilename();
                

                $product = $this->Products->patchEntity($product, $data);
                // debug($product->getErrors());

                if ($this->Products->save($product)) {
                    $this->Flash->success(__('The product has been saved.'));

                    return $this->redirect(['action' => 'index']);
                } else
                    $this->Flash->error(__('The product could not be saved. Please, try again.'));
            
            } else
                $this->Flash->error(__('Please upload file having extensions .jpeg/.jpg/.png only.'));
            
        }
        $this->set(compact('product'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id === null || !$this->Products->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid product ID. Cannot edit product.'));
            return $this->redirect(['action' => 'index']);
        }
        
        $product = $this->Products->get($id, contain: []);
    
        if ($this->request->is(['patch', 'post', 'put'])) {
            $newData = $this->request->getData();
   

            ////////////////////// Categories //////////////////////
            $value = $newData['category_ids'];
            // load all categories for this product
            $productsCategories = $this->ProductsCategories->find('all', [
                'conditions' => [
                    'product_id' => $product->id
                ]
            ])->toArray();

            // get all category ids for this product
            $thisProductCategories = [];
            foreach ($productsCategories as $productsCategory) {
                $thisProductCategories[] = $productsCategory->category_id;
            }

            $value = $value ?: [];
            // check if current categories and new categories are the same
            if (count($thisProductCategories) !== count($value)){
                
                // Find categories that are in $thisProductCategories but not in $value
                $missingCategories = array_diff($thisProductCategories, $value);

                // Find categories that are in $value but not in $thisProductCategories
                $additionalCategories = array_diff($value, $thisProductCategories);

                if (count($missingCategories) !== 0 ){
                    // check if current categories are in the new categories if not delete their bond with the product
                    foreach ($thisProductCategories as $category_id) {

                        if (in_array($category_id, $value))
                            continue;
                        $this->ProductsCategories->deleteAll([
                            'product_id' => $product->id,
                            'category_id' => $category_id
                        ]);
                    }
                }

                if (count($additionalCategories) !== 0){
                    // check if new categories are in the current categories if not add them to the product
                    foreach ($value as $category_id) {
                        if (in_array($category_id, $thisProductCategories))
                            continue;
                        $newProductsCategories = $this->ProductsCategories->newEmptyEntity();
                        $newProductsCategories->product_id = $product->id;
                        $newProductsCategories->category_id = $category_id;
                        $this->ProductsCategories->save($newProductsCategories);
                    }
                }   
            }
            ////////////////////////////////////////////

            foreach ($newData as $key => $value) {
                if ($key == 'category_ids')
                    continue;

                $product[$key] = $value == null || $value == '' ? $product[$key] : $value; 
            }
            
            // debug($newData);
            // $product = $this->Products->patchEntity($product, $newData);
            // debug($product);
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['controller' => 'products', 'action' => 'index']);
            } else if (isset($product->getErrors()['image_name'])) {

                foreach ($product->getErrors()['image_name'] as $key => $value) {
                    $this->Flash->error($value);
                }
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
            
        }
        $this->set(compact('product'));
    }
    

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if ($id === null || !$this->Products->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid product ID. Cannot delete product.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);

        $product = $this->Products->get($id);

        if ($product["image_name"] != null){
            $imageUsedInProducts = $this->Products->find('all', [
                'conditions' => ['image_name' => $product["image_name"]]
            ])->toArray();

            // if imageUsedInProducts has lenght of 1 and its 0th element is the current product then delete the file
            if (count($imageUsedInProducts) == 1 && $imageUsedInProducts[0]->id == $product["id"] && file_exists("../webroot/img/products" . $product["image_name"]) )
                unlink("../webroot/img/products" . $product["image_name"]);
        }

        // delete all bonds with categories
        $productsCategories = $this->ProductsCategories->find('all', [
            'conditions' => [
                'product_id' => $product->id
            ]
        ])->toArray();

        foreach ($productsCategories as $productsCategory) {
            $this->ProductsCategories->delete($productsCategory);
        }
        
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function view(int $id = null) : ?\Cake\Http\Response
    {
        if ($id === null || !$this->Products->exists(['id' => $id])) {
            $this->Flash->error(__('Invalid product ID. Cannot view product.'));
            return $this->redirect(['action' => 'index']);
        }

        $product = $this->Products->get($id);

        $this->set(compact('product'));
        return null;
    }
}

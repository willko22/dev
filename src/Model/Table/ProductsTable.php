<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Utility\FileUtility;
use Laminas\Diactoros\UploadedFile;
use Cake\Event\EventInterface;
use ArrayObject;
use App\Model\Entity\Product;


/**
 * Products Model
 *
 * @method \App\Model\Entity\Product newEmptyEntity()
 * @method \App\Model\Entity\Product newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Product> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Product findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Product> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Product saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Product>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Product> deleteManyOrFail(iterable $entities, array $options = [])
 * 
 * 
 */
class ProductsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('products');
        $this->setDisplayField('product_name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Categories', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'category_id',
            'joinTable' => 'products_categories',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('image_name')
            ->allowEmptyString('image_name')
            ->maxLength('image_name', 255);

        $validator
            ->numeric('price')
            ->allowEmptyString('price');

        $validator
            ->numeric('dph')
            ->allowEmptyString('dph');

        return $validator;
    }

    public function validateMimeType(UploadedFile $file) : bool
    {
        // debug($value->getClientFilename());
        if (empty($file->getClientFilename())) {
            return true;
            // debug($file->getClientFilename());
        }

        if ($file instanceof \Laminas\Diactoros\UploadedFile 
            && $file->getError() === UPLOAD_ERR_OK 
            ) 
        {
            return in_array($file->getClientMediaType(), ['image/png', 'image/jpeg', 'image/jpg']);
        }

        return false;
    }

    /**
     * Before save callback.
     *
     * @param \Cake\Event\EventInterface<\App\Model\Entity\Product> $event The beforeSave event.
     * @param \App\Model\Entity\Product $entity The entity being saved.
     * @param \ArrayObject<string, mixed> $options The options passed to the save method.
     *
     */
    public function beforeSave(EventInterface $event, Product $entity, ArrayObject $options) : bool
    {
        // debug($event);
        // debug($entity->getOriginal('image_name'));
        // debug($entity->image_name);

        $oldImage = $entity->getOriginal('image_name');
        $newImage = $entity['image_name'];

        if ($oldImage !== $newImage) {

            // check if newImage is png, jpg, or jpeg
            if ($this->validateMimeType($newImage)) {
                
                // check if old image was used by other products and delete it if not
                
                if ($this->find()->where(['image_name' => $oldImage])->count() === 1) {
                    // $this->deleteFile($oldImage);

                    FileUtility::deleteFile(WWW_ROOT . 'img/products/' . $oldImage);
                    // unlike('../webroot/img/products/' . $oldImage);
                }


                if ($newImage instanceof \Laminas\Diactoros\UploadedFile 
                    && $newImage->getError() === UPLOAD_ERR_OK 
                    && !empty($newImage->getClientFilename())
                    ) 
                {
                    // $entity->image_name = FileUtility::saveFile($newImage, '../webroot/img/products/');
                    $entity->set('image_name', FileUtility::saveFile($newImage, WWW_ROOT . 'img/products/'));
                } else {
                    $entity->set('image_name', $oldImage);
                    // ->image_name = $oldImage;
                }

                // $entity->patchEntity($entity, ['image_name' => $entity->image_name]); 
            } else {
                // raise error
                $entity->setError('image_name', ['Please upload file having extensions .jpeg/.jpg/.png only.']);
                return false;
            }

        }
        return true;
    }
}

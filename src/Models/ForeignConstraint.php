<?php

namespace AnimusCoop\CrudGenerator\Models;

use AnimusCoop\CrudGenerator\Support\Arr;
use AnimusCoop\CrudGenerator\Support\Contracts\JsonWriter;
use AnimusCoop\CrudGenerator\Support\Str;
use AnimusCoop\CrudGenerator\Traits\CommonCommand;
use AnimusCoop\CrudGenerator\Traits\ModelTrait;

class ForeignConstraint implements JsonWriter
{
    use CommonCommand, ModelTrait;

    /**
     * The name of the foreign field/column.
     *
     * @var string
     */
    public $column;

    /**
     * The name of the column being referenced on the foreign model.
     *
     * @var string
     */
    public $references;

    /**
     * The name of the foreign model being referenced.
     *
     * @var string
     */
    public $on;

    /**
     * The action to take when the model is updated.
     *
     * @var string
     */
    public $onUpdate;

    /**
     * The action to take when the model is deleted.
     *
     * @var string
     */
    public $onDelete;

    /**
     * The model this key references
     *
     * @var string
     */
    protected $referencesModel;

    /**
     * If the model references itself.
     *
     * @var string
     */
    protected $isSelfReference;

    /**
     * Creates a new field instance.
     *
     * @param string $column
     * @param string $references
     * @param string $on
     * @param string $onDelete
     * @param string $onUpdate
     * @param string $model
     *
     * @return void
     */
    public function __construct($column, $references, $on, $onDelete = null, $onUpdate = null, $model = null, $isSelfReference = false)
    {
        $this->column = $column;
        $this->references = $references;
        $this->on = $on;
        $this->onUpdate = $onUpdate;
        $this->onDelete = $onDelete;
        $this->referencesModel = $model;
        $this->isSelfReference = $isSelfReference;
    }

    /**
     * Get the model that is being referenced.
     *
     * @return string
     */
    public function getReferencesModel()
    {
        if (empty($this->referencesModel)) {
            $this->referencesModel = self::getModelNamespace($this->getForeignModelName(), null);
        }

        return $this->referencesModel;
    }

    /**
     * Checks if this constraint reference itself as parent-child relation
     *
     * @return bool
     */
    public function isSelfReference()
    {
        return $this->isSelfReference;
    }

    /**
     * Get a foreign relation.
     *
     * @return AnimusCoop\CrudGenerator\Models\ForeignRelatioship
     */
    public function getForeignRelation()
    {
        $params = [
            $this->getReferencesModel(),
            $this->column,
            $this->on,
        ];

        $prefix = $this->isSelfReference() ? 'parent_' : '';

        $relation = new ForeignRelationship(
            'belongsTo',
            $params,
            $this->getForeignModelName($prefix)
        );

        return $relation;
    }

    /**
     * Get the name of the foreign model.
     *
     * @param string $prefix
     *
     * @return string
     */
    protected function getForeignModelName($prefix = '')
    {
        return ucfirst(Str::camel($prefix . Str::singular($this->references)));
    }

    /**
     * Checks if the constraint has a delete action.
     *
     * @return bool
     */
    public function hasDeleteAction()
    {
        return !empty($this->onDelete);
    }

    /**
     * Checks if the constraint has an update action.
     *
     * @return bool
     */
    public function hasUpdateAction()
    {
        return !empty($this->onUpdate);
    }

    /**
     * Gets the constrain in an array format.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'field' => $this->column,
            'references' => $this->references,
            'on' => $this->on,
            'on-delete' => $this->onDelete,
            'on-update' => $this->onUpdate,
            'references-model' => $this->getReferencesModel(),
            'is-self-reference' => $this->isSelfReference(),
        ];
    }

    /**
     * Get the foreign constraints
     *
     * @param array $properties
     * @param string $fieldName
     *
     * @return null || AnimusCoop\CrudGenerator\Models\ForeignConstraint
     */
    public static function fromArray(array $constraint, $fieldName)
    {
        $onUpdate = Arr::isKeyExists($constraint, 'on-update') ? $constraint['on-update'] : null;
        $onDelete = Arr::isKeyExists($constraint, 'on-delete') ? $constraint['on-delete'] : null;
        $model = Arr::isKeyExists($constraint, 'references-model') ? $constraint['references-model'] :
        self::guessModelFullName($fieldName, self::getModelsPath());
        $isSelfReference = Arr::isKeyExists($constraint, 'is-self-reference') ? (bool) $constraint['is-self-reference'] : false;

        return new self(
            $constraint['field'],
            $constraint['references'],
            $constraint['on'],
            $onDelete,
            $onUpdate,
            $model,
            $isSelfReference
        );
    }
}

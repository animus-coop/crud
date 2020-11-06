<?php

namespace AnimusCoop\CrudGenerator\Models;

use AnimusCoop\CrudGenerator\Models\Field;

class FieldMapper
{
    /**
     * The field to optimize
     *
     * @var array of AnimusCoop\CrudGenerator\Models\Field
     */
    public $field;

    /**
     * Create a new optimizer instance.
     *
     * @var array
     */
    public $meta;

    /**
     * Creates a new field instance.
     *
     * @param AnimusCoop\CrudGenerator\Models\Field $field
     * @param array $meta
     *
     * @return void
     */
    public function __construct(Field $field, array $meta = [])
    {
        $this->field = $field;
        $this->meta = $meta;
    }
}

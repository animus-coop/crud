<?php

namespace AnimusCoop\CrudGenerator\Tests;

use AnimusCoop\CrudGenerator\Tests\TestCase;
use AnimusCoop\CrudGenerator\Models\ForeignRelationship;

class ForeignRelationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
	 /** @test */
    public function testAbilityToCreateRelationForSingleField()
    {
		$relation = ForeignRelationship::fromString("name:fooModel;is-nullable:true;data-type:varchar;type:hasMany;params:App\\Models\\Asset|category_id|id");

		// TO DO, asset that the relation is created successfully!
        $this->assertTrue($relation instanceof ForeignRelationship);
    }

    public function testAbilityToCreateRelationForSingleFieldNotNullable()
    {
        $relation = ForeignRelationship::fromString("name:fooModel;data-type:varchar;type:hasMany;params:App\\Models\\Asset|category_id|id");

        // TO DO, asset that the relation is created successfully!
        $this->assertTrue($relation instanceof ForeignRelationship);
    }
}

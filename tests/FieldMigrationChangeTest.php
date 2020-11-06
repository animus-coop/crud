<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/03/19
 * Time: 1:28 AM
 */

namespace Animus\CodeGenerator\Tests;

use Animus\CodeGenerator\Models\Field;
use Animus\CodeGenerator\Models\FieldMigrationChange;
use Animus\CodeGenerator\Models\ForeignConstraint;

class FieldMigrationChangeTest extends TestCase
{
    public function testDetectFieldChangeWhenForeignConstraintAdded()
    {
        $fromField = new Field('transfer_id', 'migration');

        $constraint = new ForeignConstraint('transfer_id', 'id', 'transfers');
        $toField = new Field('transfer_id', 'migration');
        $toField->setForeignConstraint($constraint);

        $change = new FieldMigrationChange();
        $change->fromField = $fromField;
        $change->toField = $toField;

        $this->assertTrue($change->hasChange());
    }

    public function testDetectFieldChangeWhenForeignConstraintRemoved()
    {
        $constraint = new ForeignConstraint('transfer_id', 'id', 'transfers');
        $fromField = new Field('transfer_id', 'migration');
        $fromField->setForeignConstraint($constraint);

        $toField = new Field('transfer_id', 'migration');

        $change = new FieldMigrationChange();
        $change->fromField = $fromField;
        $change->toField = $toField;

        $this->assertTrue($change->hasChange());
    }
}

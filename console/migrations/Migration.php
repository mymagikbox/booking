<?php
namespace console\migrations;

use Yii;

abstract class Migration extends \yii\db\Migration
{
    protected abstract function getTable(): string;

    protected function tableExists($tableName): bool
    {
        return in_array($tableName, Yii::$app->db->schema->tableNames);
    }
}
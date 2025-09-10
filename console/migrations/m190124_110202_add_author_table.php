<?php

use yii\db\Migration;

class m190124_110202_add_author_table extends Migration
{
    protected function getTable(): string
    {
        return '{{%author}}';
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}

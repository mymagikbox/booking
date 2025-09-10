<?php

use yii\db\Migration;

class m190124_110204_create_book_author_assign extends Migration
{
    protected function getTable(): string
    {
        return '{{%book_author_assign}}';
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk_book_author_assign', $this->getTable(), ['book_id', 'author_id']);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}

<?php

use common\CQS\Domain\Entity\BookAuthorAssign;
use console\migrations\Migration;

class m190124_110204_create_book_author_assign extends Migration
{
    protected function getTable(): string
    {
        return BookAuthorAssign::tableName();
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

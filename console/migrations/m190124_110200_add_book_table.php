<?php

use common\CQS\Domain\Entity\Book;
use console\migrations\Migration;

class m190124_110200_add_book_table extends Migration
{
    protected function getTable(): string
    {
        return Book::tableName();
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'cover_image' => $this->string(255),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_book_slug', $this->getTable(), 'slug', true);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}

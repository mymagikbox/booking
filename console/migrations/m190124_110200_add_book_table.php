<?php

use \yii\db\Migration;

class m190124_110200_add_book_table extends Migration
{
    protected function getTable(): string
    {
        return '{{%book}}';
    }

    public function up(): void
    {
        $this->createTable($this->getTable(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull()->unique(),
            'cover_image' => $this->string(255),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function down(): void
    {
        $this->dropTable($this->getTable());
    }
}

<?php

namespace common\CQS\Domain\Entity;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/***
 * @property int $book_id
 * @property int $author_id
 *
 * @property Author $author
 * @property Book $book
 */
class BookAuthorAssign extends ActiveRecord
{
    public static function primaryKey(): array
    {
        return ['book_id', 'author_id'];
    }

    public static function tableName(): string
    {
        return '{{%book_author_assign}}';
    }

    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    public static function create(int $authorId, int $bookId): static
    {
        return new static([
            'book_id' => $bookId,
            'author_id' => $authorId,
        ]);
    }
}
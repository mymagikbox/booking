<?php

namespace common\CQS\Domain\Entity;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/***
 * @property int $book_id
 * @property int $author_id
 *
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
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
        return '{{%author}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
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
            'author_id' => $authorId,
            'book_id' => $bookId,
        ]);
    }
}
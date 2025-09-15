<?php

namespace common\CQS\Domain\Entity;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/***
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $year
 * @property string $description
 * @property string $isbn Международный стандартный книжный номер
 * @property string $cover_image
 *
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property BookAuthorAssign[] $bookAuthorAssign
 * @property Author[] $authors
 */
class Book extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%book}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
        ];
    }

    public function getBookAuthorAssign()
    {
        return $this->hasMany(BookAuthorAssign::class, ['book_id' => 'id']);
    }

    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->via('bookAuthorAssign');
    }
}
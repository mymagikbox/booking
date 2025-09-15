<?php
declare(strict_types=1);

namespace common\CQS\Application\Book\Query\ListBook;

use common\core\model\BaseModel;

class ListBookQuery extends BaseModel
{
    // filter param
    public string $title;
    public int $year;
    public string $isbn;

    public function rules(): array
    {
        return [
            [['title', 'isbn'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 10],
            [['year',], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'title' => 'название',
            'year' => 'год выпуска',
            'isbn' => 'isbn',
        ];
    }
}
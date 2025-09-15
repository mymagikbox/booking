<?php
declare(strict_types=1);

namespace common\CQS\Application\Report\Query\TopAuthors;

use common\core\model\BaseModel;

class TopAuthorsQuery extends BaseModel
{
    public ?int $year = null;
    public int $limit = 10;

    public function rules(): array
    {
        return [
            [['year'], 'required'],
            [['year', 'limit'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'year' => 'год выпуска',
            'limit' => 'Лимит выборки',
        ];
    }

    public function formName(): string
    {
        return 's';
    }
}
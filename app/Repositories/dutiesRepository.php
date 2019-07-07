<?php

namespace App\Repositories;

use App\Models\Duty;
use App\Repositories\BaseRepository;

/**
 * Class dutiesRepository
 * @package App\Repositories
 * @version July 7, 2019, 3:04 am UTC
*/

class dutiesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'org_id',
        'name'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Duty::class;
    }
}

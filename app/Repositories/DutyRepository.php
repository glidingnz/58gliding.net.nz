<?php

namespace App\Repositories;

use App\Models\Duty;
use App\Repositories\BaseRepository;

/**
 * Class DutyRepository
 * @package App\Repositories
 * @version July 7, 2019, 4:09 am UTC
*/

class DutyRepository extends BaseRepository
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

<?php

namespace App\Repositories;

use App\Models\Roster;
use App\Repositories\BaseRepository;

/**
 * Class RosterRepository
 * @package App\Repositories
 * @version July 7, 2019, 9:23 am UTC
*/

class RosterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'org_id',
        'day_id',
        'day_date',
        'dayrole_id',
        'member_id',
        'duty_name',
        'helper_name',
        'helper_mobile'
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
        return Roster::class;
    }
}

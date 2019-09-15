<?php

namespace App\Grids;

use Closure;
use Leantony\Grid\Grid;
use Leantony\Grid\Buttons\GenericButton;

use Gate;

class EntriesGrid extends Grid implements EntriesGridInterface
{
    /**
    * The name of the grid
    *
    * @var string
    */
    protected $name = 'Entries';

    /**
    * List of buttons to be generated on the grid
    *
    * @var array
    */
    protected $buttonsToGenerate = [
        //'create',
        'view',
        'delete',
        'refresh',
        'export'
    ];

    /**
    * Specify if the rows on the table should be clicked to navigate to the record
    *
    * @var bool
    */
    protected $linkableRows = false;

    /**
    * Set the columns to be displayed.
    *
    * @return void
    * @throws \Exception if an error occurs during parsing of the data
    */
    public function setColumns()
    {
        $this->columns = [
            'first_name' => [
                'label' => 'First Name',
                'filter' => [
                    'enabled' => true,
                    'operator' => 'like'
                ],
                'search' => ['enabled' => true],
                'styles' => [
                    'column' => 'grid-w-6'
                ],
                'search' => ['enabled' => true],
                'renderIf'=> function () {return true;}
            ],
            'last_name' => [
                'label' => 'Last Name',
                'filter' => [
                    'enabled' => true,
                    'operator' => 'like'
                ],
                'search' => ['enabled' => true],
            ],
            'is_copilot'=>['label' => 'Is Copilot','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'mobile'=>['label' => 'Mobile','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'email'=>['label' => 'email','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'address_1'=>['label' => 'Address 1','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'address_2'=>['label' => 'Address 2','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'address_3'=>['label' => 'Address 3','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'club'=>['label' => 'Club','export'=>true,'renderIf' => function () {return null!==request('export');}],

            'e_contact'=>['label' => 'Emgcy Contact','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_mobile'=>['label' => 'Emgcy Mobile','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_phone'=>['label' => 'Emgcy Phone','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_email'=>['label' => 'Emgcy email','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_address_1'=>['label' => 'Emgcy Address 1','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_address_2'=>['label' => 'Emgcy Address 2','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_address_3'=>['label' => 'Emgcy Address 3','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'e_relationship'=>['label' => 'Relationship','export'=>true,'renderIf' => function () {return null!==request('export');}],

            'glider' => [
                'label' => 'Glider',
                'filter' => [
                    'enabled' => true,
                    'operator' => 'like'
                ],
                'search' => ['enabled' => true],
            ],
            'type' => [
                'label' => 'Type',
                'filter' => [
                    'enabled' => true,
                    'operator' => 'like'
                ],
                'search' => ['enabled' => true],
            ],

            'wingspan'=>['label' => 'Wingspan','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'handicap'=>['label' => 'Handicap','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'winglets'=>['label' => 'Winglets','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'has_tracker'=>['label' => 'Has Tracker','export'=>true,'renderIf' => function () {return null!==request('export');}],

            'contest_id' => [
                'label' => 'Contest',
                'search' => ['enabled' => true],
                'presenter' => function ($columnData, $columnName) {
                    return $columnData->contest->name;
                },
                'filter' => [
                    'enabled' => true,
                    'type' => 'select',
                    'data' => \App\Models\Contest::query()->pluck('name', 'id')
                ],
            ],

            'classes_id' => [
                'label' => 'Class',
                'search' => ['enabled' => true],
                'presenter' => function ($columnData, $columnName) {
                    return $columnData->contestClass->name;
                },
                'filter' => [
                    'enabled' => true,
                    'type' => 'select',
                    'data' => \App\Models\ContestClass::query()->pluck('name', 'id')
                ],
            ],

            'crew_name'=>['label' => 'Crew Name','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'c_phone'=>['label' => 'Crew Phone','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'car_type'=>['label' => 'Car Type','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'car_color'=>['label' => 'Car Colour','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'car_plate'=>['label' => 'Car Plate','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'trailer_type'=>['label' => 'Trailer Type','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'trailer_color'=>['label' => 'Trailer Colour','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'trailer_plate'=>['label' => 'Trailer Plate','export'=>true,'renderIf' => function () {return null!==request('export');}],
            'crew_notes'=>['label' => 'Crew Notes','export'=>true,'renderIf' => function () {return null!==request('export');}],

            'declaration'=>['label' => 'Declaration','export'=>true,'renderIf' => function () {return null!==request('export');}],



        ];
    }

    /**
    * Set the links/routes. This are referenced using named routes, for the sake of simplicity
    *
    * @return void
    */
    public function setRoutes()
    {
        // searching, sorting and filtering
        $this->setIndexRouteName('contestentries.index');

        // crud support
        $this->setCreateRouteName('contestentries.create');
        $this->setViewRouteName('contestentries.show');
        $this->setDeleteRouteName('contestentries.destroy');

        // default route parameter
        $this->setDefaultRouteParameter('id');
    }

    /**
    * Return a closure that is executed per row, to render a link that will be clicked on to execute an action
    *
    * @return Closure
    */
    public function getLinkableCallback(): Closure
    {
        return function ($gridName, $item) {
            return route($this->getViewRouteName(), [$gridName => $item->id]);
        };
    }

    /**
    * Configure rendered buttons, or add your own
    *
    * @return void
    */
    public function configureButtons()
    {
        // call `addRowButton` to add a row button
        // call `addToolbarButton` to add a toolbar button
        // call `makeCustomButton` to do either of the above, but passing in the button properties as an array

        // call `editToolbarButton` to edit a toolbar button
        // call `editRowButton` to edit a row button
        // call `editButtonProperties` to do either of the above. All the edit functions accept the properties as an array

        //$this->editRowButton('delete', ['position' => 99,'renderIf'=> function() {return Gate::allows('contest-admin');} ]);

    }

    /**
    * Returns a closure that will be executed to apply a class for each row on the grid
    * The closure takes two arguments - `name` of grid, and `item` being iterated upon
    *
    * @return Closure
    */
    public function getRowCssStyle(): Closure
    {
        return function ($gridName, $item) {
            // e.g, to add a success class to specific table rows;
            // return $item->id % 2 === 0 ? 'table-success' : '';
            return '';
        };
    }
}
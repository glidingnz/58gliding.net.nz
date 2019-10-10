<?php

namespace App\Grids;

use Closure;
use Leantony\Grid\Grid;
use Leantony\Grid\Buttons\GenericButton;

use Gate;

class ContestClassesGrid extends Grid implements ContestClassesGridInterface
{
    /**
    * The name of the grid
    *
    * @var string
    */
    protected $name = 'Classes';

    /**
    * List of buttons to be generated on the grid
    *
    * @var array
    */
    protected $buttonsToGenerate = [
        'create',
        'view',
        'delete',
        'refresh',
        //'export'
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
            "name" => [
                "label" => "Name",
                "filter" => [
                    "enabled" => true,
                    "operator" => "like"
                ],
                "search" => ["enabled" => true],
                "styles" => [
                    "column" => "grid-w-6"
                ]
            ],
            "description" => [
                "label" => "Description",
                "filter" => [
                    "enabled" => true,
                    "operator" => "like"
                ],
                "search" => ["enabled" => true],
            ],
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
        $this->setIndexRouteName('contestclasses.index');

        // crud support
        $this->setCreateRouteName('contestclasses.create');
        $this->setViewRouteName('contestclasses.show');
        $this->setDeleteRouteName('contestclasses.destroy');

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

        $this->editToolbarButton('create', ['renderIf'=> function() {return Gate::allows('contest-admin');} ]);
        $this->editRowButton('view', ['class' => 'btn btn-primary btn-sm grid-row-button',  'url' => function ($gridName, $gridItem) {return route('contestclasses.show',$gridItem->id);}]);
        $this->editRowButton('delete', ['position' => 99,'renderIf'=> function() {return Gate::allows('contest-admin');} ]);

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
            return "";
        };
    }
}
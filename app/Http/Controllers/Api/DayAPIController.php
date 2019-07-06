<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDayAPIRequest;
use App\Http\Requests\API\UpdateDayAPIRequest;
use App\Models\Day;
use App\Repositories\DayRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DayController
 * @package App\Http\Controllers\API
 */

class DayAPIController extends AppBaseController
{
    /** @var  DayRepository */
    private $dayRepository;

    public function __construct(DayRepository $dayRepo)
    {
        $this->dayRepository = $dayRepo;
    }

    /**
     * Display a listing of the Day.
     * GET|HEAD /days
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $days = $this->dayRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($days->toArray(), 'Days retrieved successfully');
    }

    /**
     * Store a newly created Day in storage.
     * POST /days
     *
     * @param CreateDayAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDayAPIRequest $request)
    {
        $input = $request->all();

        $day = $this->dayRepository->create($input);

        return $this->sendResponse($day->toArray(), 'Day saved successfully');
    }

    /**
     * Display the specified Day.
     * GET|HEAD /days/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Day $day */
        $day = $this->dayRepository->find($id);

        if (empty($day)) {
            return $this->sendError('Day not found');
        }

        return $this->sendResponse($day->toArray(), 'Day retrieved successfully');
    }

    /**
     * Update the specified Day in storage.
     * PUT/PATCH /days/{id}
     *
     * @param int $id
     * @param UpdateDayAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDayAPIRequest $request)
    {
        $input = $request->all();

        /** @var Day $day */
        $day = $this->dayRepository->find($id);

        if (empty($day)) {
            return $this->sendError('Day not found');
        }

        $day = $this->dayRepository->update($input, $id);

        return $this->sendResponse($day->toArray(), 'Day updated successfully');
    }

    /**
     * Remove the specified Day from storage.
     * DELETE /days/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Day $day */
        $day = $this->dayRepository->find($id);

        if (empty($day)) {
            return $this->sendError('Day not found');
        }

        $day->delete();

        return $this->sendResponse($id, 'Day deleted successfully');
    }
}

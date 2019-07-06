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
		parent::__construct();
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
		$query = Day::query();

		// limit by organisation
		if ($request->has('org_id')) $query->where('org_id','=',$request->input('org_id'));

		// only get active days unless specified
		if (!$request->has('show_inactive')) $query->where('active','<>','false');

		if ($results = $query->paginate($request->input('per-page', 50)))
		{
			return $this->success($results, TRUE);
		}
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
		if (!$request->has('org_id')) return $this->sendError('org_id is required');
		if (!$request->has('day_date')) return $this->sendError('day_date is required');

		// check if day already exists. If so activate, rather than create
		if ($existingDay = Day::where('org_id','=',$request->input('org_id'))->where('day_date','=',$request->input('day_date'))->first())
		{
			$existingDay->active = true;
			$existingDay->deleted_at = null;
			$existingDay->save();

			return $this->sendResponse($existingDay->toArray(), 'Day existing and activated successfully');
		}
		else
		{
			$day = $this->dayRepository->create($input);

			return $this->sendResponse($day->toArray(), 'Day added successfully');
		}
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


	/**
	 * Deactivate a day.
	 * POST /days/deactivate
	 *
	 * @param string $day
	 *
	 * @throws \Exception
	 *
	 * @return Response
	 */
	public function deactivate(Request $request)
	{
		if (!$request->has('org_id')) return $this->sendError('org_id is required');
		if (!$request->has('day_date')) return $this->sendError('day_date is required');

		/** @var Day $day */
		if ($day = Day::where('org_id','=',$request->input('org_id'))->where('day_date','=',$request->input('day_date'))->first())
		{
			$day->active=false;
			$day->save();
			return $this->sendResponse($day, 'Day deactivated successfully');
		}

		if (empty($day)) {
			return $this->sendError('Day not found');
		}

	}
}

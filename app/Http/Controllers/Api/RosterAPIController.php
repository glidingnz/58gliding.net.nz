<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRosterAPIRequest;
use App\Http\Requests\API\UpdateRosterAPIRequest;
use App\Models\Roster;
use App\Repositories\RosterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RosterController
 * @package App\Http\Controllers\API
 */

class RosterAPIController extends AppBaseController
{
	/** @var  RosterRepository */
	private $rosterRepository;

	public function __construct(RosterRepository $rosterRepo)
	{
		$this->rosterRepository = $rosterRepo;
		parent::__construct();
	}

	/**
	 * Display a listing of the Roster.
	 * GET|HEAD /roster
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$query = Roster::query();

		// limit by organisation
		if ($request->has('org_id')) $query->where('org_id','=',$request->input('org_id'));

		// start and end dates
		if ($request->has('start_date')) $query->where('day_date', '>=', $request->input('start_date'));
		if ($request->has('end_date')) $query->where('day_date', '<=', $request->input('end_date'));

		if ($results = $query->get())
		{
			return $this->success($results);
		}
		
		return $this->sendError('Duties not found');
	}



	/**
	 * Store a newly created Roster in storage.
	 * POST /roster
	 *
	 * @param CreateRosterAPIRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateRosterAPIRequest $request)
	{
		$input = $request->all();

		$roster = $this->rosterRepository->create($input);

		return $this->sendResponse($roster->toArray(), 'Roster saved successfully');
	}

	/**
	 * Display the specified Roster.
	 * GET|HEAD /roster/{id}
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		/** @var Roster $roster */
		$roster = $this->rosterRepository->find($id);

		if (empty($roster)) {
			return $this->sendError('Roster not found');
		}

		return $this->sendResponse($roster->toArray(), 'Roster retrieved successfully');
	}

	/**
	 * Update the specified Roster in storage.
	 * PUT/PATCH /roster/{id}
	 *
	 * @param int $id
	 * @param UpdateRosterAPIRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateRosterAPIRequest $request)
	{
		$input = $request->all();

		/** @var Roster $roster */
		$roster = $this->rosterRepository->find($id);

		if (empty($roster)) {
			return $this->sendError('Roster not found');
		}

		$roster = $this->rosterRepository->update($input, $id);

		return $this->sendResponse($roster->toArray(), 'Roster updated successfully');
	}

	/**
	 * Remove the specified Roster from storage.
	 * DELETE /roster/{id}
	 *
	 * @param int $id
	 *
	 * @throws \Exception
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		/** @var Roster $roster */
		$roster = $this->rosterRepository->find($id);

		if (empty($roster)) {
			return $this->sendError('Roster not found');
		}

		$roster->delete();

		return $this->sendResponse($id, 'Roster deleted successfully');
	}
}

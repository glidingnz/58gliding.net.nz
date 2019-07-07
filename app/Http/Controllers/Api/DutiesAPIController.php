<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatedutiesAPIRequest;
use App\Http\Requests\API\UpdatedutiesAPIRequest;
use App\Models\Duty;
use App\Repositories\dutiesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class dutiesController
 * @package App\Http\Controllers\API
 */

class DutiesAPIController extends AppBaseController
{
	/** @var  dutiesRepository */
	private $dutiesRepository;

	public function __construct(dutiesRepository $dutiesRepo)
	{
		$this->dutiesRepository = $dutiesRepo;
	}

	/**
	 * Display a listing of the duties.
	 * GET|HEAD /duties
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function index(Request $request)
	{
		$query = Duty::query();

		// limit by organisation
		if ($request->has('org_id')) $query->where('org_id','=',$request->input('org_id'));

		if ($results = $query->get())
		{
			return $this->success($results);
		}
		
		return $this->sendError('Duties not found');
	}

	/**
	 * Store a newly created duties in storage.
	 * POST /duties
	 *
	 * @param CreatedutiesAPIRequest $request
	 *
	 * @return Response
	 */
	public function store(CreatedutiesAPIRequest $request)
	{
		$input = $request->all();

		$duties = $this->dutiesRepository->create($input);

		return $this->sendResponse($duties->toArray(), 'Duties saved successfully');
	}

	/**
	 * Display the specified duties.
	 * GET|HEAD /duties/{id}
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		/** @var duties $duties */
		$duties = $this->dutiesRepository->find($id);

		if (empty($duties)) {
			return $this->sendError('Duties not found');
		}

		return $this->sendResponse($duties->toArray(), 'Duties retrieved successfully');
	}

	/**
	 * Update the specified duties in storage.
	 * PUT/PATCH /duties/{id}
	 *
	 * @param int $id
	 * @param UpdatedutiesAPIRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdatedutiesAPIRequest $request)
	{
		$input = $request->all();

		/** @var duties $duties */
		$duties = $this->dutiesRepository->find($id);

		if (empty($duties)) {
			return $this->sendError('Duties not found');
		}

		$duties = $this->dutiesRepository->update($input, $id);

		return $this->sendResponse($duties->toArray(), 'duties updated successfully');
	}

	/**
	 * Remove the specified duties from storage.
	 * DELETE /duties/{id}
	 *
	 * @param int $id
	 *
	 * @throws \Exception
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		/** @var duties $duties */
		$duties = $this->dutiesRepository->find($id);

		if (empty($duties)) {
			return $this->sendError('Duties not found');
		}

		$duties->delete();

		return $this->sendResponse($id, 'Duties deleted successfully');
	}
}

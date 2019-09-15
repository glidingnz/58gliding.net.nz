<?php

namespace App\Exports;

use App\Models\Member;
use Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Classes\MemberUtilities;

class MembersExport implements FromCollection, WithHeadings
{
	use Exportable;

	protected $request;

	public function __construct($request)
	{
		$this->request = $request;
	}

	/**
	* @return \Illuminate\Support\Collection
	*/
	public function collection()
	{
		$memberUtilities = new MemberUtilities();
		$query = $memberUtilities->get_filtered_members($this->request);
		$members = $query->get();
		
		$memberUtilities->filter_view_results($members);

		return $members;
	}

	
	public function headings(): array
	{
		$columns = Schema::getColumnListing('gnz_member');
		// remove hidden columns
		unset($columns[3]);
		unset($columns[4]);
		return $columns;
	}
}

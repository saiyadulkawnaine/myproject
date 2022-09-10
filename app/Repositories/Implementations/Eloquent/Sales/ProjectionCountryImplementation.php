<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\ProjectionCountryRepository;
use App\Model\Sales\ProjectionCountry;
use App\Traits\Eloquent\MsTraits;
class ProjectionCountryImplementation implements ProjectionCountryRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProjectionCountry $model)
	{
		$this->model = $model;
	}
	
	public function getAll(){
		return $rows = $this->selectRaw(
		'projection_countries.id,
		projection_countries.country_ship_date,
		projection_countries.country_id,
		projection_countries.cut_off,
		projection_countries.cut_off_date,
		projections.proj_no,
		countries.name,
		sum(projection_qties.qty) as qty,
		sum(projection_qties.amount) as amount'
		
		)
		
		->join('projections', function($join)  {
		$join->on('projections.id', '=', 'projection_countries.projection_id');
		})
		->join('countries', function($join)  {
		$join->on('countries.id', '=', 'projection_countries.country_id');
		})
		->leftJoin('projection_qties',function($join){
		  $join->on('projection_qties.projection_country_id','=','projection_countries.id');
	    })
		->when(request('projection_id'), function ($q) {
			return $q->where('projection_countries.projection_id', '=', request('projection_id', 0));
		})
		->groupBy([
		'projection_countries.id',
		'projection_countries.country_ship_date',
		'projection_countries.country_id',
		'projection_countries.cut_off',
		'projection_countries.cut_off_date',
		'projections.proj_no',
		'countries.name'
		])
		->get();
	}
}

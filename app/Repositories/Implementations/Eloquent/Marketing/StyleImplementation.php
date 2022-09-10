<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleRepository;
use App\Model\Marketing\Style;
use App\Traits\Eloquent\MsTraits;
class StyleImplementation implements StyleRepository
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
	public function __construct(Style $model)
	{
		$this->model = $model;
	}

	public function getAll(){
		$rows=$this
		->leftJoin('buyers', function($join)  {
		$join->on('styles.buyer_id', '=', 'buyers.id');
		})
		->leftJoin('buyers as buyingagents', function($join)  {
		$join->on('styles.buying_agent_id', '=', 'buyingagents.id');
		})
		->leftJoin('uoms', function($join)  {
		$join->on('styles.uom_id', '=', 'uoms.id');
		})
		->leftJoin('seasons', function($join)  {
		$join->on('styles.season_id', '=', 'seasons.id');
		})
		->leftJoin('teams', function($join)  {
		$join->on('styles.team_id', '=', 'teams.id');
		})
		->leftJoin('teammembers', function($join)  {
		$join->on('styles.teammember_id', '=', 'teammembers.id');
		})
		->leftJoin('users', function($join)  {
		$join->on('users.id', '=', 'teammembers.user_id');
		})
		->leftJoin('productdepartments', function($join)  {
		$join->on('productdepartments.id', '=', 'styles.productdepartment_id');
		})
		->when(request('buyer_id'), function ($q) {
		return $q->where('styles.buyer_id', '=', request('buyer_id', 0));
		})
		->when(request('style_ref'), function ($q) {
		return $q->where('styles.style_ref', 'like', '%'.request('style_ref', 0).'%');
		})
		->when(request('style_description'), function ($q) {
		return $q->where('styles.style_description', 'like', '%'.request('style_description', 0).'%');
		})
		->orderBy('styles.id','desc')
		->get([
		'styles.*',
		'buyers.code as buyer_name',
		'uoms.name as uom_name',
		'seasons.name as season_name',
		'teams.name as team_name',
		'users.name as team_member_name',
		'productdepartments.department_name',
		'buyingagents.name as buying_agent_id'
		]);
		/*->paginate(request('rows',0), [
		'styles.*',
		'buyers.code as buyer_name',
		'uoms.name as uom_name',
		'seasons.name as season_name',
		'teams.name as team_name',
		'users.name as team_member_name',
		'productdepartments.department_name'
		]);*/
		return $rows;
	}
}

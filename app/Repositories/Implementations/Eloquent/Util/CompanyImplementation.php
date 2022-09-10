<?php
 
namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CompanyRepository;
use App\Model\Util\Company;
use App\Traits\Eloquent\MsTraits; 
class CompanyImplementation implements CompanyRepository
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
	public function __construct(Company $model)
	{
		$this->model = $model;
	}

	public function get(){
        $companies=array();
		if(\Auth::user()->level() == 5)
		{
			$companies=$this->selectRaw(
			'companies.*'
			)
			->get();
		}
		else 
		{
           $companies=$this->selectRaw(
			'companies.*'
			)
			->join('company_users', function($join) {
				$join->on('company_users.company_id', '=', 'companies.id');
			})
			->where([['company_users.user_id','=',\Auth::user()->id]])
			->whereNull('company_users.deleted_at')
			->get();
		}
		return $companies;
	}
}
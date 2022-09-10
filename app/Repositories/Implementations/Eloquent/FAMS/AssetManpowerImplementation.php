<?php
namespace App\Repositories\Implementations\Eloquent\FAMS;
use App\Repositories\Contracts\FAMS\AssetManpowerRepository;
use App\Model\FAMS\AssetManpower;
use App\Traits\Eloquent\MsTraits; 
class AssetManpowerImplementation implements AssetManpowerRepository
{
	use MsTraits;
	
	/**
	 * @var $model
	 */
	private $model;
 
	/**
	 * AssetManpowerImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(AssetManpower $model)
	{
		$this->model = $model;
	}
	
	
}
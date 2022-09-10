<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StylePkgRatioRepository;
use App\Model\Marketing\StylePkgRatio;
use App\Traits\Eloquent\MsTraits;
class StylePkgRatioImplementation implements StylePkgRatioRepository
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
	public function __construct(StylePkgRatio $model)
	{
		$this->model = $model;
	}
	
	public  function matrix($style_pkg_id){
		return $this->makeMatrix($style_pkg_id);
		
	}
	private function makeMatrix($style_pkg_id){
		$matrix=array();
		
		$stylepkgs= $this->where([['style_pkg_id','=',$style_pkg_id]])->get();
		foreach ($stylepkgs as $stylepkg) {
			$matrix[$stylepkg->style_gmt_id][$stylepkg->style_color_id][$stylepkg->style_size_id]=$stylepkg->qty;
		}
		return $matrix;
	}
}

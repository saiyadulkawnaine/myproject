<?php

namespace App\Repositories\Implementations\Eloquent\Bom;
use App\Repositories\Contracts\Bom\CadRepository;
use App\Model\Bom\Cad;
use App\Traits\Eloquent\MsTraits;
class CadImplementation implements CadRepository
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
    public function __construct(Cad $model)
    {
        $this->model = $model;
    }
}

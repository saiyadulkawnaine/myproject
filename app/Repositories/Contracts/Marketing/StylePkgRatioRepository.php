<?php

namespace App\Repositories\Contracts\Marketing;
use App\Repositories\Contracts\MsRepository;

interface StylePkgRatioRepository extends  MsRepository
{
    function matrix($style_pkg_id);
}

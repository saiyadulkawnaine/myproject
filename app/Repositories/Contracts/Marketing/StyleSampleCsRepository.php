<?php

namespace App\Repositories\Contracts\Marketing;
use App\Repositories\Contracts\MsRepository;

interface StyleSampleCsRepository extends  MsRepository
{
	 function matrix($style_sample_id);

}

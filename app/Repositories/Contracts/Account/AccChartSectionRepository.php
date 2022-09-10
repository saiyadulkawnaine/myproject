<?php

namespace App\Repositories\Contracts\Account;
use App\Repositories\Contracts\MsRepository;

interface AccChartSectionRepository extends  MsRepository
{
public function getByChartId($acc_chart_ctrl_head_id);
}

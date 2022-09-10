<h2 align="center">{{ $cgroup->company_name }}</h2>
<p align="center">
    {{-- @foreach ($cgroup as $cd)
    @endforeach --}}
    <strong>{{ $cgroup->company_address }}</strong>
</p>
<p align="center">Lisence or Certificate Status</p>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2">SL</th>
            <th rowspan="2">Document Name</th>
            <th align="center" colspan="3" class="text-center">A21</th>
            <th align="center" colspan="3" class="text-center">LAL</th>
            <th align="center" colspan="3" class="text-center">FFL</th>
            <th align="center" colspan="3" class="text-center">FDL</th>
            <th align="center" colspan="3" class="text-center">FPL</th>
            <th align="center" colspan="3" class="text-center">MIL</th>
        </tr>
        <tr>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
            <th align="center">Expire Date</th>
            <th align="center">Status</th>
            <th align="center">Remarks</th>
        </tr>
    </thead>
    <?php
    $i=1;
    ?>
    <tbody>
        @foreach ($renewalitems as $renewal_item_id=>$item)
        <?php
        $expire_date2=isset($data[$renewal_item_id][2]['expire_date'])?$data[$renewal_item_id][2]['expire_date']:'';
        $status2=isset($data[$renewal_item_id][2]['status'])?$data[$renewal_item_id][2]['status']:'';
        $remarks2=isset($data[$renewal_item_id][2]['remarks'])?'Click':'';

        $expire_date1=isset($data[$renewal_item_id][1]['expire_date'])?$data[$renewal_item_id][1]['expire_date']:'';
        $status1=isset($data[$renewal_item_id][1]['status'])?$data[$renewal_item_id][1]['status']:'';
        $remarks1=isset($data[$renewal_item_id][1]['remarks'])?'Click':'';

        $expire_date4=isset($data[$renewal_item_id][4]['expire_date'])?$data[$renewal_item_id][4]['expire_date']:'';
        $status4=isset($data[$renewal_item_id][4]['status'])?$data[$renewal_item_id][4]['status']:'';
        $remarks4=isset($data[$renewal_item_id][4]['remarks'])?'Click':'';

        $expire_date5=isset($data[$renewal_item_id][5]['expire_date'])?$data[$renewal_item_id][5]['expire_date']:'';
        $status5=isset($data[$renewal_item_id][5]['status'])?$data[$renewal_item_id][5]['status']:'';
        $remarks5=isset($data[$renewal_item_id][5]['remarks'])?'Click':'';

        $expire_date6=isset($data[$renewal_item_id][6]['expire_date'])?$data[$renewal_item_id][6]['expire_date']:'';
        $status6=isset($data[$renewal_item_id][6]['status'])?$data[$renewal_item_id][6]['status']:'';
        $remarks6=isset($data[$renewal_item_id][6]['remarks'])?'Click':'';

        $expire_date3=isset($data[$renewal_item_id][3]['expire_date'])?$data[$renewal_item_id][3]['expire_date']:'';
        $status3=isset($data[$renewal_item_id][3]['status'])?$data[$renewal_item_id][3]['status']:'';
        $remarks3=isset($data[$renewal_item_id][3]['remarks'])?'Click':'';

        $exp2=isset($data[$renewal_item_id][2]['exp'])?$data[$renewal_item_id][2]['exp']:'';
        $exp1=isset($data[$renewal_item_id][1]['exp'])?$data[$renewal_item_id][1]['exp']:'';
        $exp4=isset($data[$renewal_item_id][4]['exp'])?$data[$renewal_item_id][4]['exp']:'';
        $exp5=isset($data[$renewal_item_id][5]['exp'])?$data[$renewal_item_id][5]['exp']:'';
        $exp6=isset($data[$renewal_item_id][6]['exp'])?$data[$renewal_item_id][6]['exp']:'';
        $exp3=isset($data[$renewal_item_id][3]['exp'])?$data[$renewal_item_id][3]['exp']:'';
        //$validend2=isset($data[$renewal_item_id][2]['validend'])?$data[$renewal_item_id][2]['validend']:'';

        
        $color="background-color:red";


        ?>
        <tr>
            <td>{{ $i }}</th>
            <td>{{ $itemNameArr[$renewal_item_id]['renewal_item'] }}</td>
            
            <td align="center" style="<?php echo $exp2 ?>">{{ $expire_date2 }}</td>
            <td align="center">{{ $status2 }}</td>
            <td align="center">
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},2)">{{ $remarks2 }}</a>
            </td>

            

            <td align="center" style="<?php echo $exp1 ?>">{{ $expire_date1 }}</td>
            <td align="center">{{ $status1 }}</td>
            <td align="center">
                <?php

                ?>
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},1)">{{ $remarks1 }}</a>
            </td>

            <td align="center" style="<?php echo $exp4 ?>">{{ $expire_date4 }}</td>
            <td align="center">{{ $status4 }}</td>
            <td align="center">
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},4)">{{ $remarks4 }}</a>
            </td>

            <td align="center" style="<?php echo $exp5 ?>">{{ $expire_date5 }}</td>
            <td align="center">{{ $status5 }}</td>
            <td align="center">
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},5)">{{ $remarks5 }}</a>
            </td>

            <td align="center" style="<?php echo $exp6 ?>">{{ $expire_date6 }}</td>
            <td align="center">{{ $status6 }}</td>
            <td align="center">
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},6)">{{ $remarks6 }}</a>
            </td>

            <td align="center" style="<?php echo $exp3 ?>">{{ $expire_date3 }}</td>
            <td align="center">{{ $status3 }}</td>
            <td align="center">
                <a href="javascript:void(0)" onClick="MsRenewalReport.renewalEntryRemarksWindow({{$renewal_item_id}},3)">{{ $remarks3 }}</a>
            </td>
      
        </tr>
    <?php
    $i++;
    ?>
        @endforeach
    </tbody>
</table>
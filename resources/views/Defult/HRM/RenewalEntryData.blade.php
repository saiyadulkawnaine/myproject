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
            <th align="center" colspan="3">A21</th>
            <th align="center" colspan="3">LAL</th>
            <th align="center" colspan="3">FFL</th>
            <th align="center" colspan="3">FDL</th>
            <th align="center" colspan="3">FPL</th>
            <th align="center" colspan="3">MIL</th>
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
        $status2=isset($data[$renewal_item_id][2]['applied_date'])?$data[$renewal_item_id][2]['applied_date']:'';
        $remarks2=isset($data[$renewal_item_id][2]['remarks'])?$data[$renewal_item_id][2]['remarks']:'';

        $expire_date1=isset($data[$renewal_item_id][1]['expire_date'])?$data[$renewal_item_id][1]['expire_date']:'';
        $status1=isset($data[$renewal_item_id][1]['applied_date'])?$data[$renewal_item_id][1]['applied_date']:'';
        $remarks1=isset($data[$renewal_item_id][1]['remarks'])?$data[$renewal_item_id][1]['remarks']:'';

        $expire_date4=isset($data[$renewal_item_id][4]['expire_date'])?$data[$renewal_item_id][4]['expire_date']:'';
        $status4=isset($data[$renewal_item_id][4]['applied_date'])?$data[$renewal_item_id][4]['applied_date']:'';
        $remarks4=isset($data[$renewal_item_id][4]['remarks'])?$data[$renewal_item_id][4]['remarks']:'';

        $expire_date5=isset($data[$renewal_item_id][5]['expire_date'])?$data[$renewal_item_id][5]['expire_date']:'';
        $status5=isset($data[$renewal_item_id][5]['applied_date'])?$data[$renewal_item_id][5]['applied_date']:'';
        $remarks5=isset($data[$renewal_item_id][5]['remarks'])?$data[$renewal_item_id][5]['remarks']:'';

        $expire_date6=isset($data[$renewal_item_id][6]['expire_date'])?$data[$renewal_item_id][6]['expire_date']:'';
        $status6=isset($data[$renewal_item_id][6]['applied_date'])?$data[$renewal_item_id][6]['applied_date']:'';
        $remarks6=isset($data[$renewal_item_id][6]['remarks'])?$data[$renewal_item_id][6]['remarks']:'';

        $expire_date3=isset($data[$renewal_item_id][3]['expire_date'])?$data[$renewal_item_id][3]['expire_date']:'';
        $status3=isset($data[$renewal_item_id][3]['applied_date'])?$data[$renewal_item_id][3]['applied_date']:'';
        $remarks3=isset($data[$renewal_item_id][3]['remarks'])?$data[$renewal_item_id][3]['remarks']:'';

        ?>
        <tr>
            <td>{{ $i }}</th>
            <td>{{ $itemNameArr[$renewal_item_id]['renewal_item'] }}</td>
            
                <td align="center">{{ $expire_date2 }}</td>
                <td align="center">{{ $status2 }}</td>
                <td align="center">{{ $remarks2 }}</td>

                <td align="center">{{ $expire_date1 }}</td>
                <td align="center">{{ $status1 }}</td>
                <td align="center">{{ $remarks1 }}</td>

                <td align="center">{{ $expire_date4 }}</td>
                <td align="center">{{ $status4 }}</td>
                <td align="center">{{ $remarks4 }}</td>

                <td align="center">{{ $expire_date5 }}</td>
                <td align="center">{{ $status5 }}</td>
                <td align="center">{{ $remarks5 }}</td>

                <td align="center">{{ $expire_date6 }}</td>
                <td align="center">{{ $status6 }}</td>
                <td align="center">{{ $remarks6 }}</td>

                <td align="center">{{ $expire_date3 }}</td>
                <td align="center">{{ $status3 }}</td>
                <td align="center">{{ $remarks3 }}</td>
      
        </tr>
    <?php
    $i++;
    ?>
        @endforeach
    </tbody>
</table>
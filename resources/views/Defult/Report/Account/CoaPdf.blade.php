

<table  border="1">
     
        <thead>
        <tr>
        <th width="40" align="center">Sl</th>
        <th width="70" align="center">A/C Code</th>
        <th width="150" align="center">Head Name</th>
        <th width="90" align="center">Report Head</th>
        <th width="50" align="center">Sub Group</th>
        <th width="80" align="center">Main Group</th>
        <th width="80" align="center">Statement Type</th>
        <th width="100" align="center">Control Name</th>
        <th width="80" align="center">Currency</th>
        <th width="80" align="center">Other Type</th>
        <th width="60" align="center">Normal Balance</th>
        <th width="60" align="center">Status</th>
        </tr>
        </thead>
        <?php
        $i=1;
        ?>
        @foreach($ctrlhead as $value)

        <tr>
        <td width="40" align="center">{{ $i++ }}</td>
        <td width="70" align="center">{{ $value->code}}</td>
        <td width="150" align="left">{{ $value->name}}</td>
        <td width="90" align="left">{{ $value->root_id}}</td>
        <td width="50" align="left">{{ $value->sub_group_name}}</td>
        <td width="80" align="left">{{ $value->accchartgroup}}</td>
        <td width="80" align="left">{{ $value->statement_type_id}}</td>
        <td width="100" align="left">{{$value->control_name_id}}</td>
        <td width="80" align="left">{{$value->currency_id}}</td>
        <td width="80" align="left">{{ $value->other_type_id }}</td>
        <td width="60">{{ $value->normal_balance_id}}</td>
        <td width="60">{{$value->status}}</td>
        </tr>
        <?php
        //
        ?>
        @endforeach
        
        

</table>


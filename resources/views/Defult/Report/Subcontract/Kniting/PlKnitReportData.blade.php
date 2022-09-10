<table border="1" width="{{$tableWidth}}">
    <thead>
    <tr style="background-color: #EAE9E9">
        <th rowspan="2">SL</th>
        <th rowspan="2" width="150">M/C NO</th>
        <th rowspan="2" width="80">Dia/Gauge</th>
        @foreach($months as $month=>$value)
        <td colspan="{{ count($value)}}" align="center">{{$month}}</td>
        @endforeach
    </tr> 
    <tr style="background-color: #EAE9E9">
        
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
        <td width="40" align="center">{{date('d',strtotime($days))}}</td>
        @endforeach
        @endforeach
    </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($machines as $machine)
      <tr>
        <td>{{$i}}</td>
        <td>{{$machine->custom_no}}-{{$machine->brand}}</td>
        <td>{{$machine->dia_width}}X{{$machine->gauge}}</td>
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
        <?php
        $qty='';
        $bgColor='';
        if(isset($planData[$machine->id][$month][$days])){
           $qty=number_format($planData[$machine->id][$month][$days],0);
           $bgColor='';
        }
        else{
            $qty=''; 
            $bgColor='#FF0000';
        }
        ?>
        <td width="40" align="center" style="background-color: {{$bgColor}}">{{$qty}}</td>
        @endforeach
        @endforeach

    </tr> 
    <?php
        $i++;
        ?>
    @endforeach 
    </tbody>
    <tfoot>
         <tr style="background-color: #3c8b3c; color: #FFFFFF">
        <th></th>
        <th width="150">Total</th>
        <th width="80"></th>
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
                <?php
        $qty='';
        $bgColor='';
        if(isset($dateTotal[$month][$days])){
           $qty=number_format($dateTotal[$month][$days],0);
           $bgColor='';
        }
        else{
            $qty=''; 
            $bgColor='#FF0000';
        }
        ?>
        <td width="40" align="center">{{$qty}}</td>
        @endforeach
        @endforeach
    </tr>
    </tfoot>
</table>
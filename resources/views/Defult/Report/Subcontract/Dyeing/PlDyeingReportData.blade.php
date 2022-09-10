<table border="1" width="{{$tableWidth}}">
    <thead>
    <tr style="background-color: #EAE9E9">
        <th rowspan="2">SL</th>
        <th rowspan="2" width="150">M/C NO</th>
        <th rowspan="2" width="80">Brand</th>
        <th rowspan="2" width="80">Capacity</th>
        <th rowspan="2" width="80">Particulars</th>
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
        <td rowspan="3">{{$i}}</td>
        <td align="center" rowspan="3">{{$machine->custom_no}}</td>
        <td align="center" rowspan="3">{{$machine->brand}}</td>
        <td align="center" rowspan="3">{{$machine->prod_capacity}}</td>
        <td align="center">Plan Qty</td>
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
        <?php
        $qty='';
        $bgColor='';
        if(isset($planData[$machine->id][$month][$days]['qty'])){
           $qty=number_format($planData[$machine->id][$month][$days]['qty'],0);
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
    <tr>
        <td align="center">Color</td>
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
        <?php
        $fabcolor='';
        $bgColor='';
        if(isset($planData[$machine->id][$month][$days]['color'])){
           $fabcolor=$planData[$machine->id][$month][$days]['color'];
           $bgColor='';
        }
        else{
            $fabcolor=''; 
            $bgColor='#FF0000';
        }
        ?>
        <td width="40" align="center" style="background-color: {{$bgColor}}">{{$fabcolor}}</td>
        @endforeach
        @endforeach

    </tr> 
    <tr>
       <td align="center">Ord. No</td>
        @foreach($months as $month=>$value)
        @foreach($value as $days=>$val)
        <?php
        $ord_no='';
        $bgColor='';
        if(isset($planData[$machine->id][$month][$days]['ord_no'])){
           $ord_no=$planData[$machine->id][$month][$days]['ord_no'];
           $bgColor='';
        }
        else{
            $ord_no=''; 
            $bgColor='#FF0000';
        }
        ?>
        <td width="40" align="center" style="background-color: {{$bgColor}}">{{$ord_no}}</td>
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
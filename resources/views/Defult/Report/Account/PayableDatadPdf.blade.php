<p align="center">
    <caption style="text-align: center;"><font style=" font-weight: bold;">Lithe Group <br/>Payable Report<br/> As On: {{$ason}}</font></caption>
</p>
@foreach($buy2 as $buyerId=>$buyerName)
<?php
    $comcol=count($comp2)+3;
?>
<table  border="1" style="margin: 0 auto;">
    <thead>
        <tr>
            <th colspan="{{$comcol}}">{{$buyerName}}</th>
        </tr>

        <tr>
            <th width="40" class="text-center">SL</th>
            <th width="150" class="text-center">Head</th>
            @foreach($comp2 as $key=>$company_code)
            <th width="100" class="text-center">{{$company_code}}</th>
            @endforeach
            <th width="100" class="text-center">Total</th>
            <th width="73" class="text-center">% on Total</th>
        </tr>
    </thead>
<?php
$i=1;
?>
@foreach($acc as $accId=>$accName)
@if($accTot2['amount'][$buyerId][$accId])
<tr>
<td width="40" align="left">{{$i}}</td>
<td width="150" align="left">{{$accName}}</td>
@foreach($comp2 as $key=>$company_code)
<td width="100" align="right">{{$data2[$buyerId][$accId][$key]['amount']}}</td>
@endforeach
<td width="100" align="right">{{$accTot2['amount'][$buyerId][$accId]}}</td>
<td width="73" align="right">
   @if(array_sum($buyTot2['amount'][$buyerId])) 
   {{ number_format($accTot2['amount'][$buyerId][$accId]/array_sum($buyTot2['amount'][$buyerId])*100,2)}}
   @endif
</td>
</tr>
<?php
    $i++;
?>
@endif
@endforeach
<tr>
<td width="40" align="left"></td>
<td width="150" align="left">Total</td>
@foreach($comp2 as $key=>$company_code)
<td width="100" align="right">{{$buyTot2['amount'][$buyerId][$key]}}</td>
@endforeach
<td width="100" align="right">{{array_sum($buyTot2['amount'][$buyerId])}}</td>
<td width="73" align="right">
    100
</td>
</tr>

</table>
<br/>
@endforeach

<table  border="1" style="margin: 0 auto;">
    <tr>
        <td width="190" align="right" colspan="2">Grand Total</td>
        @foreach($comp2 as $key=>$company_code)
        <td width="100" align="right">{{number_format($comTot2['amount'][$key],0)}}</td>
        @endforeach
        <td width="100" align="right">{{number_format(array_sum($comTot2['amount']),0)}}</td>
        <td width="73" align="right">100</td>
    </tr>
</table>

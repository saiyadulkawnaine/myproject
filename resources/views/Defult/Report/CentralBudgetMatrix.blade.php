<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 24px">Summery</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-center">Company</th>
<th width="150" class="text-center">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-center">Total</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($com as $index=>$name )
<tbody>
    <tr>
        <td width="20" align="center" rowspan="3">{{$i}}</td>
        <td width="60" align="center" rowspan="3"><a href="javascript:void()" onclick="MsCentralBudget.getDetail('{{$date_from}}','{{$date_to}}','{{$index}}')">{{$name}}</a></td>
        <td width="150" align="left">Income</td>
        @foreach($comMonth[$index] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthInc[$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthInc[$index]),0)}}</td>
    </tr>
    <tr>
        
        <td width="150" align="left">Expense</td>
        @foreach($comMonth[$index] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthExp[$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthExp[$index]),0)}}</td>
    </tr>
    <tr>
        
        <td width="150" align="left">Profit</td>
        @foreach($comMonth[$index] as $month=>$value )
        <td width="100" align="right">{{number_format($comMonthPro[$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($comMonthPro[$index]),0)}}</td>
    </tr>
</tbody>
<?php
$i++;
?>
@endforeach
<tr style="background-color: #ccc; font-weight: bold;">
        <td width="20" align="center" rowspan="3"></td>
        <td width="60" align="right" rowspan="3">Total</td>
        <td width="150" align="left">Income</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthInc[$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($monthInc),0)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold;">
        <td width="150" align="left">Expense</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthExp[$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($monthExp),0)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold;">
        <td width="150" align="left">Profit</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthPro[$month],0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($monthPro),0)}}</td>
    </tr>
</table>

<br/>
@if(isset($monthNonCashExp['other_type_id']))
<table  border="1" style="margin: 0 auto;" cellpadding="2" cellspacing="2">
    <caption style="font-weight: bold;font-size: 24px">Non-Cash Expenses</caption>
<thead>
<tr style="background-color: #ccc; height: 15px">
<th width="20" class="text-center">#</th>

<th width="300" class="text-center">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-center">Total</th>
</tr>

</thead>
<?php
$i=1;
?>
<tbody>
    @foreach($monthNonCashExp['other_type_id'] as $other_type_id=>$other_type_name)
    <tr>
        <td width="20" class="text-center"></td>

        <td width="300" class="text-center">{{$other_type_name}}</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($typeMonthNonCashExp['bud'][$other_type_id][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($typeMonthNonCashExp['bud'][$other_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="300" class="text-center">Total</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthNonCashExp['bud'][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['bud']),2)}}</td>
    </tr>
  
</tbody>
</table>
@endif

<br/>
@if(isset($monthTloan['loan_type_id']))
<table  border="1" style="margin: 0 auto;" cellpadding="2" cellspacing="2">
    <caption style="font-weight: bold;font-size: 24px">Term Loan</caption>
<thead>
<tr style="background-color: #ccc; height: 15px">
<th width="20" class="text-center">#</th>

<th width="300" class="text-center">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-center">Total</th>
</tr>

</thead>
<?php
$i=1;
?>
<tbody>
    @foreach($monthTloan['loan_type_id'] as $loan_type_id=>$loan_type_name)
    <tr>
        <td width="20" class="text-center"></td>

        <td width="300" class="text-center">{{$loan_type_name}}</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($typeMonthTloan['bud'][$loan_type_id][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($typeMonthTloan['bud'][$loan_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="300" class="text-center">Total</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthTloan['bud'][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($monthTloan['bud']),2)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Surplus</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthNonCashExp['bud'][$month] - $monthTloan['bud'][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['bud'])- array_sum($monthTloan['bud']),2)}}</td>
    </tr>
   
</tbody>
</table>
 @endif

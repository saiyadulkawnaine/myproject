<div style="font-weight: bold;font-size: 24px; text-align: center;background-color: #021344; color: #FFFFFF">{{$companies->code}} ({{$profitcenters->name}})</div>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 16px">Income</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-left">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-right">Total</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeInc as $incId=>$code )
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left">{{$code}}</td>
        @foreach($codeMonthInc[$incId] as $month=>$value )
        <td width="100" align="right">{{number_format($value,0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($codeMonthInc[$incId]),0)}}</td>
    </tr>
    
</tbody>
<?php
$i++;
?>
@endforeach
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Total</td>
@foreach($monthArr as $month=>$value )
<td width="100" align="right">{{number_format($monthInc[$month],0)}}</td>
@endforeach
<td width="150" align="right">{{number_format(array_sum($monthInc),0)}}</td>
</tr>
</table>

<br/>

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 16px">Fixed Expense</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-left">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-right">Total</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeExpFix as $expFixId=>$code )
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left">{{$code}}</td>
        @foreach($codeMonthExpFix[$expFixId] as $month=>$value )
        <td width="100" align="right">{{number_format($value,0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpFix[$expFixId]),0)}}</td>
    </tr>
    
</tbody>
<?php
$i++;
?>
@endforeach
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Total</td>
@foreach($monthArr as $month=>$value )
<td width="100" align="right">{{number_format($monthExpFix[$month],0)}}</td>
@endforeach
<td width="150" align="right">{{number_format(array_sum($monthExpFix),0)}}</td>
</tr>
</table>

<br/>

<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 16px">Variable Expense</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-left">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">{{$month}}</th>
@endforeach

<th width="150" class="text-right">Total</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeExpVar as $expVarId=>$code )
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left">{{$code}}</td>
        @foreach($codeMonthExpVar[$expVarId] as $month=>$value )
        <td width="100" align="right">{{number_format($value,0)}}</td>
        @endforeach
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpVar[$expVarId]),0)}}</td>
    </tr>
    
</tbody>
<?php
$i++;
?>
@endforeach
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Total</td>
@foreach($monthArr as $month=>$value )
<td width="100" align="right">{{number_format($monthExpVar[$month],0)}}</td>
@endforeach
<td width="150" align="right">{{number_format(array_sum($monthExpVar),0)}}</td>
</tr>
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Total Expense</td>
@foreach($monthArr as $month=>$value )
<td width="100" align="right">{{number_format($monthExp[$month],0)}}</td>
@endforeach
<td width="150" align="right">{{number_format(array_sum($monthExp),0)}}</td>
</tr>
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Budgeted Profit/Loss</td>
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
<tbody>
    @foreach($monthNonCashExp['other_type_id'] as $other_type_id=>$other_type_name)
    <tr>
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">{{$other_type_name}}</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($typeMonthNonCashExp['bud'][$other_type_id][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($typeMonthNonCashExp['bud'][$other_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Total</td>
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
<tbody>
    @foreach($monthTloan['loan_type_id'] as $loan_type_id=>$loan_type_name)
    <tr>
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">{{$loan_type_name}}</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($typeMonthTloan['bud'][$loan_type_id][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($typeMonthTloan['bud'][$loan_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Total</td>
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

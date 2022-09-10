<div style="font-weight: bold;font-size: 24px; text-align: center;background-color: #021344; color: #FFFFFF">{{$companies->code}} ({{$profitcenters->name}})</div>
<table  border="1" style="margin: 0 auto;">
    <caption style="font-weight: bold;font-size: 16px">Income</caption>
<thead>
<tr style="background-color: #ccc">
<th width="20" class="text-center">#</th>
<th width="150" class="text-left">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>
<th width="150" class="text-left"></th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">Budget</th>
<th width="100" class="text-center">Actual</th>
<th width="100" class="text-center">Varience</th>
@endforeach

<th width="150" class="text-center">Budget</th>
<th width="150" class="text-center">Actual</th>
<th width="150" class="text-center">Varience</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeInc['bud'] as $incId=>$code )
 <?php
        if($incId==213 || $incId==394|| $incId==395|| $incId==397 ||$incId==501 || $incId==502 || $incId==1561 || $incId==2542){
            $bgcolorhl='yellow';
        }
        else{
            $bgcolorhl='';
        }
        ?>
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left" style="background-color: {{$bgcolorhl}}">{{$code}}</td>
        @foreach($codeMonthInc['bud'][$incId] as $month=>$value )
        <?php
        if($codeMonthInc['var'][$incId][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        $remarks=$codeMonthIncComment['bud'][$incId][$month];
        ?>
        <td width="100" align="right" title="{{$codeMonthIncComment['bud'][$incId][$month]}}"><a href="javascript:void()" onclick="MsCentralBudget.remarksWindow('{{$remarks}}')">{{number_format($value,0)}}</a></td>
        <td width="100" align="right">{{number_format($codeMonthInc['acl'][$incId][$month],0)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($codeMonthInc['var'][$incId][$month],0)}}</td>
        @endforeach
        <?php
        if(array_sum($codeMonthInc['var'][$incId])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right">{{number_format(array_sum($codeMonthInc['bud'][$incId]),0)}}</td>
        <td width="150" align="right">{{number_format(array_sum($codeMonthInc['acl'][$incId]),0)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($codeMonthInc['var'][$incId]),0)}}</td>
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
<?php
if($monthInc['var'][$month]<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="100" align="right">{{number_format($monthInc['bud'][$month],0)}}</td>
<td width="100" align="right">{{number_format($monthInc['acl'][$month],0)}}</td>
<td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthInc['var'][$month],0)}}</td>
@endforeach
<?php
if(array_sum($monthInc['var'])<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="150" align="right">{{number_format(array_sum($monthInc['bud']),0)}}</td>
<td width="150" align="right">{{number_format(array_sum($monthInc['acl']),0)}}</td>
<td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthInc['var']),0)}}</td>
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
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>
<th width="150" class="text-left"></th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">Budget</th>
<th width="100" class="text-center">Actual</th>
<th width="100" class="text-center">Varience</th>
@endforeach

<th width="150" class="text-center">Budget</th>
<th width="150" class="text-center">Actual</th>
<th width="150" class="text-center">Varience</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeExpFix['bud'] as $expFixId=>$code )
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left">{{$code}}</td>
        @foreach($codeMonthExpFix['bud'][$expFixId] as $month=>$value )
        <?php
        if($codeMonthExpFix['var'][$expFixId][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        $remarks=$codeMonthExpFixComment['bud'][$expFixId][$month];
        ?>
        <td width="100" align="right" title="{{$codeMonthExpFixComment['bud'][$expFixId][$month]}}"><a href="javascript:void()" onclick="MsCentralBudget.remarksWindow('{{$remarks}}')">{{number_format($value,0)}}</a></td>
        <td width="100" align="right">{{number_format($codeMonthExpFix['acl'][$expFixId][$month],0)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($codeMonthExpFix['var'][$expFixId][$month],0)}}</td>
        @endforeach
        <?php
        if(array_sum($codeMonthExpFix['var'][$expFixId])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpFix['bud'][$expFixId]),0)}}</td>
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpFix['acl'][$expFixId]),0)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($codeMonthExpFix['var'][$expFixId]),0)}}</td>
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
<?php
if($monthExpFix['var'][$month]<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="100" align="right">{{number_format($monthExpFix['bud'][$month],0)}}</td>
<td width="100" align="right">{{number_format($monthExpFix['acl'][$month],0)}}</td>
<td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthExpFix['var'][$month],0)}}</td>
@endforeach
<?php
if(array_sum($monthExpFix['var'])<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="150" align="right">{{number_format(array_sum($monthExpFix['bud']),0)}}</td>
<td width="150" align="right">{{number_format(array_sum($monthExpFix['acl']),0)}}</td>
<td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthExpFix['var']),0)}}</td>
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
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>
<th width="150" class="text-left"></th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">Budget</th>
<th width="100" class="text-center">Actual</th>
<th width="100" class="text-center">Varience</th>
@endforeach

<th width="150" class="text-center">Budget</th>
<th width="150" class="text-center">Actual</th>
<th width="150" class="text-center">Varience</th>
</tr>
</thead>
<?php
$i=1;
?>
@foreach($codeExpVar['bud'] as $expVarId=>$code )
<tbody>
    <tr>
        <td width="20" align="center">{{$i}}</td>
        <td width="60" align="left">{{$code}}</td>
        @foreach($codeMonthExpVar['bud'][$expVarId] as $month=>$value )
        <?php
        if($codeMonthExpVar['var'][$expVarId][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        $remarks=$codeMonthExpVarComment['bud'][$expVarId][$month];
        ?>
        <td width="100" align="right" title="{{$codeMonthExpVarComment['bud'][$expVarId][$month]}}"><a href="javascript:void()" onclick="MsCentralBudget.remarksWindow('{{$remarks}}')">{{number_format($value,0)}}</a></td>
        <td width="100" align="right">{{number_format($codeMonthExpVar['acl'][$expVarId][$month],0)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($codeMonthExpVar['var'][$expVarId][$month],0)}}</td>
        @endforeach
        <?php
        if(array_sum($codeMonthExpVar['var'][$expVarId])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpVar['bud'][$expVarId]),0)}}</td>
        <td width="150" align="right">{{number_format(array_sum($codeMonthExpVar['acl'][$expVarId]),0)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($codeMonthExpVar['var'][$expVarId]),0)}}</td>
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
<?php
if($monthExpVar['var'][$month]<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="100" align="right">{{number_format($monthExpVar['bud'][$month],0)}}</td>
<td width="100" align="right">{{number_format($monthExpVar['acl'][$month],0)}}</td>
<td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthExpVar['var'][$month],0)}}</td>
@endforeach
<?php
if(array_sum($monthExpVar['var'])<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="150" align="right">{{number_format(array_sum($monthExpVar['bud']),0)}}</td>
<td width="150" align="right">{{number_format(array_sum($monthExpVar['acl']),0)}}</td>
<td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthExpVar['var']),0)}}</td>
</tr>
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Total Expense</td>
@foreach($monthArr as $month=>$value )
<?php
if($monthExp['var'][$month]<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="100" align="right">{{number_format($monthExp['bud'][$month],0)}}</td>
<td width="100" align="right">{{number_format($monthExp['acl'][$month],0)}}</td>
<td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthExp['var'][$month],0)}}</td>
@endforeach
<?php
if(array_sum($monthExp['var'])<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="150" align="right">{{number_format(array_sum($monthExp['bud']),0)}}</td>
<td width="150" align="right">{{number_format(array_sum($monthExp['acl']),0)}}</td>
<td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthExp['var']),0)}}</td>
</tr>
<tr style="background-color: #ccc; font-weight: bold;">
<td width="20" align="center"></td>
<td width="60" align="right">Budgeted Profit/Loss</td>
@foreach($monthArr as $month=>$value )
<?php
if($monthPro['var'][$month]<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="100" align="right">{{number_format($monthPro['bud'][$month],0)}}</td>
<td width="100" align="right">{{number_format($monthPro['acl'][$month],0)}}</td>
<td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthPro['var'][$month],0)}}</td>
@endforeach
<?php
if(array_sum($monthPro['var'])<0){
$bgcolor='red';
}
else{
$bgcolor='';
}
?>
<td width="150" align="right">{{number_format(array_sum($monthPro['bud']),0)}}</td>
<td width="150" align="right">{{number_format(array_sum($monthPro['acl']),0)}}</td>
<td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthPro['var']),0)}}</td>
</tr>
</table>

@if(isset($monthNonCashExp['other_type_id']))
<table  border="1" style="margin: 0 auto;" cellpadding="2" cellspacing="2">
    <caption style="font-weight: bold;font-size: 24px">Non-Cash Expenses</caption>
<thead>
<tr style="background-color: #ccc; height: 15px">
<th width="20" class="text-center">#</th>

<th width="150" class="text-center">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>

<th width="150" class="text-center"></th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">Budget</th>
<th width="100" class="text-center">Actual</th>
<th width="100" class="text-center">Varience</th>
@endforeach

<th width="150" class="text-center">Budget</th>
<th width="150" class="text-center">Actual</th>
<th width="150" class="text-center">Varience</th>
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
        <?php
        if($typeMonthNonCashExp['var'][$other_type_id][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="100" align="right">{{number_format($typeMonthNonCashExp['bud'][$other_type_id][$month],2)}}</td>
        <td width="100" align="right">{{number_format($typeMonthNonCashExp['acl'][$other_type_id][$month],2)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($typeMonthNonCashExp['var'][$other_type_id][$month],2)}}</td>
        @endforeach
        <?php
        if(array_sum($typeMonthNonCashExp['var'][$other_type_id])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>

        <td width="150" align="right">{{number_format(array_sum($typeMonthNonCashExp['bud'][$other_type_id]),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($typeMonthNonCashExp['acl'][$other_type_id]),2)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($typeMonthNonCashExp['var'][$other_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Total</td>
        @foreach($monthArr as $month=>$value )
        <?php
        if($monthNonCashExp['var'][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="100" align="right">{{number_format($monthNonCashExp['bud'][$month],2)}}</td>
        <td width="100" align="right">{{number_format($monthNonCashExp['acl'][$month],2)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthNonCashExp['var'][$month],2)}}</td>
        @endforeach
        <?php
        if(array_sum($monthNonCashExp['var'])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>

        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['bud']),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['acl']),2)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthNonCashExp['var']),2)}}</td>
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
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>

<th width="150" class="text-center"></th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center">Budget</th>
<th width="100" class="text-center">Actual</th>
<th width="100" class="text-center">Varience</th>
@endforeach

<th width="150" class="text-center">Budget</th>
<th width="150" class="text-center">Actual</th>
<th width="150" class="text-center">Varience</th>
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
        <?php
        if($typeMonthTloan['var'][$loan_type_id][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="100" align="right">{{number_format($typeMonthTloan['bud'][$loan_type_id][$month],2)}}</td>
        <td width="100" align="right">{{number_format($typeMonthTloan['acl'][$loan_type_id][$month],2)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($typeMonthTloan['var'][$loan_type_id][$month],2)}}</td>
        @endforeach
        <?php
        if(array_sum($typeMonthTloan['var'][$loan_type_id])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>

        <td width="150" align="right">{{number_format(array_sum($typeMonthTloan['bud'][$loan_type_id]),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($typeMonthTloan['acl'][$loan_type_id]),2)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($typeMonthTloan['var'][$loan_type_id]),2)}}</td>
    </tr>
    @endforeach
    <tr style="background-color: #ccc">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Total</td>
        @foreach($monthArr as $month=>$value )
        <?php
        if($monthTloan['var'][$month]<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>
        <td width="100" align="right">{{number_format($monthTloan['bud'][$month],2)}}</td>
        <td width="100" align="right">{{number_format($monthTloan['acl'][$month],2)}}</td>
        <td width="100" align="right" style="background-color: {{$bgcolor}}">{{number_format($monthTloan['var'][$month],2)}}</td>
        @endforeach
        
        <?php
        if(array_sum($monthTloan['var'])<0){
        $bgcolor='red';
        }
        else{
        $bgcolor='';
        }
        ?>

        <td width="150" align="right">{{number_format(array_sum($monthTloan['bud']),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($monthTloan['acl']),2)}}</td>
        <td width="150" align="right" style="background-color: {{$bgcolor}}">{{number_format(array_sum($monthTloan['var']),2)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold;">
        <td width="20" class="text-center"></td>

        <td width="150" class="text-center">Surplus</td>
        @foreach($monthArr as $month=>$value )
        <td width="100" align="right">{{number_format($monthNonCashExp['bud'][$month] - $monthTloan['bud'][$month],2)}}</td>
        <td width="100" align="right">{{number_format($monthNonCashExp['acl'][$month] - $monthTloan['acl'][$month],2)}}</td>
        <td width="100" align="right">{{number_format($monthNonCashExp['var'][$month] - $monthTloan['var'][$month],2)}}</td>
        @endforeach

        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['bud']) - array_sum($monthTloan['bud']),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['acl']) - array_sum($monthTloan['acl']),2)}}</td>
        <td width="150" align="right">{{number_format(array_sum($monthNonCashExp['var']) - array_sum($monthTloan['var']),2)}}</td>
    </tr>
   
</tbody>
</table>
 @endif

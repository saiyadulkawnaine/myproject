<table  border="1" style="margin: 0 auto;" cellpadding="2" cellspacing="2">
    <caption style="font-weight: bold;font-size: 24px">Summery Budget Vs Actual</caption>
<thead>
<tr style="background-color: #ccc; height: 15px">
<th width="20" class="text-center">#</th>
<th width="150" class="text-center">Company</th>
<th width="150" class="text-center">Particulars</th>
@foreach($monthArr as $month=>$value )
<th width="100" class="text-center" colspan="3">{{$month}}</th>
@endforeach

<th width="150" class="text-center" colspan="3">Total</th>
</tr>
<tr style="background-color: #ccc">
<th width="20" class="text-center"></th>
<th width="150" class="text-center"></th>
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
@foreach($com as $index=>$name )

    <tr style="height: 15px">
        <td width="20" align="center" rowspan="3">{{$i}}</td>
        <td width="60" align="center" rowspan="3"><a href="javascript:void()" onclick="MsCentralBudget.getDetailBudVsAcl('{{$date_from}}','{{$date_to}}','{{$index}}')">{{$name}}</a></td>
        <td width="150" align="left">Income</td>
        @foreach($comMonth[$index] as $month=>$value )
        <?php
        if($comMonthInc['var'][$index][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthInc['bud'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthInc['acl'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format($comMonthInc['var'][$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthInc['bud'][$index]),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthInc['acl'][$index]),0)}}</td>
        <?php
        if(array_sum($comMonthInc['var'][$index])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format(array_sum($comMonthInc['var'][$index]),0)}}</td>
    </tr>
    <tr>
        
        <td width="150" align="left">Expense</td>
        @foreach($comMonth[$index] as $month=>$value )
        <?php
        if($comMonthExp['var'][$index][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthExp['bud'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthExp['acl'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format($comMonthExp['var'][$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthExp['bud'][$index]),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthExp['acl'][$index]),0)}}</td>
        <?php
        if(array_sum($comMonthExp['var'][$index])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format(array_sum($comMonthExp['var'][$index]),0)}}</td>
    </tr>
    <tr>
        
        <td width="150" align="left">Profit</td>
        @foreach($comMonth[$index] as $month=>$value )
        <?php
        if($comMonthPro['var'][$index][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthPro['bud'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($comMonthPro['acl'][$index][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format($comMonthPro['var'][$index][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthPro['bud'][$index]),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($comMonthPro['acl'][$index]),0)}}</td>
        <?php
        if(array_sum($comMonthPro['var'][$index])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format(array_sum($comMonthPro['var'][$index]),0)}}</td>
    </tr>

<?php
$i++;
?>
@endforeach
</tbody>
<tr style="background-color: #ccc; font-weight: bold;">
        <td width="20" align="center" rowspan="3"></td>
        <td width="60" align="right" rowspan="3">Total</td>
        <td width="150" align="left">Income</td>
        @foreach($monthArr as $month=>$value )
        <?php
        if($monthInc['var'][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthInc['bud'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthInc['acl'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px ; background-color: {{$bgcolor}}">{{number_format($monthInc['var'][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthInc['bud']),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthInc['acl']),0)}}</td>
        <?php
        if(array_sum($monthInc['var'])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format(array_sum($monthInc['var']),0)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold;">
        <td width="150" align="left">Expense</td>
        @foreach($monthArr as $month=>$value )
        <?php
        if($monthExp['var'][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthExp['bud'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthExp['acl'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px ; background-color: {{$bgcolor}}">{{number_format($monthExp['var'][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthExp['bud']),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthExp['acl']),0)}}</td>
         <?php
        if(array_sum($monthExp['var'])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}">{{number_format(array_sum($monthExp['var']),0)}}</td>
    </tr>
    <tr style="background-color: #ccc; font-weight: bold;">
        <td width="150" align="left">Profit</td>
        @foreach($monthArr as $month=>$value )
        <?php
        if($monthPro['var'][$month]<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthPro['bud'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px">{{number_format($monthPro['acl'][$month],0)}}</td>
        <td width="100" align="right" style="padding: 5px ; background-color: {{$bgcolor}}">{{number_format($monthPro['var'][$month],0)}}</td>
        @endforeach
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthPro['bud']),0)}}</td>
        <td width="150" align="right" style="padding: 5px">{{number_format(array_sum($monthPro['acl']),0)}}</td>
        <?php
        if(array_sum($monthPro['var'])<0){
            $bgcolor='red';
        }
        else{
            $bgcolor='';
        }
        ?>
        <td width="150" align="right" style="padding: 5px; background-color: {{$bgcolor}}"> {{number_format(array_sum($monthPro['var']),0)}}</td>
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
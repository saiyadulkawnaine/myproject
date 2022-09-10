<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Sales (CM Earnings)</th>
       
    </tr>
    <tr>
        <th width="30px" class="text-center">SL</th>
        <th width="350px" class="text-center">Particulars</th>
        <th width="350px" class="text-center">Data Source and Proportion Basis</th>
        <th width="30px" class="text-center">Amount/Month</th>
        <th width="30px" class="text-center">Amount/Day</th>
    </tr>
    <?php
    $i=1;
    $etamountmonth=0;
    $etamountday=0;
    ?>
    @foreach($earnings as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{number_format($row->amount,0)}}</td>
        <td width="30px"align="right">{{number_format($row->per_day,0)}}</td>
    </tr>
    <?php
    $etamountmonth+=$row->amount;
    $etamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{number_format($etamountmonth,0)}}</td>
        <td width="30px"align="right">{{number_format($etamountday,0)}}</td>
   </tr>
</table>


<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Variable Expenses</th>
       
    </tr>
    <tr align="center">
        <th width="30px" class="text-center" >SL</th>
        <th width="350px" class="text-center">Particulars</th>
        <th width="350px" class="text-center">Data Source and Proportion Basis</th>
        <th width="30px" class="text-center">Amount/Month</th>
        <th width="30px" class="text-center">Amount/Day</th>
    </tr>
    <?php
    $i=1;
    $vtamountmonth=0;
    $vtamountday=0;
    ?>
    @foreach($vexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{number_format($row->amount,0)}}</td>
        <td width="30px"align="right">{{number_format($row->per_day,0)}}</td>
    </tr>
    <?php
    $vtamountmonth+=$row->amount;
    $vtamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
   <?php
    $contribumonth=$etamountmonth-$vtamountmonth;
    $contribuday=$etamountday-$vtamountday;
    $contribumonth_per=0;
    $contribuday_per=0;
    if($etamountmonth){
        $contribumonth_per=($contribumonth/$etamountmonth)*100;
        $contribuday_per=($contribuday/$etamountday)*100;
    }
    
    ?>
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{number_format($vtamountmonth,0)}}</td>
        <td width="30px"align="right">{{number_format($vtamountday,0)}}</td>
   </tr>
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Contribution Margin</td>
        <td width="30px"align="right">{{number_format($contribumonth,0)}}</td>
        <td width="30px"align="right">{{number_format($contribuday,0)}}</td>
   </tr>
    <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Contribution Margin %</td>
        <td width="30px"align="right">{{number_format($contribumonth_per,2)}}</td>
        <td width="30px"align="right">{{number_format($contribuday_per,2)}}</td>
   </tr>
</table>

<br/>


<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Fixed Expenses</th>
       
    </tr>
    <tr align="center">
        <th width="30px" class="text-center">SL</th>
        <th width="350px" class="text-center">Particulars</th>
        <th width="350px" class="text-center">Data Source and Proportion Basis</th>
        <th width="30px" class="text-center">Amount/Month</th>
        <th width="30px" class="text-center">Amount/Day</th>
    </tr>
    <?php
    $i=1;
    $ftamountmonth=0;
    $ftamountday=0;
    ?>
    @foreach($fexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{number_format($row->amount,0)}}</td>
        <td width="30px"align="right">{{ number_format($row->per_day,0)}}</td>
    </tr>
    <?php
    $ftamountmonth+=$row->amount;
    $ftamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
<?php
$bepmonth=0;
$bepday=0;
if($contribumonth_per){
$bepmonth=$ftamountmonth/($contribumonth_per/100);
$bepday=$ftamountday/($contribuday_per/100);
}
?>
   <tr align="center">
        <td width="30px"align="right" colspan="3" style="font-weight: bold;">Total</td>
        
        <td width="30px"align="right">{{number_format($ftamountmonth,0)}}</td>
        <td width="30px"align="right">{{number_format($ftamountday,0)}}</td>
</tr>

<tr align="center">
        <td width="30px"align="right" colspan="3" style="font-weight: bold;">Net Profit/Loss</td>
        <td width="30px"align="right">{{number_format($contribumonth-$ftamountmonth,0)}}</td>
        <td width="30px"align="right">{{number_format($contribuday-$ftamountday,0)}}</td>
   </tr>

    <!-- <tr align="center">
    <td width="30px"align="right" colspan="3" style="font-weight: bold;">Break Event Point In Taka</td>
    <td width="30px"align="right">{{number_format($bepmonth,0)}}</td>
    <td width="30px"align="right">{{number_format($bepday,0)}}</td>
    </tr>
    <tr align="center">
    <td width="30px"align="right" colspan="3" style="font-weight: bold;">Break Event Point In USD ( Exch. Rate: {{$exch_rate}} )</td>
    <td width="30px"align="right">{{number_format($bepmonth/$exch_rate,0)}}</td>
    <td width="30px"align="right">{{number_format($bepday/$exch_rate,0)}}</td>
    </tr> -->
</table>
<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th align="center" colspan="5">BEP Details</th>
       
    </tr>
    <tr align="center">
        <th width="30px" class="text-center">SL</th>
        <th width="350px" class="text-center">Particulars</th>
        <th width="350px" class="text-center">Qty</th>
        <th width="30px" class="text-center">Value/BDT</th>
        <th width="95px" class="text-center">Value/USD  @ {{$exch_rate}}</th>
    </tr>
   
    <tr align="left">
        <td >1</td>
        <td >Monthly BEP in Qty (Unit Price @ {{$accbepmst->unit_price}} USD) </td>
        <td align="right">{{number_format($bepmonth/($accbepmst->unit_price*$exch_rate),0)}}</td>
        <td align="right">{{number_format($bepmonth,0)}}</td>
        <td align="right">{{number_format($bepmonth/$exch_rate,0)}}</td>
    </tr>

    <tr align="left">
        <td>2</td>
        <td>Daily BEP in Qty (Unit Price @ {{$accbepmst->unit_price}} USD)</td>
        <td align="right">{{number_format($bepday/($accbepmst->unit_price*$exch_rate),0)}}</td>
        <td align="right">{{number_format($bepday,0)}}</td>
        <td align="right">{{number_format($bepday/$exch_rate,0)}}</td>
    </tr>
    <!-- <tr align="left">
        <td>3</td>
        <td>Monthly production target with {{$accbepmst->profit_per}} % margin</td>
        <td align="right">{{number_format(($bepmonth/$accbepmst->unit_price)+(($bepmonth/$accbepmst->unit_price)*($accbepmst->profit_per/100)),0)}}</td>
        <td align="right">{{number_format($bepmonth+($bepmonth*($accbepmst->profit_per/100)),0)}}</td>
        <td align="right">{{number_format(($bepmonth/$exch_rate)+(($bepmonth/$exch_rate)*($accbepmst->profit_per/100)),0)}}</td>
    </tr>

    <tr align="left">
        <td>4</td>
        <td>Daily production target with {{$accbepmst->profit_per}} % margin</td>
        <td align="right">{{number_format(($bepday/$accbepmst->unit_price)+(($bepday/$accbepmst->unit_price)*($accbepmst->profit_per/100)),0)}}</td>
        <td align="right">{{number_format($bepmonth+($bepday*($accbepmst->profit_per/100)),0)}}</td>
        <td align="right">{{number_format(($bepday/$exch_rate)+(($bepday/$exch_rate)*($accbepmst->profit_per/100)),0)}}</td>
    </tr> -->
    
   
</table>
@if($subsection)
<br>
<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="5">Capacity Per Day</th>
       
    </tr>
    <tr align="center">
        <th width="30px" class="text-center">SL</th>
        <th width="350px" class="text-center">Particulars</th>
        <th width="350px" class="text-center"></th>
        
    </tr>
   
    <tr align="left">
        <td >1</td>
        <td >Total Line</td>
        <td align="right">{{$subsection->id}}</td>
        
    </tr>

    <tr align="left">
        <td>2</td>
        <td>Per line per day @ 10 hours</td>
        <td width="350px" align="right">{{$subsection->qty}}</td>
        
    </tr>
    <tr align="left">
        <td>3</td>
        <td>Daily Production Capacity</td>
        <td align="right">{{$subsection->qty/26}}</td>
        
    </tr>

  
    
   
</table>
@endif

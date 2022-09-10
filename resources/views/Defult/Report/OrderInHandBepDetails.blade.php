<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Sales (CM Earnings)</th>
       
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
        <th width="30px"align="center">Amount/Day</th>
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
        <td width="30px"align="right">{{number_format($row->amount,2)}}</td>
        <td width="30px"align="right">{{number_format($row->per_day,2)}}</td>
    </tr>
    <?php
    $etamountmonth+=$row->amount;
    $etamountday+=$row->per_day;
    $i++;
    ?>
   @endforeach
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{number_format($etamountmonth,2)}}</td>
        <td width="30px"align="right">{{number_format($etamountday,2)}}</td>
   </tr>
</table>


<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">Variable Expenses</th>
       
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
        <th width="30px"align="center">Amount/Day</th>
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
        <td width="30px"align="right">{{number_format($row->amount,2)}}</td>
        <td width="30px"align="right">{{number_format($row->per_day,2)}}</td>
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
        $contribumonth_per=$contribumonth/$etamountmonth;
        $contribuday_per=$contribuday/$etamountday;
    }
    
    ?>
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{number_format($vtamountmonth,2)}}</td>
        <td width="30px"align="right">{{number_format($vtamountday,2)}}</td>
   </tr>
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Contribution Margin</td>
        <td width="30px"align="right">{{number_format($contribumonth,2)}}</td>
        <td width="30px"align="right">{{number_format($contribuday,2)}}</td>
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
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
        <th width="30px"align="center">Amount/Day</th>
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
        <td width="30px"align="right">{{number_format($row->amount,2)}}</td>
        <td width="30px"align="right">{{ number_format($row->per_day,2)}}</td>
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
$bepmonth=$ftamountmonth/$contribumonth_per;
$bepday=$ftamountday/$contribuday_per;
}
?>
   <tr align="center">
        <td width="30px"align="right" colspan="3" style="font-weight: bold;">Total</td>
        
        <td width="30px"align="right">{{number_format($ftamountmonth,2)}}</td>
        <td width="30px"align="right">{{number_format($ftamountday,2)}}</td>
</tr>

 <tr align="center">
        <td width="30px"align="right" colspan="3" style="font-weight: bold;">Break Event Point In Taka</td>
        <td width="30px"align="right">{{number_format($bepmonth,2)}}</td>
        <td width="30px"align="right">{{number_format($bepday,2)}}</td>
   </tr>
</table>
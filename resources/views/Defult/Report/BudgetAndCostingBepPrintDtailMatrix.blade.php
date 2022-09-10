<h2> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    @foreach ($prodcompany as $comp)
        {{ $comp->name }}
    @endforeach
<h4> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fixed & Variable Expenses</h4>


<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">A.&nbsp;Variable Expenses </th>       
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
    </tr>
    <?php
    $i=1;
    $vtamountmonth=0;
    ?>
    @foreach($vexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{ number_format($row->amount,2) }}</td>
    </tr>
    <?php
    $vtamountmonth+=$row->amount;
    $i++;
    ?>
   @endforeach
   <tr align="center" style="font-weight: bold;">
        <td width="30px"align="right" colspan="3">Total</td>
        <td width="30px"align="right">{{ number_format($vtamountmonth,2) }}</td>
   </tr>
</table>

<br/>

<table border="1" style="border-style:dotted;line-height:25px;">
    <tr align="center">
        <th width="30px"align="center" colspan="4">B.&nbsp;Fixed Expenses</th>
    </tr>
    <tr align="center">
        <th width="30px"align="center">SL</th>
        <th width="350px"align="center">Particulars</th>
        <th width="350px"align="center">Data Source and Proportion Basis</th>
        <th width="30px"align="center">Amount/Month</th>
    </tr>
    <?php
    $i=1;
    $ftamountmonth=0;
    $stoverheadTotalmonth=0;
    ?>
    @foreach($fexpense as $row)
    <tr align="left">
        <td width="30px">{{$i}}</td>
        <td width="350px">{{$row->name}}</td>
        <td width="350px">{{$row->remarks}}</td>
        <td width="30px"align="right">{{ number_format($row->amount,2) }}</td>
    </tr>
    <?php
    $ftamountmonth+=$row->amount;
    $stoverheadTotalmonth=$ftamountmonth+$vtamountmonth;
    $i++;
    ?>
   @endforeach

<tr align="center">
    <td width="30px"align="right" colspan="3" style="font-weight: bold;">Total</td>
    <td width="30px"align="right">{{number_format($ftamountmonth,2)}}</td>
</tr>
<tr>
    {{-- <th width="30px" align="center" colspan="4">C.&nbsp;Grand Total Expense&nbsp;(A+B)</th> --}}
    <td width="30px"align="left" colspan="3" style="font-weight: bold;">C.&nbsp;Grand Total Expense&nbsp;(A+B)</td>  
    <td width="30px"align="right">{{ number_format($stoverheadTotalmonth,2) }}</td>
</tr>

</table>
<table>
    
    <tr align="left">
        <th width="30px" align="center"></th>
        <th width="350px" align="center"></th>
        <th width="350px" align="left">Standard Overhead Per Pcs</th>
        <th width="30px" align="center"></th>
        <th width="30px" align="center"></th>
    </tr>
    <tr align="left">
        <td width="30px" align="center"></td>
        <td width="350px" align="center"></td>
        <td width="350px" align="left">Total Production Capacity in Pcs</td>
        <?php
            //$i=1;
            $tprodcapacity=0;
        ?>
        @foreach ($prodcompany as $comp)
            <td width="30px" align="right">{{ number_format($comp->screen_print_capacity_qty,2 )}}</td>
        <?php
            //$i++;
            $tprodcapacity+=$comp->screen_print_capacity_qty;
        ?>
        @endforeach
        <td width="30px"align="right"></td>
    </tr>
    <tr align="left">
        <td width="30px" align="center"></td>
        <td width="350px" align="center"></td>
        <td width="350px" align="left">Monthly Overhead in Taka</td>
        {{-- <td width="30px"align="right">{{ number_format($bepmonth,2) }}</td> --}}
        <td width="30px" align="right">{{number_format($stoverheadTotalmonth,2)}}</td>
        <td width="30px" align="right"></td>
    </tr>
    <?php
        $stoverheadcost_taka=0;
        $stoverheadcost_usd=0;
        
        if($tprodcapacity){
        $stoverheadcost_taka=($stoverheadTotalmonth)/$tprodcapacity;
        $stoverheadcost_usd=$stoverheadcost_taka/82;
        }
        $stoverheadcostDzn_usd=0;
        if($stoverheadcost_usd){
            $stoverheadcostDzn_usd=$stoverheadcost_usd*12;
        }
        
    ?>
    <tr align="left">
        <td width="30px"align="center"></td>
        <td width="350px"align="center"></td>
        <td width="350px"align="left">Standard Overhead Cost Per Pcs in Taka</td>
        <td width="30px"align="right">{{ number_format($stoverheadcost_taka,2) }}</td>
        <td width="30px"align="right"></td>
    </tr>
    <tr align="left">
        <td width="30px"align="center"></td>
        <td width="350px"align="center"></td>
        <td width="350px"align="left">Standard Overhead Cost Per Pcs in USD</td>
        <td width="30px"align="right">{{ number_format($stoverheadcost_usd,3) }}</td>
        <td width="30px"align="right"></td>
    </tr>
    <tr align="left">
        <td width="30px"align="center"></td>
        <td width="350px"align="center"></td>
        <td width="350px"align="left">Standard Overhead Cost Per Dzn in USD</td>
        <td width="30px"align="right">{{ number_format($stoverheadcostDzn_usd,3) }}</td>
        <td width="30px"align="right"></td>
    </tr>
</table>
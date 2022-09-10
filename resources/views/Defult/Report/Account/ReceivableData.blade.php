<table  border="1" style="margin: 0 auto;">
    <caption style="text-align: center;"><font style="font-size: 12px; font-weight: bold;">Lithe Group <br/>Receivable Report<br/> As On: {{$ason}}</font></caption>
    <thead>
    <tr>
    <th width="40" class="text-center">SL</th>
    <th width="150" class="text-center">Customer</th>
    @foreach($comp as $key=>$company_code)
    <th width="100" class="text-center">{{$company_code}}</th>
    @endforeach
    <th width="150" class="text-center">Total</th>
    <th width="150" class="text-center">% onTotal</th>
   
    </tr>
    </thead>
    <?php
    $i=1;
    ?>
    @foreach($data as $buyer=>$value)
    <tr>
    <td width="40" align="left">{{$i}}</td>
    <td width="150" align="left">{{$buy[$buyer]}}</td>
    @foreach($comp as $key=>$company_code)
    <td width="100" align="right">{{number_format($value[$key]['amount'],0)}}</td>
    @endforeach
    <td width="150" align="right">{{number_format($buyTot['amount'][$buyer],0)}}</td>
    <td width="100" align="right">{{number_format($buyTot['amount'][$buyer]/array_sum($comTot['amount'])*100,2)}}
    </td>
    </tr>
    <?php
    $i++;
    ?>
    @endforeach

    <tr>
    <td width="40" align="left"></td>
    <td width="150" align="left">Total</td>
    @foreach($comp as $key=>$company_code)
    <td width="100" align="right">{{number_format($comTot['amount'][$key],0)}}</td>
    @endforeach
    <td width="150" align="right">{{number_format(array_sum($comTot['amount']),0)}}</td>
    <td width="100" align="right">100
    </td>
    </tr>
</table>
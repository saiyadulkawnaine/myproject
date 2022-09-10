<table  border="1" style="margin: 0 auto;">
    <caption style="text-align: center;"><font style="font-size: 12px; font-weight: bold;">Lithe Group <br/>Payable Report<br/> As On: {{$ason}}</font></caption>
    <thead>
    <tr>
    <th width="40" class="text-center" rowspan="2">SL</th>
    <th width="150" class="text-center" rowspan="2">Customer</th>
    @foreach($comp as $key=>$company_code)
    <th width="100" class="text-center" colspan="3">{{$company_code}}</th>
    @endforeach
    <th width="150" class="text-center"  colspan="3">Total</th>
    </tr>

    <tr>
    @foreach($comp as $key=>$company_code)
    <th width="100" class="text-center" >Debit</th>
    <th width="100" class="text-center" >Credit</th>
    <th width="100" class="text-center" >Bal</th>
    @endforeach
    <th width="100" class="text-center">Debit</th>
    <th width="100" class="text-center">Credit</th>
    <th width="100" class="text-center">Bal</th>
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
    <td width="100" align="right">{{number_format($value[$key]['d_amount'],0)}}</td>
    <td width="100" align="right">{{number_format($value[$key]['c_amount'],0)}}</td>
    <td width="100" align="right">{{number_format($value[$key]['amount'],0)}}</td>
    @endforeach
    <td width="150" align="right">{{number_format($buyTot['d_amount'][$buyer],0)}}</td>
    <td width="150" align="right">{{number_format($buyTot['c_amount'][$buyer],0)}}</td>
    <td width="150" align="right">{{number_format($buyTot['amount'][$buyer],0)}}</td>
    </tr>
    <?php
    $i++;
    ?>
    @endforeach

    <tr>
    <td width="40" align="left"></td>
    <td width="150" align="left">Total</td>
    @foreach($comp as $key=>$company_code)
    <td width="100" align="right">{{number_format($comTot['d_amount'][$key],0)}}</td>
    <td width="100" align="right">{{number_format($comTot['c_amount'][$key],0)}}</td>
    <td width="100" align="right">{{number_format($comTot['amount'][$key],0)}}</td>
    @endforeach
    <td width="150" align="right">{{number_format(array_sum($comTot['d_amount']),0)}}</td>
    <td width="150" align="right">{{number_format(array_sum($comTot['c_amount']),0)}}</td>
    <td width="150" align="right">{{number_format(array_sum($comTot['amount']),0)}}</td>
    </td>
    </tr>
</table>
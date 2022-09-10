<table  border="1" style="margin: 0 auto;">
    <caption style="text-align: center;"><font style="font-size: 12px; font-weight: bold;">Lithe Group <br/>Receivable Report<br/> As On: {{$ason}}</font></caption>
    <thead>
    <tr style="background-color: #ccc;">
    <th width="40" class="text-center">SL</th>
    <th width="300" class="text-center">Receivable Head</th>
    @foreach($comp as $key=>$company_code)
    <th width="100" class="text-center">{{$company_code}}</th>
    @endforeach
    <th width="150" class="text-center">Total</th>
   
    </tr>
    </thead>
    <?php
    $i=1;
    ?>
    @foreach($data as $accid=>$value)
    <tr>
    <td width="40" align="left">{{$i}}</td>
    <td width="300" align="left">{{$acchArr[$accid]}}</td>
    @foreach($comp as $key=>$company_code)
    <td width="100" align="right"><a href="javascript:void(0)" onClick="MsGroupReceivableReport.getBuyerDelails('{{$ason}}',{{$accid}},{{$key}})">{{number_format($value[$key]['amount'],0)}}</a></td>
    @endforeach
    <td width="150" align="right"><a href="javascript:void(0)" onClick="MsGroupReceivableReport.getBuyerDelails('{{$ason}}',{{$accid}},'')">{{number_format($acchTot['amount'][$accid],0)}}</a></td>
    </td>
    </tr>
    <?php
    $i++;
    ?>
    @endforeach

    <tr style="background-color: #ccc; font-weight: bold;">
    <td width="40" align="left"></td>
    <td width="300" align="left">Total</td>
    @foreach($comp as $key=>$company_code)
    <td width="100" align="right">{{number_format($comTot['amount'][$key],0)}}</td>
    @endforeach
    <td width="150" align="right">{{number_format(array_sum($comTot['amount']),0)}}</td>
    </td>
    </tr>
</table>
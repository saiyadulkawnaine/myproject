

<table  border="1" style="margin: 0 auto;">
     <caption style="text-align: center;"><img src="{{url('/').'/images/logo/'.$company->logo}}" width="300" height="70" /><br/>{{$company->address}} <br/><font style="font-size: 12px; font-weight: bold;">Trial Balance As on {{ $end_date}}</font></caption>
        <thead>
        <tr>
        <th width="50" class="text-center">Gl</th>
        <th width="100" class="text-center">Account</th>
        <th width="300" class="text-center">Account Name</th>
        <th width="90" class="text-center">Debit</th>
        <th width="50" class="text-center">Cridit</th>
        </tr>
        </thead>
       <?php
        $total_debit=0;
        $total_credit=0;
        ?>
        @foreach($data as $row)
        <tr>
            <td width="50" align="center"><a href="javascript:void(0)" onClick="MsTb.pdfGl({{ $row->id}})">Print</a></td>
        <td width="100" align="center"><a href="javascript:void(0)" onClick="MsTb.getGl({{ $row->id}})">{{ $row->code}}</a></td>
        <td width="300" align="left">{{ $row->name}}</td>
        <td width="90" align="right">{{ number_format($row->amount_debit,2,'.',',')}}</td>
        <td width="50" align="right">{{ number_format($row->amount_credit,2,'.',',')}}</td>
        </tr>
        <?php
        $total_debit+=$row->amount_debit;
        $total_credit+=$row->amount_credit;
        ?>
        @endforeach
      <tr style="font-weight: bold;">
       
        <td colspan="3" align="left">Total</td>
        <td width="90" align="right">{{ number_format($total_debit,2,'.',',')}}</td>
        <td width="50" align="right">{{ number_format($total_credit,2,'.',',')}}</td>
        </tr>

</table>

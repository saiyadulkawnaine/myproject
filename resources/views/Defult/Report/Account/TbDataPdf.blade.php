<table>
<tr>
<td colspan="10" align="center"><strong>Trial Balance As on {{ date("d-M-Y",strtotime($end_date))}} </strong></td>
</tr>
</table>
<br/>
<table  border="1">
     
        <thead>
        <tr>
        <th width="100" align="center">Account</th>
        <th width="360" align="center">Account Name</th>
        <th width="89" align="center">Debit</th>
        <th width="89" align="center">Cridit</th>
        </tr>
        </thead>
       <?php
        $total_debit=0;
        $total_credit=0;
        ?>
        @foreach($data as $row)
        <tr>
        <td width="100" align="center">{{ $row->code}}</td>
        <td width="360" align="left">{{ $row->name}}</td>
        <td width="89" align="right">{{ number_format($row->amount_debit,2,'.',',')}}</td>
        <td width="89" align="right">{{ number_format($row->amount_credit,2,'.',',')}}</td>
        </tr>
        <?php
        $total_debit+=$row->amount_debit;
        $total_credit+=$row->amount_credit;
        ?>
        @endforeach
      <tr style="font-weight: bold;">
       
        <td colspan="2" align="left">Total</td>
        <td width="89" align="right">{{ number_format($total_debit,2,'.',',')}}</td>
        <td width="89" align="right">{{ number_format($total_credit,2,'.',',')}}</td>
        </tr>

</table>

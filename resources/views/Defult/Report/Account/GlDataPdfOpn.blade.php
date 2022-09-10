<table>
<tr>
<td colspan="10" align="center"><strong>General Ledger - Opening ( {{ date("d-M-Y",strtotime($start_date))}} To {{ date("d-M-Y",strtotime($end_date))}} )</strong></td>
</tr>
</table>
<br/>
<table  border="1">
     
        <thead>
        <tr>
        <th align="center" width="80" class="text-center">Type</th>
        <th align="center" width="47" class="text-center">Journal No</th>
        <th align="center" width="60" class="text-center">Date</th>
        <th align="center" width="50" class="text-center">Reference No</th>
        <th align="center" width="70" class="text-center">Cost Center</th>
        <th align="center" width="70" class="text-center">Party</th>
        <th align="center" width="60" class="text-center">Debit</th>
        <th align="center" width="60" class="text-center">Credit</th>
        <th align="center" width="60" class="text-center">Balance</th>
        <th align="center" width="80" class="text-center">Narration</th>
        </tr>
        </thead>
@foreach($data as $key=>$value)
         <?php
         $code = substr($key, 0, 6);
         //$openingBalance=isset($opening[$code])?$opening[$code]:0;
         ?>
        <tr><td width="637"><strong>{{$key}} </strong></td></tr>
        
        <?php
        $total_debit=0;
        $total_credit=0;
        //$balance=$openingBalance;
        $balance=0;
        ?>
        @foreach($value as $row)
        <?php
        //if($row->trans_type_id==0){
        $balance=($balance+$row->amount_debit)-$row->amount_credit;
        ?>
        <tr>
        <td width="80">{{ $row->trans_type}}</td>
        <td width="47" align="center">{{ $row->trans_no}}</td>
        <td width="60" align="center">{{ $row->trans_date}}</td>
        <td width="50" align="center">{{ $row->bill_no}}</td>
        <td width="70" align="center"></td>
        <td width="70" align="center">{{ $row->emp_party_name}}</td>
        <td width="60" align="right">{{ number_format($row->amount_debit,2,'.',',')}}</td>
        <td width="60" align="right">{{ number_format($row->amount_credit,2,'.',',')}}</td>
        <td width="60" align="right">{{ number_format($balance,2,'.',',') }}</td>
        <td width="80">{{ $row->chld_narration}} @if($row->instrument_no), Chaq. No : {{ $row->instrument_no }} @endif</td>
        </tr>
        <?php
        $total_debit+=$row->amount_debit;
        $total_credit+=$row->amount_credit;
        //}
        ?>
        @endforeach
        
        <tr>
        <td width="80"><strong>Total</strong></td>
        <td  width="47" align="center"></td>
        <td  width="60" align="center"></td>
        <td  width="50" align="center"></td>
        <td  width="70" align="center"></td>
        <td  width="70" align="center"></td>
        <td  width="60" align="right"><strong>{{ number_format($total_debit,2,'.',',')}}</strong></td>
        <td  width="60" align="right"><strong>{{ number_format($total_credit,2,'.',',')}}</strong></td>
        <td  width="60" align="right"></td>
        <td width="80" ></td>
        </tr>
        <tr>
        <td width="637">-</td>
        </tr>

@endforeach
</table>

<table>
<tr>
<td colspan="10" align="center"><strong>Supllier Ledger ( {{ date("d-M-Y",strtotime($start_date))}} To {{ date("d-M-Y",strtotime($end_date))}} )</strong></td>
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
        <th align="center" width="70" class="text-center">Supplier</th>
        <th align="center" width="60" class="text-center">Debit</th>
        <th align="center" width="60" class="text-center">Credit</th>
        <th align="center" width="60" class="text-center">Balance</th>
        <th align="center" width="80" class="text-center">Narration</th>
        </tr>
        </thead>
        @if(count($data)>0)
        @foreach($data as $supkey=>$supvalue)
        <tr><td colspan="10"><strong>{{ $supplier[$supkey] }}</strong></td></tr>
@foreach($supvalue as $key=>$value)
         <?php
         $code = substr($key, 0, 6);
         $openingBalance=isset($opening[$supkey][$code][0]->amount)?$opening[$supkey][$code][0]->amount:0;
         ?>
        <tr><td width="637"><strong>{{$key}} </strong></td></tr>
        <tr>

        <td width="497">Opening Balance:</td>
        <td width="60" align="right">{{ number_format($openingBalance,2,'.',',')}}</td>
        <td width="80"></td>


        </tr>
        <?php
        $total_debit=0;
        $total_credit=0;
        $balance=$openingBalance;
        ?>
        @foreach($value as $row)
        <?php
        if($row->trans_type_id>0){
        $balance=($balance+$row->amount_debit)-$row->amount_credit;
        ?>
        <tr>
        <td width="80">{{ $row->trans_type}}</td>
        <td width="47" align="center">{{ $row->trans_no}}</td>
        <td width="60" align="center">{{ $row->trans_date}}</td>
        <td width="50" align="center">{{ $row->bill_no}}</td>
        <td width="70" align="center"></td>
        <td width="70" align="center">{{ $row->supplier_name}}</td>
        <td width="60" align="right">{{ number_format($row->amount_debit,2,'.',',')}}</td>
        <td width="60" align="right">{{ number_format($row->amount_credit,2,'.',',')}}</td>
        <td width="60" align="right">{{ number_format($balance,2,'.',',') }}</td>
        <td width="80">{{ $row->chld_narration}}</td>
        </tr>
        <?php
        $total_debit+=$row->amount_debit;
        $total_credit+=$row->amount_credit;
    }
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
@endforeach

@elseif (count($data) <= 0)
    @foreach($opening as $supkey=>$supvalue)
    <tr><td width="638"><strong>{{ $supplier[$supkey] }}</strong></td></tr>
    @foreach($supvalue as $key=>$value)
    <?php
         $code = substr($key, 0, 6);
         //print_r($value)
         $openingBalance=$value[0]->amount;
         ?>
        <tr><td width="637"><strong>{{$value[0]->acc_ctrl_head_name}} </strong></td></tr>
        <tr>

        <td width="497">Opening Balance:</td>
        
        <td width="60" align="right">{{ number_format($openingBalance,2,'.',',')}}</td>
        <td width="80"></td>


        </tr>
    @foreach($value as $row)

    @endforeach
    @endforeach
    @endforeach
@else

@endif
</table>

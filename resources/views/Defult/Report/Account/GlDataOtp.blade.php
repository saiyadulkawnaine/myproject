

<table  border="1" style="margin: 0 auto;">
     <caption style="text-align: center;"><img src="{{url('/').'/images/logo/'.$company->logo}}" width="300" height="70" /><br/>{{$company->address}} <br/><font style="font-size: 12px; font-weight: bold;">Other Party Ledger ( {{ date("d-M-Y",strtotime($start_date))}} To {{ date("d-M-Y",strtotime($end_date))}} )</font></caption>
        <thead>
        <tr>
        <th width="100" class="text-center">Type</th>
        <th width="50" class="text-center">Journal No</th>
        <th width="90" class="text-center">Date</th>
        <th width="50" class="text-center">Reference No</th>
        <th width="80" class="text-center">Cost Center</th>
        <th width="80" class="text-center">Other Party</th>
        <th width="100" class="text-center">Debit</th>
        <th width="80" class="text-center">Credit</th>
        <th width="80" class="text-center">Balance</th>
        <th width="200" class="text-center">Narration</th>
        </tr>
        </thead>
@if(count($data)>0)
        
        @foreach($data as $supkey=>$supvalue)
        <tr><td colspan="10"><strong>{{ $supplier[$supkey] }}</strong></td></tr>
@foreach($supvalue as $key=>$value)         <?php
         $code = substr($key, 0, 6);
         $openingBalance=isset($opening[$supkey][$code][0]->amount)?$opening[$supkey][$code][0]->amount:0;
         ?>
        <tr><td colspan="10"><strong>{{$key}} </strong></td></tr>
        <tr>

        <td colspan="8">Opening Balance:</td>
        
        <td width="80" align="right">{{ number_format($openingBalance,2,'.',',')}}</td>
        <td width="200"></td>


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
        <td width="100">{{ $row->trans_type}}</td>
        <td width="50" align="center"><a href="javascript:void(0)" onClick="MsGlOtp.showJournal({{ $row->id}})">{{ $row->trans_no}}</a></td>
        <td width="90" align="center">{{ $row->trans_date}}</td>
        <td width="50" align="center">{{ $row->bill_no}}</td>
        <td width="80" align="center"></td>
        <td width="80" align="center">{{ $row->other_party_name}}</td>
        <td width="100" align="right">{{ number_format($row->amount_debit,2,'.',',')}}</td>
        <td width="80" align="right">{{ number_format($row->amount_credit,2,'.',',')}}</td>
        <td width="80" align="right">{{ number_format($balance,2,'.',',') }}</td>
        <td width="200">{{ $row->chld_narration}}</td>
        </tr>
        <?php
        $total_debit+=$row->amount_debit;
        $total_credit+=$row->amount_credit;
    }
        ?>
        @endforeach
        
        <tr>
        <td width="100"><strong>Total</strong></td>
        <td width="50" align="center"></td>
        <td width="90" align="center"></td>
        <td width="50" align="center"></td>
        <td width="80" align="center"></td>
        <td width="80" align="center"></td>
        <td width="100" align="right"><strong>{{ number_format($total_debit,2,'.',',')}}</strong></td>
        <td width="80" align="right"><strong>{{ number_format($total_credit,2,'.',',')}}</strong></td>
        <td width="80" align="right"></td>
        <td width="200"></td>
        </tr>
        <tr>
        <td colspan="10">-</td>
        </tr>

@endforeach
@endforeach

@elseif (count($data) <= 0)
    @foreach($opening as $supkey=>$supvalue)
    <tr><td colspan="10"><strong>{{ $supplier[$supkey] }}</strong></td></tr>
    @foreach($supvalue as $key=>$value)
    <?php
         $code = substr($key, 0, 6);
         //print_r($value)
         $openingBalance=$value[0]->amount;
         ?>
        <tr><td colspan="10"><strong>{{$value[0]->acc_ctrl_head_name}} </strong></td></tr>
        <tr>

        <td colspan="8">Opening Balance:</td>
        
        <td width="80" align="right">{{ number_format($openingBalance,2,'.',',')}}</td>
        <td width="200"></td>


        </tr>
    @foreach($value as $row)

    @endforeach
    @endforeach
    @endforeach
@else

@endif
</table>

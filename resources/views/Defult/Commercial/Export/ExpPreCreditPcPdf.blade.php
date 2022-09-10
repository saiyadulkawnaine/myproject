<p>Date: {{date('d-m-Y',strtotime($rows['cr_date']))}}</p>
<p></p>
<p>To<br/>{{$rows['contact']}}<br/>{{$rows['bank_name']}}<br/>{{$rows['branch_name']}}<br/>{{$rows['bank_address']}}</p>
<p style='text-align:justify;'>{{$sub}}</p>
<p>Dear Sir,</p>
<p>{{$body}}</p>
<?php 
$lesFobCom=($lc_sc_amount*2)/100;
$net_fob_value=$lc_sc_amount-$lesFobCom;
if ($rows['bank_id']==62) {
    $bai_salam_amount=($lc_sc_amount*10)/100;
}else{
  $bai_salam_amount=($net_fob_value*15)/100;  
}

?>
<table cellpadding="2" cellspacing="1">
    <tr>
        <td align="left" width="150">Total L/C value:</td>
        <td align="left" width="200"><strong>{{ $rows['currency_code'] }}&nbsp;{{ $rows['currency_symbol'] }}&nbsp;{{ number_format($lc_sc_amount,2) }}</strong></td>
    </tr>
    <tr>
        <td align="left" width="150">Less freight & com:</td>
        <td align="left" width="200"><strong>{{ $rows['currency_code'] }}&nbsp;{{ $rows['currency_symbol'] }}&nbsp;{{ number_format($lesFobCom,2) }}</strong></td>
    </tr>
    <tr>
        <td align="left" width="150">Net FOB value:</td>
        <td align="left" width="200"><strong>{{ $rows['currency_code'] }}&nbsp;{{ $rows['currency_symbol'] }}&nbsp;{{ $net_fob_value }}</strong></td>
    </tr>
    <tr>
        <td align="left" width="150">@if($rows['bank_id']==62)Packing credit amount
    
@else
Bai-salam amount:  
@endif</td>
        <td align="left" width="200"><strong>{{ $rows['currency_code'] }}&nbsp;{{ $rows['currency_symbol'] }}&nbsp;{{ $bai_salam_amount }}</strong></td>
    </tr>
</table>

<p></p>
<p></p>
<p>{{$ttp2}}</p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td>Thanking You.</td>
    </tr>
</table>
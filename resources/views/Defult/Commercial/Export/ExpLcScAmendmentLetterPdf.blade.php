<p>Date: {{date('d-m-Y',strtotime($rows['last_amendment_date']))}}</p>
<p></p>
<p></p>
<p>To<br/>{{$rows['contact']}}<br/>{{$rows['bank_name']}}<br/>{{$rows['branch_name']}}<br/>{{$rows['bank_address']}}</p>
<p style='text-align:justify;'>{{-- @if ($rows['lc_sc_nature_id']==3)Sub : Amendment of {{$rows['remarks']}} of LC no: {{$rows['lc_sc_no']}} date : {{$rows['lc_sc_date']}} for {{ $rows['currency_code']}}&nbsp; {{$rows['currency_symbol']}}&nbsp; {{$rows['lc_sc_value']}} as replacement of Sales Contract  No: {{$replacedScLc[$rows['id']]}}&nbsp;. @else --}}
{{$sub}} 
{{-- @endif --}}</p>
<p>Dear Sir,</p>
<p>{{-- @if ($rows['lc_sc_nature_id']==3)We have, hereby, submitted export LC mentioned in the subject line as lien to have working capital finance that received from  {{$rows['buyer_name']}} as amendment of existing lien against replacement of Sales Contract  No: {{$replacedScLc[$rows['id']]}} @else --}}{{$body}} 
{{-- @endif --}}</p>
<p></p>
<p>{{$ttp2}}</p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td>Thanking You.</td>
    </tr>
</table>
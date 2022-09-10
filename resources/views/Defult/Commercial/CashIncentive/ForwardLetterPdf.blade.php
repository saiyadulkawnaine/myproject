<p>Date: {{ $rows['claim_sub_date'] }}</p>
<p>To<br/>{{$rows['contact']}}<br/>{{$rows['bank_name']}}<br/>{{$rows['branch_name']}}<br/>{{$rows['bank_address']}}</p>
<p style='text-align:justify;'>{{$sub}}</p>
<p>Dear Sir,</p>
<p>{{$body}}</p>

<p></p>
<p>{{$ttp2}}</p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td><br/><br/><br/></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td><p></p></td>
        <td></td><td></td>
    </tr>
    <tr>
        <td>Thanking You.<br/>
        Yours Faithfully,<br/>
        {{$rows['beneficiary_id']}}
        <br/><br/><br/><br/>
        </td>
        <td></td><td></td>
    </tr>
    <tr>
        <td></td>
        <td></td><td></td>
    </tr>
</table>
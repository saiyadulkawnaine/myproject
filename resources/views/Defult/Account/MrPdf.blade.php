<table cellspacing="0" cellpadding="2" border="0">
<tr align="center">
<td width="638"><strong>Money Receipt</strong></td>
</tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="2" border="0">
<tr>
<td width="214">SL No {{ $transprnt['trans_no'] }}</td>
<td width="106"></td>
<td width="106"></td>
<td width="212">Date {{ $transprnt['trans_date'] }}</td>

</tr>
</table>
<p></p>
<table cellspacing="0" cellpadding="2" border="0">
<tr align="">
<td width="638">Received  <strong>{{ $transprnt['amount'] }} /-</strong> ({{$transprnt['inword']}}) only. by {{ $transprnt['by'] }} @if($transprnt['instrument_no']) number {{ $transprnt['instrument_no'] }} date {{ $transprnt['place_date'] }} @endif @if($transprnt['receiverom']) from {{ $transprnt['receiverom']}}  @endif. {{ $transprnt['narration'] }}.</td>
</tr>
</table>

<p></p>
<p></p>
<table cellspacing="0" cellpadding="2" border="0">
<tr >
<td width="128" align="left">Authorised Signature</td>
<td width="128"></td>
<td width="128"></td>
<td width="127"></td>
<td width="127" align="center" rowspan="3" style="border: 1px solid #000">Stamp</td>
</tr>
<tr align="center">
<td width="128"></td>
<td width="128"></td>
<td width="128"></td>
<td width="127"></td>

</tr>
<tr align="center">
<td width="128"></td>
<td width="128"></td>
<td width="128"></td>
<td width="127"></td>

</tr>
</table>
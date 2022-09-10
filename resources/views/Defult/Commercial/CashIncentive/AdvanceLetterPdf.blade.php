<p  align="right">Date: {{ $rows->applied_date }}</p>
<p>To<br/>{{ ($rows->contact) }}<br/>{{$rows->bank_name }}<br/>{{$rows->branch_name }}<br/>{{$rows->bank_address }}</p>
<p style='text-align:justify;'>Subject: Application for sanctioning TK. {{ number_format($rows->total_amount,0) }} as {{ $rows->advance_per }}% advance against {{ $rows->is_islamic_bank }} as claimed as a texttile industry exporting (EU Zone & Regular) on behalf {{ $rows->company_name }} a valued client of your branch</p>
<p></p>
<p>We are one of your valued customer,operating banking activities since long. We have submitted following files which are under finalization process. Due to settle some emergency financial obligations, we need {{ $rows->advance_per }}% advance from following Incentive claims.</p>
<p></p>
<table border="1" cellspacing="0" cellpadding="2">
    <tr>
        <th width="40px" align="center">SL</th>
        <th width="100px" align="center">ID No</th>
        <th width="180px" align="center">Bank ref. No</th>
        <th width="100px" align="center">Claim Amount</th>
        <th width="100px" align="center">{{ $rows->advance_per }}% Amount</th>
    </tr>
    <?php
        $i=1;
        $tLocalAmount=0;
        $tAdvanceAmount=0;
    ?>
    @foreach ($incentiveadvclaim as $data)
        <tr  nobr="true">
            <td width="40" align="center">{{ $i++ }}</td>
            <td width="100px" align="center">{{ $data->cash_incentive_ref_id }}</td>
            <td width="180px" align="center">{{ $data->bank_file_no }}</td>
            <td width="100px" align="right">{{ number_format($data->local_cur_amount,0) }}</td>
            <td width="100px" align="right" >{{ number_format($data->amount,0) }}</td>
        </tr> 
    <?php
        $tLocalAmount+=$data->local_cur_amount;
        $tAdvanceAmount+=$data->amount;
    ?> 
    @endforeach
    <tr>
        <td width="320" colspan="3" align="center"><strong>Total</strong></td>
        <td width="100px" align="right">{{ number_format($tLocalAmount,0) }}</td>
        <td width="100px" align="right">{{ number_format($tAdvanceAmount,0) }}</td>
    </tr>
</table>
<p></p>
<p><strong>In Words: {{ $rows->inword }} taka only</strong></p>
<p></p>
<p>Therefore you are highly requested to sanction {{ $rows->advance_per }}% advance against {{ $rows->is_islamic_bank }} as mentioned in the subject line.</p>
<p></p>
<p>Your kind co-operation in this regard would be highly appreciated.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td width="130px"><hr>&nbsp;&nbsp;Authorized Signature</td>
        <td width="100px"></td>
        <td width="100px"></td>
        <td width="100px"></td>
    </tr>
</table>

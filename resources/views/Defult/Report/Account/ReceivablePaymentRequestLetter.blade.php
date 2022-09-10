<h1 align="center">Lithe Group</h1>
<p align="center">56, A.H Tower (12th & 13th Floor), Sector# 03, Road# 02, Uttara, Dhaka.</p>
<p></p>
<p></p>
<p>{{date('l, F j, Y')}}</p>
<p>To<br/>
Managing Director,<br/>
{{$buyerinfo->name}}
{{$buyerinfo->address}}
</p>
<p></p>
<p><strong>Subject: </strong>Payment request against receivable of amounting to Tk. <strong>{{number_format($buyerinfo->amount,0)}}</strong> ({{ $buyerinfo->inword }}) in all respect.</p> 
<p></p> 
<P>Dear Sir,<br/><br/>
We would like to inform you that as per your account maintained in our books, credit limit has been exceeded as mentioned in below matrix. Keeping your account regular, we request to pay outstanding soon as maximum as possible.
</P>
@foreach($buy2 as $buyerId=>$buyerName)
<?php
$comcol=count($comp2)+3;
?>
<p><strong>RECEIVABLE DETAILS</strong></p>
<table  border="1" cellspacing="0" cellpadding="2">
    <thead>
        <tr>
            <th width="20" align="center"><strong>SL</strong></th>
            <th width="200" align="center"><strong>Receivable Head</strong></th>
            @foreach($comp2 as $key=>$company_code)
            <th width="80" align="center"><strong>{{$company_code}}</strong></th>
            @endforeach
            <th width="80" align="center"><strong>Total</strong></th>
        </tr>
    </thead>
    <?php
        $i=1;
    ?>
    @foreach($acc as $accId=>$accName)
        @if($accTot2['amount'][$buyerId][$accId])
            <tr>
                <td width="20" align="center">{{$i}}</td>
                <td width="200" align="left">{{$accName}}</td>
                @foreach($comp2 as $key=>$company_code)
                <td width="80" align="right">{{number_format($data2[$buyerId][$accId][$key]['amount'],0)}}</td>
                @endforeach
                <td width="80" align="right">{{number_format($accTot2['amount'][$buyerId][$accId],0)}}</td>
            </tr>
            <?php
            $i++;
            ?>
        @endif
    @endforeach
</table>
@endforeach
<?php
$tReceivable=0;
?>
<table  border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td width="20" align="center"></td>
        <td width="200" align="left" colspan="2">Total</td>
        @foreach($comp2 as $key=>$company_code)
        <td width="80" align="right"><strong>{{number_format($comTot2['amount'][$key],0)}}</strong></td>
        
        @endforeach
        <td width="80" align="right"><strong>{{number_format(array_sum($comTot2['amount']),0)}}</strong></td>
    </tr>
    <tr>
        <td width="20" align="left"></td>
        <td width="200" align="left" colspan="2">Credit Limit</td>
        @foreach($comp2 as $key=>$company_code)
        <td width="80" align="right"></td>
        @endforeach
        <td width="80" align="right"><strong>{{number_format($buyerinfo->cr_limit_amt,0)}}</strong></td>
    </tr>
    <tr>
        <td width="20" align="left"></td>
        <td width="200" align="left" colspan="2">Receivable Over the Limit</td>
        @foreach($comp2 as $key=>$company_code)
        <td width="80" align="right"></td>
        <?php
           // $tReceivable+=$comp2['amount'][$key];
        ?>
        @endforeach
        <td width="80" align="right">{{ number_format($buyerinfo->cr_limit_amt-array_sum($comTot2['amount']),0) }}</td>
    </tr>
</table>
<p></p>
<p>For your kind information, this is ERP generated letter and ERP controls the credit limit as fixed up having your consent. If any query, your concerned person is most welcome to contact with our accounts.</p>
<p>We must co-operate each other and grow together.</p>
<p></p>
<p>Best Regards,</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p align="left">@if ($userinfo->signature_file)<img src="{{ $userinfo->signature_file }}" width="100", height="40"/><br>@endif
</p>
<p>{{ $userinfo->employee_name }}<br>{{ $userinfo->designation }}<br>{{ $userinfo->department }}<br></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p style="border: 1px solid black;margin: 3px;color:red;font-size:40px;" align="center" width="400px">If you have paid before getting this letter, please ignore it.</p>
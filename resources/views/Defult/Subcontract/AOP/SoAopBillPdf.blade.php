<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250">To,<br/>
        {{ $data['master']->buyer_name }}<br/>
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Bill No:<br/>
            Bill Date:<br/>
            Currency:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->issue_no }}<br/>
            {{ $data['master']->issue_date }}<br/>
            {{ $data['master']->currency_name }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">
            <br/>
            Remarks: {{ $data['master']->master_remarks}}
        </td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="60" align="center">Work Order No</td>
            <td width="110" align="center">Fabric Description</td>
            <td width="60" align="center">Impression</td>
            <td width="40" align="center">Cov. %</td>
            <td width="35" align="center">Fin. GSM</td>
            <td width="35" align="center">Fin. Dia</td>
            <td width="60" align="center">AOP Color</td>
            <td width="60" align="center">AOP Type</td>
            <td width="50" align="center">Design No</td>
            <td width="50" align="center">Design Name</td>
            <td width="30" align="center">UOM</td>
            <td width="60" align="center">Grey Used</td>
            <td width="50" align="center">Dlv. Qty</td>
            <td width="50" align="center">Rate</td>
            <td width="50" align="center">Amonut</td>
            <td width="50" align="center">No of Roll</td>
            <td width="56" align="left">Remarks</td>
        </tr>
    </thead>
    <tbody>
    <?php
    $qty=0;
    $no_of_roll=0;
    $amount=0;
    $grey_used=0;
    ?>
    @foreach($data['details'] as $row)
    <tr>
    <td width="30" align="center">{{ $i }}</td>
    <td width="60" align="center">{{$row->sales_order_no}}</td>
    <td width="110" align="left">{{$row->fabrication}},{{$row->fabriclooks}}, {{$row->fabricshape}}</td>
    <td width="60" align="center">{{$row->impression}}</td>
    <td width="40" align="center">{{$row->coverage}}</td>
    <td width="35" align="center">{{$row->fin_gsm}}</td>
    <td width="35" align="center">{{$row->fin_dia}}</td>
    <td width="60" align="center">{{$row->fabric_color}}</td>
    <td width="60" align="center">{{$row->aoptype}}</td>
    <td width="50" align="left">{{$row->design_no}}</td>
    <td width="50" align="left">{{$row->design_name}}</td>
    <td width="30" align="right">{{$row->uom_name}}</td>
    <td width="60" align="right">{{$row->grey_used}}</td>
    <td width="50" align="right">{{number_format($row->qty,2)}}</td>
    <td width="50" align="right">{{number_format($row->rate,4)}}<br>{{$row->bill_for}}</td>
    <td width="50" align="right">{{number_format($row->amount,2)}}</td>
    <td width="50" align="right">{{$row->no_of_roll}}</td>
    
    <td width="56" align="center"> {{$row->remarks}}</td>
    </tr>
    <?php
    $qty+=$row->qty;
    $no_of_roll+=$row->no_of_roll;
    $amount+=$row->amount;
    $grey_used+=$row->grey_used;
    $i++;
    ?>
    @endforeach
        
    <tr>
        <td width="200" align="right">Total</td>
        <td width="60" align="right"></td>
        <td width="40" align="right"></td>

        <td width="35" align="center"></td>
        <td width="35" align="center"></td>
        <td width="60" align="right"></td>
        <td width="60" align="right"></td>
        <td width="50" align="right"></td>
        <td width="50" align="right"></td>
        <td width="30" align="right"></td>
        <td width="60" align="right">{{number_format($grey_used,0)}}</td>
        <td width="50" align="right">{{number_format($qty,2)}}</td>
        <td width="50" align="right"></td>
        <td width="50" align="right">{{number_format($amount,2)}}</td>
        <td width="50" align="right">{{number_format($no_of_roll,0)}}</td>
        <td width="56" align="center"></td>
    </tr>
</tbody>
</table>
<p></p>
<p></p>
In Words: {{$data['master']->inword}}
<br/>
<br/>
Note:<br/>
Stock Security Amount: {{number_format($data['master']->fabrcvbal,0)}}<br/>
Receivable Amount: {{number_format($data['master']->receivable,0)}}<br/>
@if($data['master']->bal < 0)
<font style="color: #FF0000">Balance Amount: {{number_format($data['master']->bal,0)}}</font>
@else
<font>Balance Amount: {{number_format($data['master']->bal,0)}}</font>
@endif
<br/>
<br/>
<br/>
<table>
    <tr align="center">
        <td width="196">
            Prepared By
        </td>
        <td width="196">
           Marketing Manager 
        </td>
        <td width="196">
           Head of Department 
        </td>
        <td width="196">
          Audited By 
        </td>
        <td width="196">
           Head of Finance
        </td>
    </tr>
    <tr align="center">
        <td width="196">&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }},&nbsp;{{ $data['master']->created_at }}
        </td>
        <td width="196">
        </td>
        <td width="196">
        </td>
        
    </tr>
</table>
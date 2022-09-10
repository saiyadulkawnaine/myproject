<table cellspacing="0" cellpadding="2">
<tr>
<td width="438">
To,<br/>
{{ $purOrder['master']->supplier_name }}<br/>
{{ $purOrder['master']->supplier_address }}
</td>
<td width="120">
    <br/>
    Purchase Order No:<br/>
    Purchase Order Date:<br/>
    Delivery Start:<br/>
    Delivery End:<br/>
</td>
<td width="80">
    <br/>
     {{ $purOrder['master']->pur_order_no }}<br/>
     {{ $purOrder['master']->pur_order_date }}<br/>
     {{ $purOrder['master']->delv_start_date }}<br/>
     {{ $purOrder['master']->delv_end_date }}<br/>
</td>
</tr>
<tr>
    <td width="638">
        <br/>
        Remarks: {{ $purOrder['master']->remarks}}
    </td>
</tr>
</table>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="60" align="center">Style Ref</td>
            <td width="60" align="center">Sale Order No</td>
            <td width="100" align="center">Item description</td>

            <td width="50" align="center">Item Color</td>
            <td width="50" align="center">Item Size</td>
            <td width="50" align="center">GMT Color</td>
            <td width="50" align="center">GMT Size</td>

            <td width="65" align="center">Qty</td>
            <td width="30" align="center">UOM</td>
            <td width="30" align="center">Rate</td>
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $gamount=0;
        ?>
        @foreach($purOrder['details'] as $key=>$data)
        <tr>
            <td width="638"><strong>{{$key}}</strong></td>
        </tr>
        <?php
        $qty=0;
        $amount=0;
        ?>
        @foreach($data as $row)
       <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60">{{$row->style_ref}}</td>
            <td width="60">{{$row->sale_order_no}}</td>
            <td width="100"></td>

            <td width="50" align="center">{{$row->trim_color}}</td>
            <td width="50" align="center">{{$row->measurment}}</td>
            <td width="50" align="center">{{$row->gmt_color}}</td>
            <td width="50" align="center">{{$row->gmt_size}}</td>

            <td width="65" align="right">{{number_format($row->qty,2)}} </td>
            <td width="30" align="right">{{$row->uom_code}}</td>
            <td width="30" align="right">{{number_format($row->rate,2)}}</td>
            <td width="65" align="right">{{number_format($row->amount,2)}}</td>
        </tr>
        <?php
        $qty+=$row->qty;
        $amount+=$row->amount;
        $gamount+=$row->amount;
        ?>
        @endforeach 
        <tr>
            <td width="448" align="right"><strong>Item Total</strong></td>
            <td width="65" align="right">{{number_format($qty,2)}}</td>
            <td width="30" align="right"></td>
            <td width="30" align="right">{{number_format($amount/$qty,2)}}</td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
         <tr>
            <td width="573" align="right"><strong>Grand Total</strong></td>
            <td width="65" align="right">{{number_format($gamount,2)}}</td>
        </tr>
        
    </tfoot>
    
</table>
<br/>
<strong>In Words : {{ $purOrder['master']->inword }}.</strong>
<br/>
<br/>
<table>
    <tr>
    <td width="638">
    <strong>Terms & Conditions:</strong>
    </td>
    </tr>
    <?php
    $i=1;
    ?>
@foreach($purOrder['purchasetermscondition'] as $terms)
    <tr>
        <td width="38">
            {{$i}}.
        </td>
        <td width="600">
            <strong>{{$terms->term}}</strong>
        </td>
    </tr>
    <?php
    $i++;
    ?>
@endforeach
</table>
<br/>
<br/>
<br/>
<br/>
<table>

<tr align="center">
    <td width="107">
        Prepared By
    </td>
    <td width="107">
        Department Manager
    </td>
    <td width="106">
        GM Marketing
    </td>
    <td width="106">
        GM Finance & Accounts
    </td>
    <td width="106">
        Director/C.O.O
    </td>
    <td width="106">
        Managing Director
    </td>
</tr>
<tr align="center">
    <td width="107">
        {{$purOrder['master']->user_name}}
    </td>
    <td width="107">
    </td>
    <td width="106">
    </td>
    <td width="106">
    </td>
    <td width="106">
    </td>
    <td width="106">
    </td>
</tr>
</table>




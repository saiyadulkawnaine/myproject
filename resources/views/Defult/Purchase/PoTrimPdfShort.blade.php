<table cellspacing="0" cellpadding="2">
<tr>
<td width="710">
To,<br/>
{{ $purOrder['master']->supplier_name }}<br/>
{{ $purOrder['master']->supplier_address }}
</td>
<td width="120">
    <br/>
    PO No:<br/>
    PO Date:<br/>
    Delivery Start:<br/>
    Delivery End:
</td>
<td width="80">
    <br/>
     {{ $purOrder['master']->po_no }}<br/>
     {{ $purOrder['master']->po_date }}<br/>
     {{ $purOrder['master']->delv_start_date }}<br/>
     {{ $purOrder['master']->delv_end_date }}
</td>
</tr>
<tr>
    <td width="638">Remarks: {{ $purOrder['master']->remarks}}
    </td>
</tr>
</table>
<br/>
@foreach($purOrder['sizesensivitarray'] as $key=>$data)
<strong>{{$key}}</strong>
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
            <td width="30" align="center">UOM</td>
            
            @foreach($sizearray[$key] as $size=>$size_value)
             <td width="50" align="center">{{$size_value}}</td>
            @endforeach
            <td width="50" align="center">Total Qty</td>
            <td width="30" align="center">Rate</td>
            
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        
        
        <?php
        $total_amount=0;
        ?>
        @foreach($data as $row)
        <?php
        $amount=array_sum($row['amount']);
        $total_amount+=$amount;
        ?>
       <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60">{{$row['style_ref']}}, {{ $row['buyer_name'] }}</td>
            <td width="60">{{$row['sale_order_no']}}, {{ $row['company_name'] }}</td>
            <td width="100">{{$row['description']}}</td>

            <td width="50" align="center">{{$row['trim_color']}}</td>
            <td width="50" align="center">{{$row['measurment']}}</td>
            <td width="30" align="right">{{$row['uom_code']}}</td>
            <?php
            $row_total=0;
            ?>
            @foreach($sizearray[$key] as $size=>$size_value)
            <?php
            $qty=isset($row['qty'][$size_value])?$row['qty'][$size_value]:0;
            $row_total+=$qty;
            if($qty){
               $qtyformat=number_format($qty,2); 
            }
            else{
                $qtyformat='';
            }
            ?>
             <td width="50" align="right">{{$qtyformat}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right">{{number_format($row['rate'],2)}}</td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
        @endforeach 
        <tr>
            <td align="right" width="378">Total</td>
            
            <?php
            $row_total=0;
            ?>
            @foreach($sizearray[$key] as $size=>$size_value)
            <?php
            $column_total=array_sum($sizetotalarray[$key][$size_value]);
            $row_total+=$column_total;
            ?>
             <td width="50" align="right">{{number_format($column_total,2)}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right"></td>
            <td width="65" align="right">{{number_format($total_amount,2)}}</td>
        </tr>
    </tbody>
</table>
@endforeach

<br/>

@foreach($purOrder['colorsensivitarray'] as $key=>$data)
<strong>{{$key}}</strong>
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
            <td width="30" align="center">UOM</td>
            
            @foreach($colorarray[$key] as $color=>$color_value)
             <td width="50" align="center">{{$color_value}}</td>
            @endforeach
            <td width="50" align="center">Total Qty</td>
            <td width="30" align="center">Rate</td>
            
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        
        
        <?php
        $total_amount=0;
        ?>
        @foreach($data as $row)
        <?php
        $amount=array_sum($row['amount']);
        $total_amount+=$amount;
        ?>
       <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60">{{$row['style_ref']}}, {{ $row['buyer_name'] }}</td>
            <td width="60">{{$row['sale_order_no']}}, {{ $row['company_name'] }}</td>
            <td width="100">{{$row['description']}}</td>

            <td width="50" align="center">{{$row['trim_color']}}</td>
            <td width="50" align="center">{{$row['measurment']}}</td>
            <td width="30" align="right">{{$row['uom_code']}}</td>
            <?php
            $row_total=0;
            ?>
            @foreach($colorarray[$key] as $color=>$color_value)
            <?php
            $qty=isset($row['qty'][$color_value])?$row['qty'][$color_value]:0;
            $row_total+=$qty;
            if($qty){
               $qtyformat=number_format($qty,2); 
            }
            else{
                $qtyformat='';
            }
            ?>
             <td width="50" align="right">{{$qtyformat}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right">{{$row['rate']}}</td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
        @endforeach 
        <tr>
            <td align="right" width="378">Total</td>
            
            <?php
            $row_total=0;
            ?>
            @foreach($colorarray[$key] as $color=>$color_value)
            <?php
            $column_total=array_sum($colortotalarray[$key][$color_value]);
            $row_total+=$column_total;
            ?>
             <td width="50" align="right">{{number_format($column_total,2)}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right"></td>
            <td width="65" align="right">{{number_format($total_amount,2)}}</td>
        </tr>
    </tbody>
</table>
@endforeach

<br/>
@if(count($purOrder['nosensivits']))
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="60" align="center">Style Ref</td>
            <td width="60" align="center">Sale Order No</td>
            <td width="100" align="center">Item description</td>
            <td width="50" align="center">Item Color</td>
            <td width="50" align="center">Item Size</td>
            
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
        @foreach($purOrder['nosensivits'] as $key=>$data)
        <tr>
            <td width="538"><strong>{{$key}}</strong></td>
        </tr>
        <?php
        $qty=0;
        $amount=0;
        ?>
        @foreach($data as $row)
       <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60">{{$row->style_ref}}, {{ $row->buyer_name }}</td>
            <td width="60">{{$row->sale_order_no}},{{ $row->company_name}}</td>
            <td width="100">{{$row->description}}</td>

            <td width="50" align="center">{{$row->trim_color}}</td>
            <td width="50" align="center">{{$row->measurment}}</td>
            

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
            <td width="348" align="right"><strong>Item Total</strong></td>
            <td width="65" align="right">{{number_format($qty,2)}}</td>
            <td width="30" align="right"></td>
            <td width="30" align="right">{{number_format($amount/$qty,2)}}</td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    
    <tfoot>
         <tr>
            <td width="473" align="right"><strong>Total</strong></td>
            <td width="65" align="right">{{number_format($gamount,2)}}</td>
        </tr>
        
    </tfoot>
    
</table>
@endif
<br/>


<br/>

@foreach($purOrder['colorsizesensivitarray'] as $key=>$data)
<strong>{{$key}}</strong>
<br/>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="28" align="center">#</td>
            <td width="60" align="center">Style Ref</td>
            <td width="60" align="center">Sale Order No</td>
            <td width="100" align="center">Item description</td>
            <td width="50" align="center">GMT Color</td>
            <td width="50" align="center">Item Color</td>
            <td width="50" align="center">Item Size</td>
            <td width="30" align="center">UOM</td>
            
            @foreach($colorsizearray[$key] as $color=>$color_value)
             <td width="50" align="center">{{$color_value}}</td>
            @endforeach
            <td width="50" align="center">Total Qty</td>
            <td width="30" align="center">Rate</td>
            
            <td width="65" align="center">Amount {{ $purOrder['master']->currency_name}}</td>
        </tr>
    </thead>
    <tbody>
        
        
        <?php
        $total_amount=0;
        ?>
        @foreach($data as $row)
        <?php
        $amount=array_sum($row['amount']);
        $total_amount+=$amount;
        ?>
       <tr>
            <td width="28" align="center">{{ $loop->iteration }}</td>
            <td width="60">{{$row['style_ref']}}, {{ $row['buyer_name'] }}</td>
            <td width="60">{{$row['sale_order_no']}}, {{ $row['company_name'] }}</td>
            <td width="100">{{$row['description']}}</td>
            <td width="50" align="center">{{$row['gmt_color']}}</td>

            <td width="50" align="center">{{$row['trim_color']}}</td>
            <td width="50" align="center">{{$row['measurment']}}</td>
            <td width="30" align="right">{{$row['uom_code']}}</td>
            <?php
            $row_total=0;
            ?>
            @foreach($colorsizearray[$key] as $color=>$color_value)
            <?php
            $qty=isset($row['qty'][$color_value])?$row['qty'][$color_value]:0;
            $row_total+=$qty;
            if($qty){
               $qtyformat=number_format($qty,2); 
            }
            else{
                $qtyformat='';
            }
            ?>
             <td width="50" align="right">{{$qtyformat}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right">{{$row['rate']}}</td>
            <td width="65" align="right">{{number_format($amount,2)}}</td>
        </tr>
        @endforeach 
        <tr>
            <td align="right" width="428">Total</td>
            
            <?php
            $row_total=0;
            ?>
            @foreach($colorsizearray[$key] as $color=>$color_value)
            <?php
            $column_total=array_sum($colorsizetotalarray[$key][$color_value]);
            $row_total+=$column_total;
            ?>
             <td width="50" align="right">{{number_format($column_total,2)}}</td>
            @endforeach
            <td width="50" align="right">{{number_format($row_total,2)}}</td>
            <td width="30" align="right"></td>
            <td width="65" align="right">{{number_format($total_amount,2)}}</td>
        </tr>
    </tbody>
</table>
@endforeach
<br/>
<strong>Grand Total ( {{ $purOrder['master']->currency_name}} ) : {{ number_format($purOrder['master']->amount,2) }} </strong><br/>
<strong>In Words : {{ $purOrder['master']->inword }}.</strong>
<br/>
<table>
        <tr>
        <td width="948">
        <strong>Terms & Conditions:</strong>
        </td>
        </tr>
        <?php
        $i=1;
        ?>
    @foreach($purOrder['purchasetermscondition'] as $terms)
        <tr>
            <td width="48">
                {{$i}}.
            </td>
            <td width="900">
                <strong>{{$terms->term}}</strong>
            </td>
        </tr>
        <?php
        $i++;
        ?>
    @endforeach
</table>
<br/>
@if (!$purOrder['master']->approved_by)
    <h3 align="right" style="font-stretch: ultra-expanded">UNAPPROVED&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h3>
@endif
<table>
    <tr align="center">
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158"></td>
        <td width="158">@if ($purOrder['master']->approved_user_signature)<img src="{{ $purOrder['master']->approved_user_signature }}" width="100", height="40"/>
            @endif</td>
    </tr>
    <tr align="center">
        <td width="158">
            Prepared By
        </td>
        <td width="158">
            Department Manager
        </td>
        <td width="158">
            GM Marketing
        </td>
        <td width="158">
            GM Finance & Accounts
        </td>
        <td width="158">
            Director/C.O.O
        </td>
        <td width="158">
            Approved By
        </td>
    </tr>
    <tr align="center">
        <td width="158">
            {{$purOrder['master']->user_name}}
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">
        </td>
        <td width="158">{{ $purOrder['master']->approval_emp_name }}<br/>
            {{ $purOrder['master']->approval_emp_contact }}<br/>
            {{ $purOrder['master']->approval_emp_designation }}<br/>
            {{ $purOrder['master']->approved_at }}
        </td>
    </tr>
</table>




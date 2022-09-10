<?php
    $i=1;
    
?>

<p>Received Trim Details</p>
<table border="1" width="1600px">
    <tr>
        
        
        
        <th width="100px"  class="text-center">Sales Order No</th>
        <th width="100px"  class="text-center">GMT Color</th>

        <th width="100px"  class="text-center">GMT Size</th>
        <th width="100px"  class="text-center">Item Class</th>
        <th width="250px"  class="text-center">Description</th>
        <th width="100px"  class="text-center">Item Color</th>
        <th width="100px"  class="text-center">Item Size</th>
        <th width="50px"  class="text-center">PO NO</th>
        <th width="50px"  class="text-center">PI NO</th>
        <th width="50px"  class="text-center">PO Qty</th>
        <th width="50px"  class="text-center">UOM</th>
        <th width="50px"  class="text-center">PO Rate</th>

        <th width="50px"  class="text-center">PO Amount</th>
        <th width="50px"  class="text-center">Currency</th>
        <th width="50px"  class="text-center">Exch.Rate</th>
        <th width="80px"  class="text-center">Tot.Rcv Qty</th>
        <th width="100px"  class="text-center">Store Name</th>
        
        <th width="80px"  class="text-center">Rcv Qty</th>
        <th width="80px"  class="text-center">Rcv Rate</th>
        <th width="80px"  class="text-center">Rcv Amount</th>
        <th width="80px"  class="text-center">Room</th>
        <th width="80px"  class="text-center">Rack</th>
        <th width="80px"  class="text-center">Shelf</th>
        <th width="80px"  class="text-center">Remarks</th>
    </tr>
    <tbody>    
        @foreach($rows as $row)
         <tr align="center">
           
            <td align="left">
                {{ $row->sale_order_no }}
                <input type="hidden" name="po_trim_item_report_id[{{ $i }}]" id="po_trim_item_report_id{{ $i }}" value="{{ $row->po_trim_item_report_id }}"/>
            </td>
            <td>{{ $row->style_color_name }}</td>
            <td>
            {{ $row->style_size_name }}
            </td>
             <td>
                {{ $row->class_name }}
                
                <input type="hidden" name="itemclass_id[{{ $i }}]" id="itemclass_id{{ $i }}" value="{{ $row->itemclass_id }}"/>
            </td>
            <td>
            {{ $row->description }}
            <input type="hidden" name="description[{{ $i }}]" id="description{{ $i }}" value="{{$row->description}}" />
            </td>
            <td>
                {{$row->item_color_name}}
                <input type="hidden" name="trim_color_id[{{ $i }}]" id="trim_color_id{{ $i }}" value="{{$row->trim_color_id}}" />
            </td>
            <td align="right">
                {{$row->measurment}}
                <input type="hidden" name="measurment[{{ $i }}]" id="measurment{{ $i }}" value="{{$row->measurment}}"/>
            </td>
            <td align="right">
                {{$row->po_no}}
            </td>
            
            <td align="right">
                {{$row->pi_no}}
            </td>
            <td align="right">
                {{$row->po_qty}}
            </td>
            <td>
                {{$row->uom_name}}
            </td>

            <td align="right">
                {{$row->po_rate}}
            </td>
            
            <td align="right">
                {{$row->po_amount}}
            </td>
            <td>
                {{$row->currency_code}}
            </td>
            <td align="right">
                <input type="text" name="exch_rate[{{ $i }}]" id="exch_rate{{ $i }}" value="{{$row->exch_rate}}" class="number integer" readonly />
            </td>
            <td align="right">
                {{$row->cu_qty}}
            </td>
            <td>
            {!! Form::select("store_id[$i]", $store,'',array('id'=>'store_id','onchange'=>"MsInvTrimRcvItem.copyStore(this.value, $i , $loop->count)")) !!}
            
            </td>
             
            <td>
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" class="number integer" value="{{$row->bal_qty}}" onchange="MsInvTrimRcvItem.calculate_qty({{ $i }},{{$loop->count}})"/>
            </td>
            <td>
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" class="number integer" value="{{$row->po_rate}}" readonly/>
            </td>
            <td>
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" class="number integer" value="{{$row->bal_amount}}" readonly />
            </td>
            <td>
                <input type="text" name="room[{{ $i }}]" id="room{{ $i }}" onchange="MsInvTrimRcvItem.copyRoom(this.value,{{ $i }},{{ $loop->count}})" value="" />
            </td>
            <td>
                <input type="text" name="rack[{{ $i }}]" id="rack{{ $i }}" onchange="MsInvTrimRcvItem.copyRack(this.value,{{ $i }},{{ $loop->count}})" value="" />
            </td>
            <td>
                <input type="text" name="shelf[{{ $i }}]" id="shelf{{ $i }}" onchange="MsInvTrimRcvItem.copyShelf(this.value,{{ $i }},{{ $loop->count}})" value="" />
            </td>
            <td>
                <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" onchange="MsInvTrimRcvItem.copyRemarks(this.value,{{ $i }},{{ $loop->count}})" value="" />
            </td>
        </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>



<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>

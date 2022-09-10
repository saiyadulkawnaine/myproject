<?php
    $i=1;
    if($receive_against_id==9){
       $readonly='readonly'; 
    }
    else{
       $readonly=''; 
    }
?>

<p>Received Yarn Details</p>
<table border="1" width="1600px">
    <tr>
        <th width="100px"  class="text-center">Store Name</th>
        <th width="80px"  class="text-center">Yarn <br/>Class</th>
        <th width="50px"  class="text-center">Count</th>
        <th width="300px"  class="text-center">Composition</th>
        <th width="50px"  class="text-center">Type</th>

        <th width="50px"  class="text-center">Color</th>
        <th width="50px"  class="text-center">Lot/<br/> Batch</th>
        <th width="50px"  class="text-center">Brand</th>
        <th width="50px"  class="text-center">Cone <br/>/ Bag</th>
        <th width="50px"  class="text-center">Wgt.<br/>/Cone</th>
        <th width="50px"  class="text-center">No. of<br/> Bag</th>
        <th width="50px"  class="text-center">Recv.<br/> Qty</th>
        <th width="50px"  class="text-center">UOM</th>
        <th width="50px"  class="text-center">Rate</th>

        <th width="50px"  class="text-center">Amount</th>
        <th width="50px"  class="text-center">Currency</th>
        <th width="50px"  class="text-center">Exch.Rate</th>
        <th width="50px"  class="text-center">Used Yarn</th>
        <th width="50px"  class="text-center">ILE%</th>
        <th width="50px"  class="text-center">ILE<br/>Cost</th>
        
        <th width="80px"  class="text-center">Room</th>
        <th width="80px"  class="text-center">Rack</th>
        <th width="80px"  class="text-center">Shelf</th>
        <th width="80px"  class="text-center">Remarks</th>
    </tr>
    <tbody>    
        @foreach($poyarn as $row)
         <tr align="center">
            <td>
            {!! Form::select("store_id[$i]", $store,'',array('id'=>'store_id')) !!}
            <input type="hidden" name="po_yarn_item_id[{{ $i }}]" id="po_yarn_item_id{{ $i }}" value="{{ $row->po_yarn_item_id }}"/>
            <input type="hidden" name="inv_yarn_isu_item_id[{{ $i }}]" id="inv_yarn_isu_item_id{{ $i }}" value="{{ $row->inv_yarn_isu_item_id }}"/>
            <input type="hidden" name="yarn_dyeing_rate[{{ $i }}]" id="yarn_dyeing_rate{{ $i }}" value="{{ $row->po_yarn_dyeing_item_rate }}"/>
            <input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" value="{{ $row->item_account_id }}"/>
            </td>
            <td>{{ $row->itemclass_name }}</td>
            <td>{{ $row->yarn_count }}</td>
            <td align="left">{{ $row->composition }}</td>
            <td>{{ $row->yarn_type }}</td>
            <td>
            <input type="text" name="color_id[{{ $i }}]" id="color_id{{ $i }}" value="{{ $row->yarn_color }}" style="text-transform: uppercase;" {{$readonly}} />
            </td>
            <td>
            <input type="text" name="lot[{{ $i }}]" id="lot{{ $i }}" style="text-transform: uppercase;"/>
            </td>
            <td>
                <input type="text" name="brand[{{ $i }}]" id="brand{{ $i }}" style="text-transform: uppercase;"/>
            </td>
            <td align="right">
                <input type="text" name="cone_per_bag[{{ $i }}]" id="cone_per_bag{{ $i }}" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty({{ $i }},{{$loop->count}})" />
            </td>
            <td align="right">
                <input type="text" name="wgt_per_cone[{{ $i }}]" id="wgt_per_cone{{ $i }}" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty({{ $i }},{{$loop->count}})"/>
            </td>
            
            <td align="right">
                <input type="text" name="no_of_bag[{{ $i }}]" id="no_of_bag{{ $i }}" value="" class="number integer" onchange="MsInvYarnRcvItem.calculate_qty({{ $i }},{{$loop->count}})"/>
            </td>
            <td align="right">
                <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="" class="number integer" readonly />
            </td>
            <td>
                {{$row->uom_code}}
            </td>

            <td align="right">
                <input type="text" name="rate[{{ $i }}]" id="rate{{ $i }}" value="{{ $row->rate }}" class="number integer" readonly />
            </td>
            
            <td align="right">
                <input type="text" name="amount[{{ $i }}]" id="amount{{ $i }}" value="" class="number integer" readonly />
            </td>
            <td>
                {{$row->currency_code}}
            </td>
            <td align="right">
                <input type="text" name="exch_rate[{{ $i }}]" id="exch_rate{{ $i }}" value="{{$row->exch_rate}}" class="number integer" readonly />
            </td>
             <td align="right">
                <input type="text" name="used_yarn[{{ $i }}]" id="used_yarn{{ $i }}" value="{{$row->used_yarn}}" class="number integer" readonly />
            </td>
            <td align="right">
            <input type="text" name="ile_percent[{{ $i }}]" id="ile_percent[{{ $i }}]">
            </td>
            <td>
                <input type="text" name="ile_cost_poc[{{ $i }}]" id="ile_cost_poc{{ $i }}" value="" class="number integer"/>

                <input type="hidden" name="ile_cost_stc[{{ $i }}]" id="ile_cost_stc{{ $i }}" value=""/>
                <input type="hidden" name="rate_stc[{{ $i }}]" id="rate_stc{{ $i }}" value=""/>
                <input type="hidden" name="stc_currency_rate[{{ $i }}]" id="stc_currency_rate{{ $i }}" value=""/>

            </td>
            
            <td>
                <input type="text" name="room[{{ $i }}]" id="room{{ $i }}" value="" />
            </td>
            <td>
                <input type="text" name="rack[{{ $i }}]" id="rack{{ $i }}" value="" />
            </td>
            <td>
                <input type="text" name="shelf[{{ $i }}]" id="shelf{{ $i }}" value="" />
            </td>
            <td>
                <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="" />
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

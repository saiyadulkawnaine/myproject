<p>Transfer In Finish Details</p>
<table border="1" width="1700px">
    <tr>
        <th width="100px"  class="text-center">Roll No</th>
        <th width="80px"  class="text-center">Custom No</th>
        <th width="50px"  class="text-center">Rcv. Qty</th>
        <th width="100px"  class="text-center">Store</th>
        <th width="50px"  class="text-center">Room</th>
        <th width="50px"  class="text-center">Rack</th>
        <th width="50px"  class="text-center">Shelf</th>

        <th width="50px"  class="text-center">Body Part</th>
        <th width="200px"  class="text-center">Fabric <br/>Description</th>
        <th width="80px"  class="text-center">Fabric <br/>Shape</th>
        <th width="80px"  class="text-center">Fabric <br/>Look</th>
        <th width="50px"  class="text-center">Fabric Color</th>
        <th width="50px"  class="text-center">GSM</th>
        <th width="50px"  class="text-center">Dia/<br/>Widht</th>
        <th width="50px"  class="text-center">Measurement</th>
        <th width="50px"  class="text-center">Roll Length</th>
        <th width="50px"  class="text-center">Stitch<br/>Length</th>

        <th width="50px"  class="text-center">Shrink %</th>
        
        <th width="50px"  class="text-center">PCS</th>
        <th width="150px"  class="text-center">Order No</th>
        <th width="150px"  class="text-center">Style Ref</th>
        <th width="200px"  class="text-center">Buyer Name</th>
        <th width="200px"  class="text-center">Remarks</th>
        
    </tr>
    <tbody>    
        <?php
            $i=1;
        ?>
        @foreach($prodknitqc as $row)
         <tr align="center">
            <td>
            {{ $row->id }}
            <input type="hidden" name="inv_finish_fab_isu_item_id[{{ $i }}]" id="inv_finish_fab_isu_item_id{{ $i }}" value="{{ $row->id }}" readonly />
            <input type="hidden" name="prod_finish_dlv_roll_id[{{ $i }}]" id="prod_finish_dlv_roll_id{{ $i }}" value="{{ $row->prod_finish_dlv_roll_id }}" readonly />
            
            <input type="hidden" name="inv_finish_fab_item_id[{{ $i }}]" id="inv_finish_fab_item_id{{ $i }}" value="{{ $row->inv_finish_fab_item_id }}" readonly />
            
            </td>
            <td>
            <input type="text" name="custom_no[{{ $i }}]" id="custom_no{{ $i }}" value="{{ $row->custom_no }}" readonly  />
            </td>
            <td>
            <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $row->isu_qty }}" readonly />
            </td>

            <td align="left">
            {!! Form::select("store_id[$i]",$store,$row->store_id,array('id'=>'store_id',"onchange"=>"MsInvFinishFabTransInItem.copyStore(this.value,$i,$loop->count)")) !!}
            </td>

            <td align="left">
            <input type="text" name="room[{{ $i }}]" id="room{{ $i }}" onchange="MsInvFinishFabTransInItem.copyRoom(this.value,{{ $i }},{{ $loop->count}})"/>
            </td>

            <td align="left">
            <input type="text" name="rack[{{ $i }}]" id="rack{{ $i }}" onchange="MsInvFinishFabTransInItem.copyRack(this.value,{{ $i }},{{ $loop->count}})"/>
            </td>
            <td>
            <input type="text" name="shelf[{{ $i }}]" id="shelf{{ $i }}" onchange="MsInvFinishFabTransInItem.copyShelf(this.value,{{ $i }},{{ $loop->count}})"/>            
            </td>
            <td>
            {{ $row->body_part }}
            <input type="hidden" name="gmtspart_id[{{ $i }}]" id="gmtspart_id{{ $i }}" value="{{ $row->gmtspart_id }}" readonly/> 
            </td>
            <td>
            {{ $row->fabrication }}
            <input type="hidden" name="autoyarn_id[{{ $i }}]" id="autoyarn_id{{ $i }}" value="{{ $row->autoyarn_id }}" readonly/>
            </td>
            <td>
            {{ $row->fabric_shape }}
            <input type="hidden" name="fabric_shape_id[{{ $i }}]" id="fabric_shape_id{{ $i }}" value="{{ $row->fabric_shape_id }}" readonly/>
            </td>
            <td align="right">
            {{ $row->fabric_look }}   
            <input type="hidden" name="fabric_look_id[{{ $i }}]" id="fabric_look_id{{ $i }}" value="{{ $row->fabric_look_id }}" readonly/>         
            </td>
            <td align="right">
                {{$row->fabric_color_name}}
            </td>
            <td align="right">
            <input type="text" name="gsm_weight[{{ $i }}]" id="gsm_weight{{ $i }}" value="{{ $row->gsm_weight }}" readonly/>
            </td>
            
            <td align="right">
            <input type="text" name="dia[{{ $i }}]" id="dia{{ $i }}" value="{{ $row->dia_width }}" readonly/>
            </td>
            <td align="right">
            <input type="text" name="measurment[{{ $i }}]" id="measurment{{ $i }}" value="{{ $row->measurement }}" readonly/>

            </td>
            <td>
            <input type="text" name="roll_length[{{ $i }}]" id="roll_length{{ $i }}" value="{{ $row->roll_length }}" readonly/>
            </td>

            <td align="right">
            <input type="text" name="stitch_length[{{ $i }}]" id="stitch_length{{ $i }}" value="{{ $row->stitch_length }}" readonly/>
            </td>
            
            <td align="right">
            <input type="text" name="shrink_per[{{ $i }}]" id="shrink_per{{ $i }}" value="{{ $row->shrink_per }}" readonly/>
            </td>
            
            
             <td align="right">
                {{$row->qty_pcs}}
            </td>
            <td align="right">
            {{$row->sale_order_no}}
            </td>
            <td>
                {{$row->style_ref}}

            </td>
            
            <td>
                {{$row->buyer_name}}
            </td>
            <td>
            <input type="hidden" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="{{ $row->remarks }}" />
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

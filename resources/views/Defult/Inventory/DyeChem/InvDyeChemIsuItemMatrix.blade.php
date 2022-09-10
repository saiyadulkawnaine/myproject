<table border="1" width="1230px" id="invdyechemisuitemmatrixtbl">
    <tr>
        <th class="text-center" width="100"></th>
        <th class="text-center" width="150">Sub Process</th>
        <th class="text-center" width="100">Item Category</th>
        <th class="text-center" width="100">Item Class</th>
        <th class="text-center" width="200">Description</th>
        <th class="text-center" width="100">Store</th>
        <th class="text-center" width="70" align="right">Item Batch No
</th>
        <th class="text-center" width="150">Ratio</th>
        <th class="text-center" width="70" align="right">Qty</th>
        <th class="text-center" width="60">UOM</th>
        <th class="text-center" width="60">Sequence</th>
        <th class="text-center" width="70" align="right">Remarks</th>
        <th class="text-center" width="70" align="right">Room</th>
        <th class="text-center" width="100">Rack</th>
        <th class="text-center" width="100">Shelf</th>
    </tr>
    <tbody> 
        <?php
        $i=1;
        ?>   
        @foreach($rows as $row)
         <tr align="center">
            <td>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsInvDyeChemIsuItem.submitMartix({{ $i }})">Save</a>
            </td>
            <td>
            {{ $row->sub_process }}
            <input type="hidden" name="inv_dye_chem_isu_rq_item_id[{{ $i }}]" id="inv_dye_chem_isu_rq_item_id{{ $i }}" value="{{ $row->inv_dye_chem_isu_rq_item_id }}"/>
            <input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" value="{{ $row->item_account_id }}"/>
            <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}" value=""/>
            </td>
            
            <td>{{ $row->category_name }}</td>
            <td>{{ $row->class_name }}</td>
            <td align="left">{{ $row->item_desc }}</td>
            <td align="left">{!! Form::select("store_id[$i]", $store,'',array('id'=>'store_id','onchange'=>"MsInvDyeChemIsuItem.copyStore($i,$loop->count)")) !!}</td>
            <td align="left"><input type="text" name="batch[{{ $i }}]" id="batch{{ $i }}"   /></td>
            <td>{{ $row->ratio }}</td>
            <td>
            <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $row->qty }}" class="number integer" readonly />
            </td>
            <td>
            {{$row->uom_name}}
            </td>
            <td>
            {{ $row->sort_id }}
            </td>
            <td>
            <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="{{ $row->remarks }}"  />            
            </td>
            <td>
            <input type="text" name="room[{{ $i }}]" id="room{{ $i }}"/>
            </td>
            
            <td>
            <input type="text" name="rack[{{ $i }}]" id="rack{{ $i }}"/>            
            </td>
            <td>
            <input type="text" name="shelf[{{ $i }}]" id="shelf{{ $i }}"/>            
            </td>
        </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>
<p></p>




<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>

<p>All Item</p>
<table border="1" width="1030px">
    <tr>
        <th class="text-center" width="100">ID</th>
        <th class="text-center" width="150">Sub Process</th>
        <th class="text-center" width="60">Sequence</th>
        <th class="text-center" width="100">Item Category</th>
        <th class="text-center" width="100">Item Class</th>
        <th class="text-center" width="200">Description</th>
        <th class="text-center" width="150">Ratio</th>
        <th class="text-center" width="70" align="right">1st Qty</th>
        <th class="text-center" width="70" align="right">Add %</th>
        <th class="text-center" width="70" align="right">Add Qty</th>
        <th class="text-center" width="60">UOM</th>
        <th class="text-center" width="100">Remarks</th>
    </tr>
    <tbody> 
        <?php
        $i=1;
        ?>   
        @foreach($rows as $row)
         <tr align="center">
            <td>
            {{ $row->id }}
            <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}" value=""/>
            <input type="hidden" name="root_item_id[{{ $i }}]" id="root_item_id{{ $i }}" value="{{ $row->id }}"/>
            </td>
            <td>
            {{ $row->sub_process_name }}
            <input type="hidden" name="inv_dye_chem_isu_rq_id[{{ $i }}]" id="inv_dye_chem_isu_rq_id{{ $i }}" value="{{ $row->inv_dye_chem_isu_rq_id }}"/>
            <input type="hidden" name="root_item_id[{{ $i }}]" id="root_item_id{{ $i }}" value="{{ $row->id }}"/>
            <input type="hidden" name="sub_process_id[{{ $i }}]" id="sub_process_id{{ $i }}" value="{{ $row->sub_process_id }}"/>
            <input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" value="{{ $row->item_account_id }}"/>
            </td>
            <td>
            <input type="hidden" name="sort_id[{{ $i }}]" id="sort_id{{ $i }}" value="{{ $row->sort_id }}" class="number integer" readonly />
            {{ $row->sort_id }}
            </td>
            <td>{{ $row->category_name }}</td>
            <td>{{ $row->class_name }}</td>
            <td align="left">{{ $row->item_description }}</td>
            <td>{{ $row->ratio }}</td>
            <td>
            <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $row->qty }}" class="number integer" readonly />
            </td>
            
            <td>
            <input type="text" name="add_per[{{ $i }}]" id="add_per{{ $i }}" class="number integer" onchange="MsInvDyeChemIsuRqItemAdd.calculate_add_qty({{ $i }},{{$loop->count}})" />
            </td>
            <td>
            <input type="text" name="add_qty[{{ $i }}]" id="add_qty{{ $i }}" class="number integer" readonly />
            </td>
            <td>
                {{$row->uom_name}}
            </td>
            <td>
            <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="{{ $row->remarks }}"  />            
            </td>
        </tr>
        <?php
            $i++;
        ?>
        @endforeach
    </tbody>
</table>
<p></p>
<p>Saved Item</p>
<table border="1" width="1030px">
    <tr>
        <th class="text-center" width="100">ID</th>
        <th class="text-center" width="150">Sub Process</th>
        <th class="text-center" width="60">Sequence</th>
        <th class="text-center" width="100">Item Category</th>
        <th class="text-center" width="100">Item Class</th>
        <th class="text-center" width="200">Description</th>
        <th class="text-center" width="150">Ratio</th>
        <th class="text-center" width="70" align="right">1st Qty</th>
        <th class="text-center" width="70" align="right">Add %</th>
        <th class="text-center" width="70" align="right">Add Qty</th>
        <th class="text-center" width="60">UOM</th>
        <th class="text-center" width="100">Remarks</th>
    </tr>
    <tbody> 
        <?php
        //$i=1;
        ?>   
        @foreach($saved as $row)
         <tr align="center">
            <td>
            {{ $row->id }}
            <input type="hidden" name="id[{{ $i }}]" id="id{{ $i }}" value="{{ $row->id }}"/>
            <input type="hidden" name="root_item_id[{{ $i }}]" id="root_item_id{{ $i }}" value="{{ $row->root_item_id }}"/>
            </td>
            <td>
            {{ $row->sub_process_name }}
            <input type="hidden" name="inv_dye_chem_isu_rq_id[{{ $i }}]" id="inv_dye_chem_isu_rq_id{{ $i }}" value="{{ $row->inv_dye_chem_isu_rq_id }}"/>
            <input type="hidden" name="sub_process_id[{{ $i }}]" id="sub_process_id{{ $i }}" value="{{ $row->sub_process_id }}"/>
            <input type="hidden" name="item_account_id[{{ $i }}]" id="item_account_id{{ $i }}" value="{{ $row->item_account_id }}"/>
            </td>
            <td>
            <input type="hidden" name="sort_id[{{ $i }}]" id="sort_id{{ $i }}" value="{{ $row->sort_id }}" class="number integer" readonly />
            {{ $row->sort_id }}
            </td>
            <td>{{ $row->category_name }}</td>
            <td>{{ $row->class_name }}</td>
            <td align="left">{{ $row->item_description }}</td>
            <td>{{ $row->ratio }}</td>
            <td>
            <input type="text" name="qty[{{ $i }}]" id="qty{{ $i }}" value="{{ $row->first_qty }}" class="number integer" readonly />
            </td>
            
            <td>
            <input type="text" name="add_per[{{ $i }}]" id="add_per{{ $i }}" value="{{ $row->add_per }}" class="number integer" onchange="MsInvDyeChemIsuRqItemAdd.calculate_add_qty({{ $i }} , {{$loop->count}})" />
            </td>
            <td>
            <input type="text" name="add_qty[{{ $i }}]" id="add_qty{{ $i }}" value="{{ $row->qty }}" class="number integer" readonly />
            </td>
            <td>
                {{$row->uom_name}}
            </td>
            <td>
                <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="{{ $row->remarks }}"  />
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

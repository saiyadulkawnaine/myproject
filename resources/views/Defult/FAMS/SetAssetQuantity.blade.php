<table border="1" style="border-style:dotted">
   <thead>
      <tr align="center">
            <td width="50px">SL</td>
            <td width="150px">Product No</td>
            <td width="150px">Asset No</td>
            <td width="100px">Custom No</td>
            <td width="40px">Qty</td>
            <td width="100px">Rate</td>
            <td width="100px">Vendor Price</td>
            <td width="100px">Landed Cost</td>
            <td width="100px">Machanical Cost</td>
            <td width="100px">Civil Cost</td>
            <td width="100px">Electrical Cost</td>
            <td width="100px">Total Cost</td>
            <td width="110px">Warrentee Close <input type="hidden" name="is_edit" id="is_edit" value="0"></td>
            <td width="100px">Accumulated Dep.</td>
            <td width="100px">Salvage Value</td>
            <td width="50px">Life Time</td>
            <td width="100px">Company</td>
            <td width="100px">Location</td>
            <td width="100px">Division</td>
            <td width="100px">Department</td>
            <td width="100px">Section</td>
            <td width="100px">Subsection</td>
            <td width="100px">Floor</td>
            <td width="100px">Room No</td>
            <td width="100px">Remarks</td>
      </tr>
   </thead>
   <tbody>
      <?php 
        $i=1;
      ?>
      @for($j=$i; $j<=$qty; $j++)
      <tr>
            <td width="50px">
                  <input type="hidden" name="id[{{ $i}}]" id="id_{{ $i}}" value="">
                  {{ $i}}
                  <input type="hidden" name="asset_acquisition_id[{{ $i }}]" id="asset_acquisition_id_{{ $i }}"  value="{{ $asset_acquisition_id }}"/>
            </td>
            <td width="150px">
                  <input type="text" name="serial_no[{{ $i }}]" id="serial_no_{{ $i }}"  value="" />
            </td>
            <td width="150px">
                  <input type="text" name="asset_no[{{ $i }}]" id="asset_no{{ $i }}"  value="" class="number integer"/>
            </td>
            <td width="100px"><input type="text" name="custom_no[{{ $i }}]" id="custom_no_{{ $i }}"  value="" />
            </td>
            <td width="40px">
                  <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" value="1" style="background-color:#FFFFFF;border:none" readonly />
            </td>
            <td width="100px" >
                  <input type="text" name="rate[{{ $i }}]" id="rate_{{ $i }}" class="number integer"  value="" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'rate')"/>
            </td> 
            <td width="100px">
                  <input type="text" name="vendor_price[{{ $i }}]" id="vendor_price_{{ $i }}" class="number integer" value="" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'vendor_price')" readonly/>
            </td>
            <td width="100px">
                  <input type="text" name="landed_price[{{ $i }}]" id="landed_price_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'landed_price')" />
            </td>
            <td width="100px">
                  <input type="text" name="machanical_cost[{{ $i }}]" id="machanical_cost_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'machanical_cost')" />
            </td>
            <td width="100px">
                  <input type="text" name="civil_cost[{{ $i }}]" id="civil_cost_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'civil_cost')" />
            </td>
            <td width="100px">
                  <input type="text" name="electrical_cost[{{ $i }}]" id="electrical_cost_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'electrical_cost')" />
            </td>
            <td width="100px">
                  <input type="text" name="total_cost[{{ $i }}]" id="total_cost_{{ $i }}"  value="" class="number integer qtc" style="background-color:#FFFFFF;border:none" readonly onchange="MsAssetQuantityCost.getTotal(this.value())"/>
            </td>
            <td width="110px">
                  <input type="text" name="warrantee_close[{{ $i }}]" id="warrantee_close_{{ $i }}"  value="" class="datepicker" onchange="MsAssetQuantityCost.getWClose({{$i}},{{$qty}},'warrantee_close')" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  <input type="text" name="accumulated_dep[{{ $i }}]" id="accumulated_dep_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  <input type="text" name="salvage_value[{{ $i }}]" id="salvage_value_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.copySalvageValue(this.value,{{$i}},{{$qty}})" />
            </td>
            <td width="50px">
                  <input type="text" name="life_time[{{ $i }}]" id="life_time_{{ $i }}"  value="" class="number integer" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  {!! Form::select("company_id[$i]", $company,'',array('id'=>'company_id','onchange'=>'MsAssetQuantityCost.getTotal(this.value())')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("location_id[$i]", $location,'',array('id'=>'location_id')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("division_id[$i]", $division,'',array('id'=>'division_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("department_id[$i]", $department,'',array('id'=>'department_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("section_id[$i]", $section,'',array('id'=>'section_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("subsection_id[$i]", $subsection,'',array('id'=>'subsection_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("floor_id[$i]", $floor,'',array('id'=>'floor_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px"><input type="text" name="room_no[{{ $i }}]" id="room_no_{{ $i }}"  value="" style="background-color:#FFFFFF;border:none" /></td>
            <td width="100px"><input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"  value=""  style="background-color:#FFFFFF;border:none" /></td>
      </tr> 
      <?php 
        $i++;
      ?>
      @endfor         
      <tr align="center">
            <td width="50px"></td>
            <td width="150px"></td>
            <td width="150px"></td>
            <td width="100px"></td>
            <td width="40px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px">Total Cost</td>
            <td width="110px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="50px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
            <td width="100px"></td>
        </tr>
    </tbody>
</table>
<script>
      $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
      });
      $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
      });

      $('#assetquantitycostFrm [id="division_id"]').combobox();
      $('#assetquantitycostFrm [id="department_id"]').combobox();
      $('#assetquantitycostFrm [id="section_id"]').combobox();
      $('#assetquantitycostFrm [id="subsection_id"]').combobox();
      $('#assetquantitycostFrm [id="floor_id"]').combobox();
</script>

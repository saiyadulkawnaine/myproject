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
      <td width="110px">Warrentee Close <input type="hidden" name="is_edit" id="is_edit" value="1"></td>
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
            $totQty=0;
            $vendor=0;
            $landed=0;
            $machine=0;
            $civil=0;
            $electric=0;
            $total = 0;
            $totalAccDep=0;
            $totalSalvageVal=0;
            $totalLfe=0;
      ?>
      @foreach($data as $qtycost)   
      <tr>
            <td width="50px">{{ $i}}
                  <input type="hidden" name="id[{{ $i}}]" id="id_{{ $i}}" value="{{ $qtycost->id }}">
                  <input type="hidden" name="asset_acquisition_id[{{ $i }}]" id="asset_acquisition_id_{{ $i }}"  value="{{ $asset_acquisition_id }}" class="number integer"/>
            </td>
            <td width="150px">
                  <input type="text" name="serial_no[{{ $i }}]" id="serial_no_{{ $i }}"  value="{{$qtycost->serial_no}}" />
            </td>
            <td width="150px" align="center">
                  <input type="text" name="asset_no[{{ $i }}]" id="asset_no{{ $i }}"  value="{{ str_pad($qtycost->id,6,0,STR_PAD_LEFT ) }}" class="number integer"/>
            </td>
            <td width="100px" align="center">
                  <input type="text" name="custom_no[{{ $i }}]" id="custom_no_{{ $i }}"  value="{{ ($qtycost->custom_no!==null)?$qtycost->custom_no:str_pad($qtycost->id,6,0,STR_PAD_LEFT ) }}" />
            </td>
            <td width="40px" align="center">
                  <input type="text" name="qty[{{ $i }}]" id="qty_{{ $i }}" class="number integer" value="{{ $qtycost->qty }}" style="background-color:#FFFFFF;border:none" readonly />
            </td>
            <td width="100px" >
                  <input type="text" name="rate[{{ $i }}]" id="rate_{{ $i }}" class="number integer"  value="{{$qtycost->rate}}" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'rate')"/>
            </td>
            <td width="100px" align="center">
                  <input type="text" name="vendor_price[{{ $i }}]" id="vendor_price_{{ $i }}" class="number integer" value="{{$qtycost->vendor_price}}" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'vendor_price')" readonly/>
            </td>
            <td width="100px" align="center">
                  <input type="text" name="landed_price[{{ $i }}]" id="landed_price_{{ $i }}"  value="{{$qtycost->landed_price}}" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'landed_price')" />
            </td>
            <td width="100px" align="center">
                  <input type="text" name="machanical_cost[{{ $i }}]" id="machanical_cost_{{ $i }}"  value="{{$qtycost->machanical_cost}}" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'machanical_cost')" />
            </td>
            <td width="100px" align="center">
                  <input type="text" name="civil_cost[{{ $i }}]" id="civil_cost_{{ $i }}"  value="{{$qtycost->civil_cost}}" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'civil_cost')" />
            </td>
            <td width="50px" align="center">
                  <input type="text" name="electrical_cost[{{ $i }}]" id="electrical_cost_{{ $i }}"  value="{{$qtycost->electrical_cost}}" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getTotalCostCol({{$i}},{{$qty}},'electrical_cost')" />
            </td>
            <td width="100px" align="center">
                  <input type="text" name="total_cost[{{ $i }}]" id="total_cost_{{ $i }}"  value="{{ $qtycost->total_cost }}" class="number integer" style="background-color:#FFFFFF;border:none" readonly />
            </td>
            <td width="110px" align="center">
                  <input type="text" name="warrantee_close[{{ $i }}]" id="warrantee_close_{{ $i }}"  value="{{ $qtycost->warrantee_close }}" class="datepicker" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.getWClose({{$i}},{{$loop->count}},'warrantee_close')" />
            </td>
            <td width="100px">
                  <input type="text" name="accumulated_dep[{{ $i }}]" id="accumulated_dep_{{ $i }}"  value="{{$qtycost->accumulated_dep}}" class="number integer" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  <input type="text" name="salvage_value[{{ $i }}]" id="salvage_value_{{ $i }}"  value="{{$qtycost->salvage_value}}" class="number integer" style="background-color:#FFFFFF;border:none" onchange="MsAssetQuantityCost.copySalvageValue(this.value,{{$i}},{{$qty}})" />
            </td>
            <td width="50px">
                  <input type="text" name="life_time[{{ $i }}]" id="life_time_{{ $i }}"  value="{{$qtycost->life_time}}" class="number integer" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  {!! Form::select("company_id[$i]", $company, $qtycost->company_id ,array('id'=>'company_id')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("location_id[$i]", $location, $qtycost->location_id ,array('id'=>'location_id')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("division_id[$i]", $division, $qtycost->division_id ,array('id'=>'division_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("department_id[$i]", $department, $qtycost->department_id ,array('id'=>'department_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("section_id[$i]", $section,$qtycost->section_id,array('id'=>'section_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("subsection_id[$i]", $subsection, $qtycost->subsection_id ,array('id'=>'subsection_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  {!! Form::select("floor_id[$i]", $floor, $qtycost->floor_id ,array('id'=>'floor_id','style'=>'width: 70px; border-radius:2px')) !!}
            </td>
            <td width="100px">
                  <input type="text" name="room_no[{{ $i }}]" id="room_no_{{ $i }}"  value="{{ $qtycost->room_no }}" style="background-color:#FFFFFF;border:none" />
            </td>
            <td width="100px">
                  <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}"  value="{{ $qtycost->remarks }}"  style="background-color:#FFFFFF;border:none" />
            </td>
      </tr> 
      <?php 
      $i++;
      $totQty+=$qtycost->qty;
      $vendor+=$qtycost->vendor_price;
      $landed+=$qtycost->landed_price;
      $machine+=$qtycost->machanical_cost;
      $civil+=$qtycost->civil_cost;
      $electric+=$qtycost->electrical_cost;
      $total += $qtycost->total_cost;
      $totalAccDep+=$qtycost->accumulated_dep;
      $totalSalvageVal+=$qtycost->salvage_value;
      $totalLfe+=$qtycost->life_time;
      ?>
      @endforeach  
      <tr align="right">
            <td width="50px"></td>
            <td width="150px"></td>
            <td width="150px"></td>
            <td width="100px"></td>
            <td width="40px">{{ $totQty }}</td>
            <td width="100px"></td>
            <td width="100px">{{ $vendor }}</td>
            <td width="100px">{{ $landed }}</td>
            <td width="100px">{{ $machine }}</td>
            <td width="100px">{{ $civil }}</td>
            <td width="100px">{{ $electric }}</td>
            <td width="100px">{{ $total }}</td>
            <td width="110px"></td>
            <td width="100px">{{ $totalAccDep }}</td>
            <td width="100px">{{ $totalSalvageVal }}</td>
            <td width="50px">{{ $totalLfe }}</td>
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
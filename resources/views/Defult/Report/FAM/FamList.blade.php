<div class="easyui-layout animated rollIn"  data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Asset List  ||    Lithe Group'" style="padding:2px">
    <table id="famlistTbl" style="width:100%">
        <thead>
            <tr>
            	<th width="50"></th>
                <th width="50">1</th>
                <th width="80">2</th>
                
                <th width="80">3</th>
                <th width="60">4</th>
                <th width="60">5</th>
                <th width="60">6</th>
                <th width="80">7</th>
        
                <th width="100">8</th>
                <th width="100">9</th>
                <th width="100">10</th>
                <th width="100">11</th>
                <th width="80">12</th>
                <th width="80">13</th>
                <th width="80">14</th>
                <th width="80">15</th>
                <th width="80">16</th>
                <th width="100">17</th>
                <th width="100">18</th>
                <th width="100">19</th>
                <th width="100">20</th>
                <th width="100">21</th>
                <th width="100">22</th>
                <th width="100">23</th>
                <th width="100">24</th>
                <th width="100">25</th>
                <th width="100">26</th>       
                <th width="100">27</th>       
            </tr>
            <tr>
            	<th data-options="field:'asset_quantity_cost_id'" formatter="MsFamList.formatAssetTicketPdf" width="80">PDF</th>
                <th data-options="field:'aquisition_id'" width="80">Asset ID</th>
                <th data-options="field:'name'" width="80">Asset Name</th>
                <th data-options="field:'asset_no'" width="80">Asset No</th>
                <th data-options="field:'custom_no'" width="80">Custom No</th>
                <th data-options="field:'emp_name'" width="80">Emp. Name</th>
                <th data-options="field:'company_name'" width="60">Company</th>
                <th data-options="field:'location_name'" width="100">Location</th>
                <th data-options="field:'production_area'" width="100">Production Area</th>
                <th data-options="field:'asset_type_name'" width="100">Asset Type</th>
                <th data-options="field:'asset_group'" width="100">Group</th>
                <th data-options="field:'supplier_name'" width="100">Supplier</th>
                <th data-options="field:'brand_origin'" width="80">Brand / Origin</th>
        
                <th data-options="field:'purchase_date'" width="80">Purchase Date</th>
                <th data-options="field:'prod_capacity'" width="80">Prod Capacity</th>
                <th data-options="field:'rate'" width="80" align="right">Rate</th>
                <th data-options="field:'vendor_price'" width="100" align="right">Vendor Price</th>
                <th data-options="field:'landed_price'" width="80" align="right">Landed Cost</th>
                <th data-options="field:'machanical_cost'" width="80" align="right">Machanical Cost</th>
                <th data-options="field:'civil_cost'" width="80" align="right">Civil Cost</th>
                <th data-options="field:'electrical_cost'" width="80" align="right">Electrical_cost</th>
                <th data-options="field:'total_cost'" width="80" align="right">Total Cost</th>
                <th data-options="field:'warrantee_close'" width="80" align="right">Warrentee Close</th>
                <th data-options="field:'dia_width'" width="100" align="right">Dia / Width</th>
                <th data-options="field:'gauge'" width="100" align="right">Gauge</th>
                <th data-options="field:'extra_cylinder'" width="100" align="right">Extra Cylinder</th>
                <th data-options="field:'no_of_feeder'" width="100" align="right">No of Feeder</th>
                <th data-options="field:'serial_no'" width="100" align="right">Product No</th>      
            </tr>
        </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
   <form id="famlistFrm">
       <div id="container">
            <div id="body">
              <code>
                <div class="row">
                    <div class="col-sm-4">Company</div>
                    <div class="col-sm-8">
                      {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Location</div>
                    <div class="col-sm-8">
                      {!! Form::select('location_id', $location,'',array('id'=>'location_id','style'=>'width: 100%; border-radius:2px')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Asset Name</div>
                    <div class="col-sm-8">
                      <input type="text" name="name" id="name" />
                      {{-- <input type="hidden" name="id" id="id" value="" /> --}}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Asset Type</div>
                    <div class="col-sm-8">
                      {!! Form::select('type_id',$assetType,'',array('id'=>'type_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Production Area</div>
                    <div class="col-sm-8">
                      {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                    </div>
                </div>
               <div class="row middle">
                    <div class="col-sm-4">Purchase Date</div>
                    <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>
             </code>
          </div>
       </div>
       <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
           <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsFamList.get()">Show</a>
           <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFamList.resetForm('famlistFrm')" >Reset</a>
           <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsFamList.multiassetticketpdf()" >PDF</a>
       </div>
     </form>
   </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/FAM/MsFamListController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
</script>
<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="jobtabs">
<div title="Jobs" style="padding:1px" data-options="selected:true">
  <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
      <table id="jobTbl" style="width:690px">
        <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'job_no'" width="80">Job No</th>
              <th data-options="field:'company'" width="100">Company</th>
              <th data-options="field:'style_ref'" width="100">Style</th>
              <th data-options="field:'buyer'" width="100">Buyer</th>
              <th data-options="field:'currency'" width="70">Currency</th>
              <th data-options="field:'uom'" width="60">UOM</th>
              <th data-options="field:'season'" width="100">Season</th>
           </tr>
        </thead>
      </table>
    </div>
<div data-options="region:'west',border:true,title:'Add New Job',footer:'#ft2'" style="width:350px; padding:2px">
<form id="jobFrm">
    <div id="container">
         <div id="body">
           <code>
             <div class="row">
                 <div class="col-sm-4">Job No</div>
                 <div class="col-sm-8">
                 <input type="text" name="job_no" id="job_no" value="" readonly/>
                 <input type="hidden" name="id" id="id" value="" readonly/>
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Company</div>
                 <div class="col-sm-8">
                 {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Style</div>
                 <div class="col-sm-8">
                 <input type="text" name="style_ref" id="style_ref" placeholder="Double Click"  onDblClick="MsJob.openStyleWindow()" readonly/>
                 <input type="hidden" name="style_id" id="style_id"  readonly/>
                 </div>
             </div>
             <div class="row middle">
               <div class="col-sm-4 req-text">Buyer </div>
               <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}</div>
             </div>
             <div class="row middle">
               <div class="col-sm-4 req-text">Currency</div>
               <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">UOM </div>
                 <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Season</div>
                 <div class="col-sm-8">{!! Form::select('season_id', $season,'',array('id'=>'season_id','disabled'=>'disabled')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Remarks </div>
                 <div class="col-sm-8"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsJob.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('jobFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsJob.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
</div>

<div title="Orders" style="padding:1px">
@includeFirst(['Sales.SalesOrder', 'Defult.Sales.SalesOrder'])
</div>
<div title="Countries" style="padding:1px">
@includeFirst(['Sales.SalesOrderCountry', 'Defult.Sales.SalesOrderCountry'])
</div>

<div title="Colors & Sizes" style="padding:1px">
  @includeFirst(['Sales.SalesOrderColor', 'Defult.Sales.SalesOrderColor'])
</div>
<div title="Item" style="padding:1px">
  @includeFirst(['Sales.SalesOrderItem', 'Defult.Sales.SalesOrderItem'])
</div>
</div>
   <div id="w" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
   <div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                    <div id="body">
                        <code>
                            <form id="stylesearch">
                                <div class="row">
                                    <div class="col-sm-2">Buyer :</div>
                                    <div class="col-sm-4">
                                   {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                    </div>
                                     <div class="col-sm-2 req-text">Style Ref. </div>
                                    <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                                </div>
                                <div class="row middle">
                                     <div class="col-sm-2">Style Des.  </div>
                                     <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                                </div>
                            </form>
                        </code>
                    </div>
                        <p class="footer">
                         <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsJob.showStyleGrid()">Search</a>
                        </p>
                </div>
            </div>
			<div data-options="region:'center'" style="padding:10px;">
				<table id="styleTbl" style="width:610px">
          <thead>
              <tr>
                  <th data-options="field:'id'" width="50">ID</th>
                  <th data-options="field:'buyer'" width="70">Buyer</th>
                  <th data-options="field:'receivedate'" width="80">Receive Date</th>
                  <th data-options="field:'style_ref'" width="100">Style Refference</th>
                  <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                  <th data-options="field:'productdepartment'" width="80">Product Department</th>
                  <th data-options="field:'season'" width="80">Season</th>
             </tr>
          </thead>
        </table>
			</div>
			<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
				<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#w').window('close')" style="width:80px">Close</a>
			</div>
		</div>
	</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllSaleOrderController.js"></script>

<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>


  <div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Basic" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
			 <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
        <form id="knitchargeFrm">
        <div class="row">
        <div class="col-sm-4 req-text">Company</div>
        <div class="col-sm-8">
        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
        <input type="hidden" name="id" id="id" value=""/>
        </div>
        </div>
       
        <div class="row middle">
        <div class="col-sm-4 req-text">Construction </div>
        <div class="col-sm-8">{!! Form::select('construction_id', $construction,'',array('id'=>'construction_id')) !!}</div>
        </div>
       
       
        <div class="row middle">
        <div class="col-sm-4 req-text">Fabric Looks  </div>
        <div class="col-sm-8">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Uom </div>
        <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate </div>
        <div class="col-sm-8"><input type="text" name="in_house_rate" id="in_house_rate" value=""/></div>
        </div>
         <div class="row middle">
        <div class="col-sm-4">GMTS Part </div>
        <div class="col-sm-8">{!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Yarn Count </div>
        <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">From GSM </div>
        <div class="col-sm-8"><input type="text" name="from_gsm" id="from_gsm" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">To GSM </div>
        <div class="col-sm-8"><input type="text" name="to_gsm" id="to_gsm" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Gauge  </div>
        <div class="col-sm-8"><input type="text" name="gauge" id="gauge" value=""/></div>
        </div>
        
       
        <div class="row middle">
        <div class="col-sm-4">Sequence  </div>
        <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" value=""/></div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsKnitCharge.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('knitchargeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsKnitCharge.remove()" >Delete</a>
       </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="knitchargeTbl" style="width:100%">
        <thead>
        <tr>
            <th data-options="field:'id'" width="80">ID</th>
            <th data-options="field:'company'" width="100">Company</th>
            <th data-options="field:'fabrication'" width="500">Construction</th>
            <th data-options="field:'fabriclooks'" width="100">Fabric Looks</th>
            <th data-options="field:'inhouserate'" width="100" align="right">In House Rate</th>
            <th data-options="field:'uom'" width="100">Uom</th>
        </tr>
        </thead>
        </table>
       </div>
     </div>
    </div>
    <div title="Buyer Rate" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
			 <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
<form id="buyerknitchargeFrm">
  <div class="row">
           <div class="col-sm-4 req-text">Buyer: </div>
           <div class="col-sm-8">
             {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
             <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="knit_charge_id" id="knit_charge_id" value=""/>
           </div>
           </div>
           <div class="row middle">
           <div class="col-sm-4 req-text">Rate: </div>
           <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
       </div>

       <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerKnitCharge.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerknitchargeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerKnitCharge.remove()" >Delete</a>
    </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="buyerknitchargeTbl" style="width:100%">
         <thead>
             <tr>
                 <th data-options="field:'id'" width="80">ID</th>
                 <th data-options="field:'buyer'" width="100">Buyer</th>
                 <th data-options="field:'rate'" width="100">Rate</th>
            </tr>
         </thead>
         </table>
       </div>
     </div>
    </div>
    <div title="Supplier Rate" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'west',split:true, title:'Knit Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
      <form id="knitchargesupplierFrm">
        <div class="row">
                   <div class="col-sm-4 req-text">Supplier: </div>
                   <div class="col-sm-8">
                     {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                     <input type="hidden" name="knit_charge_id" id="knit_charge_id" value=""/>
                     <input type="hidden" name="id" id="id" value=""/>
                   </div>
                   </div>
                   <div class="row middle">
                   <div class="col-sm-4 req-text">Rate: </div>
                   <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
               </div>

               <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsKnitChargeSupplier.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('knitchargesupplierFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsKnitChargeSupplier.remove()" >Delete</a>
    </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="knitchargesupplierTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'supplier'" width="100">Supplier</th>
                <th data-options="field:'rate'" width="100">Rate</th>
           </tr>
        </thead>
        </table>
       </div>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsKnitChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsKnitChargeSupplierController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBuyerKnitChargeController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

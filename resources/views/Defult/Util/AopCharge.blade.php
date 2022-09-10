
  <div class="easyui-tabs" style="width:100%;height:100%; border:none">
    <div title="Basic" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
			 <div data-options="region:'west',split:true, title:'Aop Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
        <form id="aopchargeFrm">
        <div class="row">
        <div class="col-sm-4 req-text">Company</div>
        <div class="col-sm-8">
        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
        <input type="hidden" name="id" id="id" value=""/>
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Fabrication </div>
        <div class="col-sm-8">
        <input type="hidden" name="autoyarn_id" id="autoyarn_id"  readonly="readonly"/>
        <input type="text" name="fabrication" id="fabrication" ondblclick="MsAopCharge.openFabricationWindow()" placeholder="Double Click For Search"/>
        </div>
        </div>
      
     
        <div class="row middle">
        <div class="col-sm-4 req-text">From GSM </div>
        <div class="col-sm-8"><input type="text" name="from_gsm" id="from_gsm" value=""/></div>
        </div>
        
        <div class="row middle">
        <div class="col-sm-4 req-text">To GSM </div>
        <div class="col-sm-8"><input type="text" name="to_gsm" id="to_gsm" value=""/></div>
        </div>
        
        <div class="row middle">
        <div class="col-sm-4 req-text">Aop Type </div>
        <div class="col-sm-8">{!! Form::select('embelishment_type_id', $embelishmenttype,'',array('id'=>'embelishment_type_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">From Coverage % </div>
        <div class="col-sm-8"><input type="text" name="from_coverage" id="from_coverage" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">To Coverage % </div>
        <div class="col-sm-8"><input type="text" name="to_coverage" id="to_coverage" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">From Impression  </div>
        <div class="col-sm-8"><input type="text" name="from_impression" id="from_impression" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">To Impression  </div>
        <div class="col-sm-8"><input type="text" name="to_impression" id="to_impression" value=""/></div>
        </div>
        
        <div class="row middle">
        <div class="col-sm-4 req-text">Uom </div>
        <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text">Rate </div>
        <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
        </div>
       
       
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAopCharge.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('aopchargeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAopCharge.remove()" >Delete</a>
       </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="aopchargeTbl" style="width:790px">
        <thead>
        <tr>
            <th data-options="field:'id'" width="50">ID</th>
            <th data-options="field:'company'" width="80">Company</th>
            <th data-options="field:'fabrication'" width="150">Fabrication</th>
            <th data-options="field:'aop_type'" width="80">AOP Type</th>
            <th data-options="field:'from_coverage',halign:'center'" width="80" align="right">From <br/>Coverage%</th>
            <th data-options="field:'to_coverage',halign:'center'" width="80" align="right">To <br/> Coverage %</th>
            <th data-options="field:'from_impression',halign:'center'" width="80" align="right">From <br/> Impression</th>
             <th data-options="field:'to_impression',halign:'center'" width="80" align="right">To <br/> Impression</th>
            <th data-options="field:'rate',halign:'center'" width="50" align="right">Rate</th>
            <th data-options="field:'uom'" width="60">Uom</th>
        </tr>
        </thead>
        </table>
       </div>
     </div>
    </div>
    <div title="Buyer Rate" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
			 <div data-options="region:'west',split:true, title:'Aop Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
<form id="aopbuyerchargeFrm">
  <div class="row">
           <div class="col-sm-4 req-text">Buyer: </div>
           <div class="col-sm-8">
             {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
             <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="aop_charge_id" id="aop_charge_id" value=""/>
           </div>
           </div>
           <div class="row middle">
           <div class="col-sm-4 req-text">Rate: </div>
           <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
       </div>

       <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAopBuyerCharge.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('aopbuyerchargeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAopBuyerCharge.remove()" >Delete</a>
    </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="aopbuyerchargeTbl" style="width:100%">
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
       <div data-options="region:'west',split:true, title:'Aop Charge',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:350px;padding:3px">

        <div id="container">
        <div id="body">
        <code>
      <form id="aopsupplierchargeFrm">
        <div class="row">
                   <div class="col-sm-4 req-text">Supplier: </div>
                   <div class="col-sm-8">
                     {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                     <input type="hidden" name="aop_charge_id" id="aop_charge_id" value=""/>
                     <input type="hidden" name="id" id="id" value=""/>
                   </div>
                   </div>
                   <div class="row middle">
                   <div class="col-sm-4 req-text">Rate: </div>
                   <div class="col-sm-8"><input type="text" name="rate" id="rate" value=""/></div>
               </div>

               <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAopSupplierCharge.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('aopsupplierchargeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAopSupplierCharge.remove()" >Delete</a>
    </div>
        </form>
        </code>
        </div>
        </div>
       </div>
       <div data-options="region:'center',split:true, title:'List'">
         <table id="aopsupplierchargeTbl" style="width:100%">
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
  
  <div id="aopChargeFabricationWindow" class="easyui-window" title="Fabrications" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#stylefabricsearchTblft'" style="padding:2px">
            <table id="aopchargefabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,footer:'#aopchargefabricsearchft'" style="padding:2px; width:350px">
            <form id="aopchargefabricsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Construction</div>
                            <div class="col-sm-8"> <input type="text" name="construction_name" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="aopchargefabricsearchft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsAopCharge.searchFabric()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAopChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAopSupplierChargeController.js"></script>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAopBuyerChargeController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>

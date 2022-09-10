<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="rqyarntabs">
 <div title="Yarn Requisition" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List',footer:'#rqyarnTblFt'" style="padding:2px">
    <table id="rqyarnTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id',styler:MsRqYarn.approvedmsg" width="30">ID</th>
       <th data-options="field:'rq_no'" width="100">Req. No</th>
       <th data-options="field:'ready_to_approve'" width="60">Is Ready <br>To Approve</th>
       <th data-options="field:'company_name'" width="100">Company</th>
       <th data-options="field:'basis_name'" width="100">Basis</th>
       <th data-options="field:'supplier_name'" width="100">Supplier</th>
       <th data-options="field:'rq_date'" width="100">Req. Date</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
    <div id="rqyarnTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     Req Date: <input type="text" name="from_date" id="from_date" class="datepicker"
      style="width: 150px ;height: 23px" />
     <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 150px;height: 23px" />
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-search" plain="true" id="save" onClick="MsRqYarn.searchRqYarn()">Show</a>
    </div>
   </div>
   <div
    data-options="region:'west',border:true,title:'Yarn Requisition',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#rqyarnFrmft'"
    style="width:450px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="rqyarnFrm">
                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Req No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="rq_no" id="rq_no" readonly />
                                        </div>
                                    </div>

                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Company</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Req. Against</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('rq_against_id', $menu,'',array('id'=>'rq_against_id')) !!}
                                        </div>
                                    </div> 
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Kniting Company</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        </div>
                                    </div>
                                     <div class="row middle">
                                    <div class="col-sm-4">Req Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rq_date" id="rq_date" class="datepicker" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Ready to Approve</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('ready_to_approve_id', $yesno,'0',array('id'=>'ready_to_approve_id')) !!}
                                    </div>
                                </div>
                            </form>
                        </code>
     </div>
    </div>
    <div id="rqyarnFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsRqYarn.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsRqYarn.resetForm()">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsRqYarn.remove()">Delete</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsRqYarn.pdf()">PDF</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Fabrication" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="rqyarnfabricationTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'pl_no'" width="100">Plan/WO No</th>
       <th data-options="field:'gmtspart'" width="100">Body Part</th>
       <th data-options="field:'fabrication'" width="300">Fabrication</th>
       <th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
       <th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
       <th data-options="field:'gsm_weight'" width="80">GSM</th>
       <th data-options="field:'dia'" width="80">Dia</th>
       <th data-options="field:'stitch_length'" width="80">Stitch Length</th>
       <th data-options="field:'colorrange_name'" width="100">Color Range</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Fabrication',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#rqyarnfabricationFrmft'"
    style="width:450px; padding:2px">
    <div id="container">
     <div id="body">
      <code>
                            <form id="rqyarnfabricationFrm">

                                <div class="row">
                                    <input type="hidden" name="id" id="id" />
                                    <input type="hidden" name="pl_knit_item_id" id="pl_knit_item_id" />
                                    <input type="hidden" name="po_knit_service_item_qty_id" id="po_knit_service_item_qty_id" />
                                    <input type="hidden" name="rq_yarn_id" id="rq_yarn_id" />
                                </div>


                                <div class="row middle">
                                        <div class="col-sm-4">Plan/WO No</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="pl_no" id="pl_no" readonly ondblclick="MsRqYarnFab.rqYarnFabricationWindowOpen()" placeholder="Double Click" />
                                        </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" value=""></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Buyer </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="buyer_name" id="buyer_name" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order Numbers </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="order_no" id="order_no" disabled />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Desc. </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="fabrication" id="fabrication" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Plan Qty </div>
                                    <div class="col-sm-8">
                                    <input type="text" name="plan_qty" id="plan_qty" class="number integer" disabled />
                                    </div>
                                </div>

                                
                            </form>
                        </code>
     </div>
    </div>
    <div id="rqyarnfabricationFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
     <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsRqYarnFab.submit()">Save</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('rqyarnfabricationFrm')">Reset</a>
     <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
      iconCls="icon-remove" plain="true" id="delete" onClick="MsRqYarnFab.remove()">Delete</a>
    </div>
   </div>
  </div>
 </div>
 <div title="Required Yarn Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
     <table id="rqyarnitemTbl" style="width:100%">
      <thead>
       <tr>
        <th data-options="field:'id'" width="30">ID</th>
        <th data-options="field:'inv_yarn_item_id'" width="100">Yarn Item</th>
        <th data-options="field:'count'" width="100">Count</th>
        <th data-options="field:'composition'" width="100">Composition</th>
        <th data-options="field:'yarn_type'" width="100">Type</th>
        <th data-options="field:'lot'" width="100">Lot</th>
        <th data-options="field:'brand'" width="100">Brand</th>
        <th data-options="field:'color_name'" width="100">Color</th>
        <th data-options="field:'qty'" width="100" align="right">Qty</th>
        <th data-options="field:'remarks'" width="100">Remarks</th>
       </tr>
      </thead>
     </table>
    </div>
    <div
     data-options="region:'west',border:true,title:'Item',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#rqyarnitemFrmFt'"
     style="width:450px; padding:2px">
     <div id="container">
      <div id="body">
       <code>
                            <form id="rqyarnitemFrm">

                                <div class="row">
                                    <input type="hidden" name="id" id="id" readonly/>
                                    <input type="hidden" name="rq_yarn_fabrication_id" id="rq_yarn_fabrication_id" readonly/>
                                </div>


                                <div class="row middle">
                                        <div class="col-sm-4">Yarn Item</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="inv_yarn_item_id" id="inv_yarn_item_id" readonly ondblclick="MsRqYarnItem.rqYarnItemWindowOpen()"/>
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Count</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="count" id="count" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Composition</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="composition" id="composition" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Type</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="yarn_type" id="yarn_type" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Lot</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="lot" id="lot" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Brand</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="brand" id="brand" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Color</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="color_name" id="color_name" disabled />
                                        </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Qty</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qty" id="qty" class="number integer"/>
                                        </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </form>
                        </code>
      </div>
     </div>
     <div id="rqyarnitemFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsRqYarnItem.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('rqyarnitemFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsRqYarnItem.remove()">Delete</a>

     </div>
    </div>
   </div>

  </div>
 </div>
</div>


<div id="rqYarnFabricationWindow" class="easyui-window" title="Item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#rqyarnfabricationsearchTblFt'" style="padding:2px">
   <table id="rqyarnfabricationsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="80">ID</th>
      <th data-options="field:'pl_no'" width="100">Plan No/WO No</th>
      <th data-options="field:'gmtspart'" width="100">Body Part</th>
      <th data-options="field:'fabrication'" width="300">Fabrication</th>
      <th data-options="field:'fabricshape'" width="80">Fabric Shape</th>
      <th data-options="field:'fabriclooks'" width="80">Fabric Looks</th>
      <th data-options="field:'gsm_weight'" width="80">GSM</th>
      <th data-options="field:'dia'" width="80">Dia</th>
      <th data-options="field:'stitch_length'" width="80">Stitch Length</th>
      <th data-options="field:'colorrange_name'" width="100">Color Range</th>
     </tr>
    </thead>
   </table>
   <div id="rqyarnfabricationsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#rqYarnFabricationWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#rqyarnfabricationsearchFrmFt'" style="padding:2px; width:350px">
   <form id="rqyarnfabricationsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">Plan No</div>
                              <div class="col-sm-8">
                              <input type="text" name="pl_no" id="pl_no" />
                              </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">WO No</div>
                            <div class="col-sm-8">
                            <input type="text" name="po_no" id="po_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Dia</div>
                            <div class="col-sm-8">
                            <input type="text" name="dia" id="dia" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">GSM</div>
                            <div class="col-sm-8">
                            <input type="text" name="gsm" id="gsm" />
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="rqyarnfabricationsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true" \
      onClick="MsRqYarnFab.searchFabrication()">Search</a>
    </div>
   </form>
  </div>

 </div>
</div>


<div id="rqYarnItemWindow" class="easyui-window" title="Item Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'center',border:true,footer:'#rqyarnitemsearchTblFt'" style="padding:2px">
   <table id="rqyarnitemsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'count'" width="100">Count</th>
      <th data-options="field:'composition'" width="100">Yarn Item</th>
      <th data-options="field:'yarn_type'" width="100">Type</th>
      <th data-options="field:'lot'" width="100">Lot</th>
      <th data-options="field:'brand'" width="100">Brand</th>
      <th data-options="field:'color_name'" width="100">Color</th>
      <th data-options="field:'itemclass_name'" width="100">Item Class</th>
      <th data-options="field:'itemcategory_name'" width="100">Item category</th>
      <th data-options="field:'stock_qty'" width="100" align="right">Stock Qty</th>

     </tr>
    </thead>
   </table>
   <div id="rqyarnitemsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
     onclick="$('#rqYarnItemWindow').window('close')" style="width:80px">Close</a>
   </div>
  </div>
  <div data-options="region:'west',border:true,footer:'#rqyarnitemsearchFrmFt'" style="padding:2px; width:350px">
   <form id="rqyarnitemsearchFrm">
    <div id="container">
     <div id="body">
      <code>
                            <div class="row middle">
                              <div class="col-sm-4 req-text">Lot</div>
                              <div class="col-sm-8">
                              <input type="text" name="lot" id="lot" />
                              </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Brand</div>
                            <div class="col-sm-8">
                            <input type="text" name="brand" id="brand" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Count</div>
                            <div class="col-sm-8">
                            <input type="text" name="count" id="count" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Type</div>
                            <div class="col-sm-8">
                            <input type="text" name="type" id="type" />
                            </div>
                            </div>
                        </code>
     </div>
    </div>
    <div id="rqyarnitemsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" plain="true"
      onClick="MsRqYarnItem.searchItem()">Search</a>
    </div>
   </form>
  </div>

 </div>
</div>






<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsRqYarnController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsRqYarnFabricationController.js">
</script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Kniting/MsRqYarnItemController.js"></script>

<script>
 $('#rqyarnFrm [id="supplier_id"]').combobox();
$(document).ready(function() {
    
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

    
});
</script>
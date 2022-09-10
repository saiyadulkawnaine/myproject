<div class="easyui-tabs animated rollIn" style="width:100%;height:100%;border:none" id="soembcutpanelrcvtabs">
  <div title="Start Up" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="soembcutpanelrcvTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="30">ID</th>
              <th data-options="field:'company_name'" width="100">Company</th>
              <th data-options="field:'buyer_name'" width="100">Customer</th>
              <th data-options="field:'shift_name'" width="30">Shift Name</th>
              <th data-options="field:'receive_date'" width="100">Receive Date</th>
              <th data-options="field:'production_area'" width="100">Production Area</th>
              <th data-options="field:'challan_no'" width="100">Challan No</th>
              <th data-options="field:'remarks'" width="100">Remarks</th>
            </tr>
          </thead>
        </table>
      </div>
      <div
        data-options="region:'west',border:true,title:'Cut Panel Received',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#socutpanelrcvFrmft'"
        style="width:450px; padding:2px">
        <div id="container">
          <div id="body">
            <code>
            <form id="soembcutpanelrcvFrm">
               <div class="row">
                   <input type="hidden" name="id" id="id" />
               </div>
															<div class="row middle">
																<div class="col-sm-5 req-text">Challan No</div>
																<div class="col-sm-7">
																	<input type="text" name="challan_no" id="challan_no" />
																</div>
															</div>
               <div class="row middle">
                <div class="col-sm-5 req-text">Receiving Company</div>
                <div class="col-sm-7">
                 {!! Form::select('company_id',$company,array('id'=>'company_id')) !!}
                </div>
               </div>
               <div class="row middle">
                 <div class="col-sm-5 req-text">Customer</div>
                 <div class="col-sm-7">
                  {!! Form::select('buyer_id', $buyer,'', array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                 </div>
                </div>
																<div class="row middle">
																	<div class="col-sm-5 req-text">Prod. Area</div>
																	<div class="col-sm-7">
																		{!! Form::select('production_area_id',$productionarea,array('id'=>'production_area_id')) !!}
																	</div>
																</div>
																<div class="row middle">
																	<div class="col-sm-5 req-text"> Receive Date</div>
																	<div class="col-sm-7">
																		<input type="text" name="receive_date" id="receive_date" class="datepicker" />
																	</div>
																</div>
                <div class="row middle">
                 <div class="col-sm-5">Shift Name</div>
                 <div class="col-sm-7">
                  {!! Form::select('shift_id',$shiftname,array('id'=>'shift_id')) !!}
                 </div>
                </div>
                 <div class="row middle">
                    <div class="col-sm-5">Remarks </div>
                    <div class="col-sm-7">
                     <textarea name="remarks" id="remarks" value=""></textarea>
                    </div>
                  </div>
          </form>
        </code>
          </div>
        </div>
        <div id="socutpanelrcvFrmft" style="padding:0px 0px; text-align:right; background:#CCC;">
          <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
            iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcv.submit()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
            iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('soembcutpanelrcvFrm')">Reset</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
            iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcv.remove()">Delete</a>
        </div>
      </div>
    </div>
  </div>

  <div title="Order Details" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'west',border:true" style="width:400px; padding:2px">
        <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
            style="height:150px; padding:2px">
            <form id="soembcutpanelrcvorderFrm">
              <div id="container">
                <div id="body">
                  <code>
                    <div class="row middle">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="so_emb_cutpanel_rcv_id" id="so_emb_cutpanel_rcv_id" value="" />
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4">Order No</div>
                      <div class="col-sm-8">
                        <input type="text" name="sales_order_no" id="sales_order_no" ondblclick="MsSoEmbCutpanelRcvOrder.soWindow()"
                        placeholder="Double Click" />
                        <input type="hidden" name="so_emb_id" id="so_emb_id" value="" />
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4">Remarks</div>
                      <div class="col-sm-8">
                        <input type="text" name="remarks" id="remarks">
                      </div>
                    </div>
                   </code>
                </div>
              </div>
              <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                  iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcvOrder.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete"
                  onClick="msApp.resetForm('soembcutpanelrcvorderFrm')">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvOrder.remove()">Delete</a>
              </div>
            </form>
          </div>
          <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
            <table id="soembcutpanelrcvorderTbl" style="width:100%">
              <thead>
                <tr>
                  <th data-options="field:'id'" width="30">ID</th>
                  <th data-options="field:'sales_order_no'" width="30">Sales Order No</th>
                  <th data-options="field:'remarks'" width="30">Remerks</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div data-options="region:'center',border:true,title:'Cut Panel'" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft3'"
            style="height:150px; padding:2px">
            <form id="soembcutpanelrcvqtyFrm">
              <div id="container">
                <div id="body">
                  <code>
               <table style="width:100%" border="1">
                   <thead>
                   <tr>
                   <th width="100" class="text-center">Order No</th>
                   <th width="100" class="text-center">GMT Item</th>
                   <th width="100" class="text-center">GMT Color</th>
                   <th width="100" class="text-center">Body Part</th>
                   <th width="100" class="text-center">Design No</th>
                   <th width="100" class="text-center">Current <br/> Rcv Qty</th>
                   </tr>
                   </thead>
                   <tbody>
                       <tr>
                           <td width="100">
                            <input type="hidden" name="id" id="id" value="" />
                            <input type="hidden" name="so_emb_cutpanel_rcv_order_id" id="so_emb_cutpanel_rcv_order_id" value="" />
                            <input type="hidden" name="so_emb_ref_id" id="so_emb_ref_id">
                            <input type="text" name="sale_order_no" id="sale_order_no" placeholder="Browse" onclick="MsSoEmbCutpanelRcvQty.OpenEmbItemWindow()">
                           </td>
                           <td width="100"><input type="text" name="item_desc" id="item_desc" readonly/></td>
                           <td width="100">
                            <input type="text" name="gmt_color" id="gmt_color" readonly/></td>
                           <td width="100">
                            <input type="text" name="gmtspart" id="gmtspart" readonly/></td>
                           <td width="100"><input type="text" name="design_no" id="design_no"/></td>
                           <td width="100"><input type="text" name="qty" id="qty"/></td>
                       </tr>
                   </tbody>
               </table>
       		     </code>
                </div>
              </div>
              <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
                  iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbCutpanelRcvQty.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete"
                  onClick="msApp.resetForm('soembcutpanelrcvqtyFrm')">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
                  iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbCutpanelRcvQty.remove()">Delete</a>
              </div>
            </form>
          </div>
          <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
            <table id="soembcutpanelrcvqtyTbl" style="width:100%">
              <thead>
                <tr>
                  <th data-options="field:'id'" width="20">ID</th>
                  <th data-options="field:'sale_order_no'" width="200">Order No</th>
                  <th data-options="field:'item_desc'" width="80">Gmt Item</th>
                  <th data-options="field:'gmt_color'" width="100">Gmt Color</th>
                  <th data-options="field:'gmtspart'" width="80">Body Part</th>
                  <th data-options="field:'design_no'" width="120">Design No</th>
                  <th data-options="field:'qty'" width="65">Qty</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="soembWindow" class="easyui-window" title="Subcontract Order Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',split:true, title:'Search',footer:'#soembcutpanelrcvsoWindowFt'"
      style="width:350px;height:130px">
      <div class="easyui-layout" data-options="fit:true">
        <div id="body">
          <code>
       <form id="soembsearchFrm">
        <div class="row middle">
         <div class="col-sm-5">Company</div>
         <div class="col-sm-7">
          {!! Form::select('company_id',$company,array('id'=>'company_id')) !!}
         </div>
        </div>
        <div class="row middle">
         <div class="col-sm-5">So No</div>
         <div class="col-sm-7">
          <input type="text" name="so_no" id="so_no">
         </div>
        </div>
       </form>
     </code>
        </div>
        <div id="soembcutpanelrcvsoWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <a href="javascript:void(0)" class="easyui-linkbutton c2" style="border-radius:1px"
            onClick="MsSoEmbCutpanelRcvOrder.getsubconorder()">Search</a>
        </div>
      </div>
    </div>
    <div data-options="region:'center',footer:'#soembcutpanelrcvsosearchTblFt'" style="padding:10px;">
      <table id="soembsearchTbl" style="width:100%">
        <thead>
          <tr>
            <th data-options="field:'id'" width="30">ID</th>
            <th data-options="field:'sales_order_no'" width="130">Sales Order No</th>
            <th data-options="field:'company_name'" width="100">Company</th>
            <th data-options="field:'buyer_name'" width="160">Buyer</th>
          </tr>
        </thead>
      </table>
      <div id="soembcutpanelrcvsosearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
          onclick="$('#soembWindow').window('close')" style="border-radius:1px">Close</a>
      </div>
    </div>

  </div>
</div>

<div id="soembitemWindow" class="easyui-window" title="Emblishment Item Window"
  data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
  <div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'" style="padding:10px;">
      <table id="soembitemsearchTbl" style="width:100%">
        <thead>
          <tr>
            <th data-options="field:'id'" width="30">ID</th>
            <th data-options="field:'buyer_name'" width="100">Buyer</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
            <th data-options="field:'emb_name'" width="100">Emb. Name</th>
            <th data-options="field:'emb_type'" width="100">Emb. Type</th>
            <th data-options="field:'emb_size'" width="80">Emb. Size</th>
            <th data-options="field:'item_desc'" width="100">GMT Item</th>
            <th data-options="field:'gmtspart'" width="100">GMT Part</th>
            <th data-options="field:'gmt_color'" width="100">GMT Color</th>
            <th data-options="field:'gmt_size'" width="80">GMT Size</th>
            <th data-options="field:'qty'" width="80" align="right">Qty</th>
            <th data-options="field:'uom_name'" width="40">Uom</th>
            <th data-options="field:'rate'" width="60" align="right">Rate</th>
            <th data-options="field:'amount'" width="80" align="right">Amount</th>
            <th data-options="field:'delivery_date'" width="80">Delivery Date</th>
          </tr>
        </thead>
      </table>
    </div>

  </div>
</div>

<script type="text/javascript"
  src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbCutpanelRcvController.js">
</script>

<script>
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

    $('#soembcutpanelrcvFrm [id="buyer_id"]').combobox();
});
</script>
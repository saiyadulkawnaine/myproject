<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="buyerdevelopmenttabs">
 <div title="Start Up" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="buyerdevelopmentTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'team_name'" width="80">Team</th>
       <th data-options="field:'buyer_name'" width="150">Buyer</th>
       <th data-options="field:'product_type_id'" width="80">Product Type</th>
       <th data-options="field:'end_user_market'" width="80">End User Market</th>
       <th data-options="field:'existing_supplier'" width="80">Existing Supplier</th>
       <th data-options="field:'credit_rating'" width="80">Credit Rating</th>
       <th data-options="field:'credit_type_id'" width="80">Credit Type</th>
       <th data-options="field:'pay_term_id'" width="80">Pay Term</th>
       <th data-options="field:'penalty_clause'" width="80">Panelty Clause</th>
       <th data-options="field:'compliance_req'" width="80">Compliance Req.</th>
       <th data-options="field:'remarks'" width="80">Remarks</th>
       <th data-options="field:'status_id'" width="80">Status</th>


      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'General',footer:'#buyerdevelopmentFrmFt'"
    style="width: 450px; padding:2px">
    <form id="buyerdevelopmentFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <div class="col-sm-4 ">System ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="id" id="id" value="" readonly  />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Team</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('team_id', $team,'',array('id'=>'team_id','onchange'=>'MsBuyerDevelopment.getTeamMember(this.value)')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Marketing Member</div>
                                    <div class="col-sm-8">{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Buyer</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        </div>
                                </div> 
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Product Type</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('product_type_id', $fabricnature,'',array('id'=>'product_type_id')) !!}
                                        </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">End User Market</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="end_user_market" id="end_user_market" />
                                        
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Existing Supplier</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="existing_supplier" id="existing_supplier" />
                                        
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Credit Rating</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="credit_rating" id="credit_rating" />
                                        
                                    </div>
                                </div>

                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Credit Type</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('credit_type_id', $credittype,'',array('id'=>'credit_type_id')) !!}
                                        </div>
                                </div> 

                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Pay Term</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('pay_term_id', $payterm,'',array('id'=>'pay_term_id')) !!}
                                        </div>
                                </div> 
                                
                                
                               <div class="row middle">
                                    <div class="col-sm-4 req-text">Panelty Clause</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="penalty_clause" id="penalty_clause" />
                                        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Compliance Req.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="compliance_req" id="compliance_req" />
                                        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                        
                                    </div>
                                </div>

                                <div class="row middle">
                                        <div class="col-sm-4">Status</div>
                                        <div class="col-sm-8">
                                            {!! Form::select('status_id', $buyerdlvstatus,'',array('id'=>'status_id')) !!}
                                        </div>
                                </div> 
                            </code>
      </div>
     </div>
     <div id="buyerdevelopmentFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopment.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopment.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" zdy90 onClick="MsBuyerDevelopment.remove()">Delete</a>

     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Events" style="padding:2px">

  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="buyerdevelopmenteventTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'meeting_date'" width="80">Meeting Date</th>
       <th data-options="field:'meeting_type_id'" width="80">Meeting Type</th>
       <th data-options="field:'meeting_summary'" width="150">Meeting Summary</th>
       <th data-options="field:'next_action_plan'" width="80">Next Action Plan</th>
       <th data-options="field:'remarks'" width="80">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'General',footer:'#buyerdevelopmenteventFrmFt'"
    style="width: 450px; padding:2px">
    <form id="buyerdevelopmenteventFrm">
     <div id="container">
      <div id="body">
       <code>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Meeting Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="meeting_date" id="meeting_date" class="datepicker" />
                                        
                                    </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Meeting Type</div>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" id="id" value="" readonly  />
                                            <input type="hidden" name="buyer_development_id" id="buyer_development_id" value="" readonly  />
                                            {!! Form::select('meeting_type_id', $meetingtype,'',array('id'=>'meeting_type_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Meeting Summary</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="meeting_summary" id="meeting_summary" />
                                        
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4">Next Action Plan</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="next_action_plan" id="next_action_plan" />
                                        
                                    </div>
                                </div>
                                
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks" />
                                        
                                    </div>
                                </div>

                                
                            </code>
      </div>
     </div>
     <div id="buyerdevelopmenteventFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopmentEvent.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentEvent.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" zdy90 onClick="MsBuyerDevelopmentEvent.remove()">Delete</a>

     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Intermediary" style="padding:2px">

  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="buyerdevelopmentintmTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="40">ID</th>
       <th data-options="field:'buyer_name'" width="80">Buying House</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'General',footer:'#buyerdevelopmentintmFrmFt'"
    style="width: 450px; padding:2px">
    <form id="buyerdevelopmentintmFrm">
     <div id="container">
      <div id="body">
       <code>


                                <div class="row middle">
                                        <div class="col-sm-4 req-text">Buying House</div>
                                        <div class="col-sm-8">
                                            <input type="hidden" name="id" id="id" value="" readonly  />
                                            <input type="hidden" name="buyer_development_id" id="buyer_development_id" value="" readonly  />
                                            {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                        </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="buyerdevelopmentintmFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopmentIntm.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentIntm.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" zdy90 onClick="MsBuyerDevelopmentIntm.remove()">Delete</a>

     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Document Upload" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="buyerdevelopmentdocTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'original_name'" width="80">Original Name</th>
       <th data-options="field:'file_src'" width="80" formatter="MsBuyerDevelopmentDoc.formatfile">
        Upload Files</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'General',footer:'#buyerdevelopmentdocFrmFt'"
    style="width: 450px; padding:2px">
    <form id="buyerdevelopmentdocFrm">
     <div id="container">
      <div id="body">
       <code>

                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Name</div>
                                   <div class="col-sm-8">
                                    <input type="hidden" name="id" id="id" value="" readonly  />
                                    <input type="hidden" name="buyer_development_id" id="buyer_development_id" value="" readonly  />
                                        <input type="text" id="original_name" name="original_name" value="" />
                                   </div>
                                </div>
                               
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Upload</div>
                                   <div class="col-sm-8">
                                        <input type="file" id="file_upload" name="file_upload" value="" />              
                                   </div>
                                </div> 
                            </code>
      </div>
     </div>
     <div id="buyerdevelopmentdocFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopmentDoc.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentDoc.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" zdy90 onClick="MsBuyerDevelopmentDoc.remove()">Delete</a>

     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Order Forcasting" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft4'"
      style="height:300px; padding:2px">
      <form id="buyerdevelopmentorderFrm">
       <div id="container">
        <div id="body">
         <code>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Style Description</div>
                                <div class="col-sm-7">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="buyer_development_intm_id" id="buyer_development_intm_id" value="" />
                                    <input type="text" name="style_description" id="style_description">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">SMV</div>
                                <div class="col-sm-7">
                                    <input type="text" name="smv" id="smv">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Currency</div>
                                <div class="col-sm-7">
                                    {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5 req-text">Exch. Rate</div>
                                <div class="col-sm-7">
                                    <input type="text" name="exch_rate" id="exch_rate"  class="number integer"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-5">Remarks </div>
                                <div class="col-sm-7">
                                    <textarea name="remarks" id="remarks"></textarea>
                                </div>
                            </div>
                        </code>
        </div>
       </div>
       <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopmentOrder.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerdevelopmentorderFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentOrder.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="buyerdevelopmentorderTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'style_description'" width="100">Style Description</th>
         <th data-options="field:'smv'" width="60">SMV</th>
         <th data-options="field:'currency_name',align:'right'" width="100">Currency</th>
         <th data-options="field:'exch_rate',align:'right'" width="80">Exch. Rate</th>
         <th data-options="field:'remarks'" width="120">Remarks</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Order Details'" style="padding:2px">
    <div class="easyui-layout" data-options="fit:true">
     <div data-options="region:'north',border:true,title:'Add Details',iconCls:'icon-more',footer:'#ft5'"
      style="height:200px; padding:2px">
      <form id="buyerdevelopmentorderqtyFrm">
       <div id="container">
        <div id="body">
         <code>
                                    <table style="width:100%" border="1">
                                        <thead>
                                        <tr>
                                        <th width="80">Forcasted Qty</th>
                                        <th width="80">Forcasted Rate</th>
                                        <th width="80">Forcasted Amount</th>
                                        <th width="80">Received Qty</th>
                                        <th width="80">Received Rate</th>
                                        <th width="80">Received Amount</th>
                                        <th width="80">Est Shipdate</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="80"><input type="hidden" name="id" id="id" value="" />
                                                    <input type="hidden" name="buyer_development_order_id" id="buyer_development_order_id" value="" />
                                                    <input type="text" name="qty" id="qty" onchange="MsBuyerDevelopmentOrderQty.calculateAmount()"  class="number integer"/></td>
                                                <td width="80"><input type="text" name="rate" id="rate" onchange="MsBuyerDevelopmentOrderQty.calculateAmount()"  class="number integer"/></td>
                                                <td width="80"><input type="text" name="amount" id="amount"  class="number integer" readonly/></td>
                                                <td width="80"><input type="text" name="rcv_qty" id="rcv_qty" onchange="MsBuyerDevelopmentOrderQty.calculateRcvAmount()" class="number integer"/></td>
                                                <td width="80"><input type="text" name="rcv_rate" id="rcv_rate" onchange="MsBuyerDevelopmentOrderQty.calculateRcvAmount()" class="number integer"/></td>
                                                <td width="80"><input type="text" name="rcv_amount" id="rcv_amount"  class="number integer" readonly/></td>
                                                <td width="80"><input type="text" name="est_ship_date" id="est_ship_date"  class="datepicker"/></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </code>
        </div>
       </div>
       <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
         iconCls="icon-save" plain="true" id="save" onClick="MsBuyerDevelopmentOrderQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete"
         onClick="msApp.resetForm('buyerdevelopmentorderqtyFrm')">Reset</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
         iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerDevelopmentOrderQty.remove()">Delete</a>
       </div>
      </form>
     </div>
     <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
      <table id="buyerdevelopmentorderqtyTbl" style="width:100%">
       <thead>
        <tr>
         <th data-options="field:'id'" width="80">ID</th>
         <th data-options="field:'qty'" width="100">Qty</th>
         <th data-options="field:'rate'" width="60">Rate</th>
         <th data-options="field:'amount',align:'right'" width="100">Amount</th>
         <th data-options="field:'rcv_qty',align:'right'" width="80">Receive Qty</th>
         <th data-options="field:'rcv_rate'" width="120">Receive Rate</th>
         <th data-options="field:'rcv_amount'" width="120">Receive Amount</th>
         <th data-options="field:'est_ship_date'" width="120">Est Shipdate</th>
        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div>
  </div>
 </div>
</div>




<script type="text/javascript" src="<?php echo url('/');?>/js/Marketing/MsAllBuyerDevelopmentController.js"></script>

<script>
 (function(){
    $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
})(jQuery);
//$('#buyerdevelopmentFrm [id="team_id"]').combobox();
$('#buyerdevelopmentFrm [id="buyer_id"]').combobox();
$('#buyerdevelopmenteventFrm [id="meeting_type_id"]').combobox();
$('#buyerdevelopmentintmFrm [id="buyer_id"]').combobox();

</script>
<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="comExpTabs">
   <div title="Basic Information" style="padding:2px">
       <div class="easyui-layout" data-options="fit:true">
           <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
               <table id="exppiTbl" style="width:100%">
                   <thead>
                       <tr>
                           <th data-options="field:'id'" width="40">ID</th>
                           <th data-options="field:'pi_no'" width="100">PI No</th>
                           <th data-options="field:'company_id'" width="100">Company</th>
                           <th data-options="field:'buyer_id'" width="100">Buyer</th>
                           <th data-options="field:'pi_date'" width="100">PI Date</th>
                           <th data-options="field:'delivery_date'" width="100">Delivery Date</th>
                           <th data-options="field:'pi_validity_days'" width="100">Validity Days</th>
                           <th data-options="field:'itemclass_id'" width="100">Item Class</th>
                           <th data-options="field:'file_no'" width="100">File No</th>
                           <th data-options="field:'pay_term_id'" width="100">Pay Term</th>
                           
                       </tr>
                   </thead>
               </table>
           </div>
           <div data-options="region:'west',border:true,title:'Add Basic Information',footer:'#ft2'" style="width: 350px; padding:2px">
               <form id="exppiFrm">
                   <div id="container">
                       <div id="body">
                           <code>
                               <div class="row middle">
                                 <div class="col-sm-4 req-text">Company </div>
                                 <div class="col-sm-8">
                                     {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                     <input type="hidden" name="id" id="id" value="" />
                                 </div>
                             </div>
                               <div class="row middle" style="display: none">
                                    <div class="col-sm-4">Sys PI No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sys_pi_no" id="sys_pi_no" />
                                    </div>
                                 </div>
                               <div class="row middle">
                                 <div class="col-sm-4 req-text">PI No </div>
                                 <div class="col-sm-8">
                                     <input type="text" name="pi_no" id="pi_no" />
                                 </div>
                              </div>
                               <div class="row middle">
                                   <div class="col-sm-4 req-text">Item Class </div>
                                   <div class="col-sm-8">
                                       {!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}
                                   </div>
                               </div>
                               <div class="row middle">
                                 <div class="col-sm-4 req-text">Buyer </div>
                                 <div class="col-sm-8">
                                     {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                 </div>
                             </div>
                               <div class="row middle">
                                 <div class="col-sm-4">PI Validity Days </div>
                                 <div class="col-sm-8">
                                     <input type="text" name="pi_validity_days" id="pi_validity_days">
                                 </div>
                             </div>
                               <div class="row middle">
                                   <div class="col-sm-4 req-text">PI Date </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="pi_date" id="pi_date" class="datepicker" placeholder=" yy-mm-dd"/>
                                   </div>
                               </div>
                               <div class="row middle">
                                   <div class="col-sm-4">File No </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="file_no" id="file_no">
                                   </div>
                               </div>
                              <div class="row middle">
                                 <div class="col-sm-4 req-text">Pay Term </div>
                                 <div class="col-sm-8">
                                     {!! Form::select('pay_term_id', $payterm,'',array('id'=>'pay_term_id')) !!}
                                 </div>
                             </div>
                             <div class="row middle">
                                    <div class="col-sm-4">Tenor </div>
                                    <div class="col-sm-8">
                                       <input type="text" name="tenor" id="tenor" class="number integer">
                                    </div>
                              </div>
                              <div class="row middle">
                                    <div class="col-sm-4 req-text">Incoterm </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('incoterm_id', $incoterm,'',array('id'=>'incoterm_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Inco Term Place </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="incoterm_place" id="incoterm_place">
                                    </div>
                                </div>
                               <div class="row middle">
                                   <div class="col-sm-4 req-text">Delivery Date </div>
                                   <div class="col-sm-8">
                                       <input type="text" name="delivery_date" id="delivery_date" class="datepicker" placeholder="yy-mm-dd" />
                                   </div>
                               </div>
                               <div class="row middle">
                                    <div class="col-sm-4">Port of Entry </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="port_of_entry" id="port_of_entry">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Port of Loading </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="port_of_loading" id="port_of_loading">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Port of Discharge </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="port_of_discharge" id="port_of_discharge">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Final Destination </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="final_destination" id="final_destination">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">ETD Port </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="etd_port" id="etd_port">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">ETA Port </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="eta_port" id="eta_port">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">HS Code </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="hs_code" id="hs_code">
                                    </div>
                                </div>
                               <div class="row middle">
                                   <div class="col-sm-4">Remarks </div>
                                   <div class="col-sm-8">
                                       <textarea name="remarks" id="remarks"></textarea>
                                   </div>
                               </div>
                           </code>
                       </div>
                   </div>
                   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                       <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpPi.submit()">Save</a>
                       <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('exppiFrm')">Reset</a>
                       <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpPi.remove()">Delete</a>
                   </div>

               </form>
           </div>
       </div>
   </div>
   <div title="Sales Order" style="padding:2px">
       <div class="easyui-layout" data-options="fit:true">
           <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
               <table id="exppiorderTbl" style="width:100%">
                   <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                        <th data-options="field:'job_no'" width="100">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'item_description'" width="100">Item</th>    
                        <th data-options="field:'uom_name'" width="100"> UOM</th> 
                        <th data-options="field:'qty'" width="100" align="right">Qty</th> 
                        <th data-options="field:'rate'" width="100" align="right"> Rate</th> 
                        <th data-options="field:'amount'" width="100" align="right"> Amount</th>         
                        
                    </tr>
                </thead>
               </table>
           </div>
           <div data-options="region:'west',border:true,title:'Sales Order Details',footer:'#ft3'" style="width: 350px; padding:2px">
               <form id="exppiorderFrm">
                   <div id="container">
                       <div id="body">
                           <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="exp_pi_id" id="exp_pi_id" value="" />               
                                </div>
                               <div class="row middle">
                                    <div class="col-sm-4">Sales Order</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sale_order_no" id="sales_order_no" ondblclick="MsExpPiOrder.openExpPiOrderWindow()" placeholder=" Double Click" readonly/>
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Style Ref </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Job 
                                        No </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" disabled>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Order UOM </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="uom_name" id="uom_name"disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                        <div class="col-sm-4">Item </div>
                                        <div class="col-sm-8">   
                                        <input type="text" name="item_description" id="item_description" value="" disabled>
                                        </div>
                                    </div>
                                <div class="row middle">
                                    <div class="col-sm-4"> Order Qty </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="order_qty" id="order_qty" value="" class="number integer" readonly>
                                    </div>
                                </div>  
                                <div class="row middle">
                                        <div class="col-sm-4 req-text"> Tagged Qty </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="qty" id="qty" onchange="MsExpPiOrder.calculateAmount()" placeholder="write" class="number integer"/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4"> Balance Qty </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="balance_qty" id="balance_qty"  class="number integer" required />
                                        </div>
                                    </div>                          
                                <div class="row middle">
                                    <div class="col-sm-4"> Price/Unit </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="rate" id="rate" onchange="MsExpPiOrder.calculateAmount()" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4"> Amount </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" readonly class="number integer" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4"> Remarks </div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                           </code>
                       </div>
                   </div>
                   <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                       <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpPiOrder.submit()">Save</a>
                       <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('exppiorderFrm')">Reset</a>
                       <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExpPiOrder.remove()">Delete</a>
                   </div>
               </form>
           </div>
       </div>
   </div>
</div>
<div id="exporderwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="exppiordersearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>

                            <div class="row middle">
                                <div class="col-sm-4">Job </div>
                                <div class="col-sm-8">
                                        <input type="text" name="job_no" id="job_no" value="">
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsExpPiOrder.searchOrder()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="exppiordersearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'style_ref'" width="70">Style Ref</th>
                         <th data-options="field:'job_no'" width="100">Job No</th>
                        <th data-options="field:'sale_order_no'" width="100">Sale Order No</th>
                        <th data-options="field:'item_description'" width="100">Item</th>    
                        <th data-options="field:'uom_name'" width="100"> UOM</th> 
                        <th data-options="field:'qty'" width="100" align="right"> Order Qty</th> 
                        <th data-options="field:'rate'" width="100" align="right"> Rate</th> 
                        <th data-options="field:'amount'" width="100" align="right"> Amount</th>
                        <th data-options="field:'cumulative_qty'" width="100" align="right"> Cumulative Qty</th>
                        <th data-options="field:'balance_qty'" width="100" align="right"> Balance Qty</th>          
                        
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="MsExpPiOrder.closeExpPiOrderWindow()" style="width:80px">Close</a>
        </div>
    </div>
</div>


<div id="exptagorderwindow" class="easyui-window" title="Sales Order Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        
        <div data-options="region:'center',footer:'#exptagorderScsft'" style="padding:10px;">
          <form id="exptagorderFrm">
          <code id="exptagorderScs">
          </code>
          <div id="exptagorderScsft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
          <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExpPiOrder.submitBatch()">Save</a>
          <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('exptagorderFrm')" >Reset</a>
          </div>

          </form>
           
        </div>
       
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/Export/MsAllExpController.js"></script>
<script>
   $(".datepicker").datepicker({
       dateFormat: 'yy-mm-dd',
       changeMonth: true,
       changeYear: true
   });

</script>

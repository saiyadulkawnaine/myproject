<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="comlocalexplctabs">
   <div title="Local Export LC" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
         <div data-options="region:'center',border:true,title:' LC list'" style="padding:2px">
            <table id="localexplcTbl" style="width:100%">
               <thead>
                  <tr>
                     <th data-options="field:'id'" width="80">ID</th>
                     <th data-options="field:'local_lc_no'" width="100">LC No</th>
                     <th data-options="field:'beneficiary'" width="100">Beneficiary</th>
                     <th data-options="field:'buyer'" width="100">Buyer</th>
                     <th data-options="field:'lc_date'" width="100">LC Date</th>
                     <th data-options="field:'lc_value'" width="100" align="right">LC value</th>
                     <th data-options="field:'currency'" width="100">Currency</th>
                     <th data-options="field:'exch_rate'" width="100">Exch Rate</th>
		     <th data-options="field:'productionarea'" width="100">Prod.Area</th>
                  </tr>
               </thead>
            </table>
         </div>
         <div data-options="region:'west',border:true,title:'Add Local Export LC',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
            <div id="container">
               <div id="body">
                  <code>
                     <form id="localexplcFrm">
                        <div class="row middle">
                           <div class="col-sm-4 req-text">LC No</div>
                           <div class="col-sm-8">
                              <input type="text" name="local_lc_no" id="local_lc_no" />
                              <input type="hidden" name="id" id="id" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">LC Date</div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_date" id="lc_date" class="datepicker" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">LC Value </div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_value" id="lc_value" value="" class="number integer" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Currency</div>
                           <div class="col-sm-8">
                              {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Ex. Rate </div>
                           <div class="col-sm-8">
                              <input type="text" name="exch_rate" id="exch_rate" class="integer number"  />
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
                           <div class="col-sm-4 req-text">Production Area</div>
                           <div class="col-sm-8">
                               {!! Form::select('production_area_id', $productionarea,'',array('id'=>'production_area_id')) !!}
                           </div>
                       </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Beneficiary </div>
                           <div class="col-sm-8">
                              {!! Form::select('beneficiary_id', $company,'',array('id'=>'beneficiary_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Buyer</div>
                           <div class="col-sm-8">
                              {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Buyer's Bank</div>
                           <div class="col-sm-8">
                              <textarea name="buyers_bank" id="buyers_bank"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Exporter'sBank Branch</div>
                           <div class="col-sm-8">
                              {!! Form::select('exporter_bank_branch_id', $bankbranch,'',array('id'=>'exporter_bank_branch_id')) !!} 
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Lien Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="lien_date" id="lien_date" class="datepicker" placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Last Delivery Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="last_delivery_date" id="last_delivery_date" class="datepicker"
                                 placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4 req-text">Delivery Mode </div>
                           <div class="col-sm-8">
                              {!! Form::select('delivery_mode_id', $deliveryMode,'',array('id'=>'delivery_mode_id'))
                              !!}
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">LC Expire Date </div>
                           <div class="col-sm-8">
                              <input type="text" name="lc_expire_date" id="lc_expire_date" class="datepicker"
                                 placeholder="yy-mm-dd" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Delivery Place </div>
                           <div class="col-sm-8">
                              <input type="text" name="delivery_place" id="delivery_place" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">HS Code </div>
                           <div class="col-sm-8">
                              <input type="text" name="hs_code" id="hs_code" value="" />
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Customers LC/SC Detail</div>
                           <div class="col-sm-8">
                              <textarea name="customer_lc_sc" id="customer_lc_sc"></textarea>
                           </div>
                        </div>
                        <div class="row middle">
                           <div class="col-sm-4">Remarks</div>
                           <div class="col-sm-8">
                              <textarea name="remarks" id="remarks"></textarea>
                           </div>
                        </div>
                     </form>
                  </code>
               </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
               <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save"
                  plain="true" id="save" onClick="MsLocalExpLc.submit()">Save</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove"
                  plain="true" id="delete" onClick="msApp.resetForm('localexplcFrm')">Reset</a>
               <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"
                  plain="true" id="delete" onClick="MsLocalExpLc.remove()">Delete</a>
            </div>
         </div>
      </div>
   </div>
   <!--==========Lc Tag PI Tab=================-->
   <div title="Tag PI" style="padding:2px">
         <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Search'"
               style="width:450px; padding:2px">
               <div class="easyui-layout" data-options="fit:true">
               <div data-options="region:'north',border:true,title:'Add PI',iconCls:'icon-more',footer:'#lctagpift'"
               style="height:180px; padding:2px">
               <form id="lctagpisearchFrm">
                  <div id="container">
                     <div id="body">
                        <code>
                           <div class="row middle">
                              <div class="col-sm-4">PI No </div>
                              <div class="col-sm-8">
                                 <input type="text" name="pi_no" id="pi_no" />
                              </div>
                           </div>
                        </code>
                     </div>
                  </div>
                  <div id="lctagpift" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpLcTagPi.importLcPi()">Search</a>
                     <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('localexplctagpiFrm')">Reset</a>
                     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsLocalExpLcTagPi.submit()">Tag</a>
                     
                  </div>  
               </form>
               </div> 
               <div data-options="region:'center',border:true,title:'List'">
                   <table id="localexplctagpisearchTbl" style="width:100%">
                     <thead>
                        <tr>
                           <th data-options="field:'id'" width="40">ID</th>
                           <th data-options="field:'pi_no'" width="70">PI NO</th>
                           <th data-options="field:'qty'" width="100" align="right">PI Qty</th>
                           <th data-options="field:'rate'" width="100" align="right">Price/Unit</th>
                           <th data-options="field:'amount'" width="100" align="right">PI Value</th>
                        </tr>
                     </thead>
                  </table>
               </div>
               </div> 
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
               <table id="localexplctagpiTbl" style="width:100%">
                  <thead>
                     <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'local_exp_pi_id'" width="70">PI ID</th>
                        <th data-options="field:'customer_code'" width="60">Company</th>
                        <th data-options="field:'sales_company_code'" width="60">Customer</th>
                        <th data-options="field:'pi_no'" width="80">PI NO</th> 
                        <th data-options="field:'qty'" width="80" align="right">PI Qty</th>
                        <th data-options="field:'rate'" width="60" 
                        align="right">Price/Unit</th>
                        <th data-options="field:'amount'" width="80" align="right">PI Value</th>
                     </tr>
                  </thead>
               </table>
            </div>
         </div>
   </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Commercial/LocalExport/MsAllLocalLcController.js"></script>
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
         changeYear: true,
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      $('#localexplcFrm [id="buyer_id"]').combobox();
   })(jQuery);
</script>

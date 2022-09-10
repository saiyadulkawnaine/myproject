<div class="easyui-layout animated rollIn"  data-options="fit:true">
    {{-- <div data-options="region:'center',border:true,title:'Aop, Knitting & Dyeing Marketing Report'" style="padding:2px">
        <table id="subinbmarketingreportTbl" style="width:100%">
            <thead>
                <tr>         
                    
                    <th data-options="field:'company_name',halign:'center'" width="60">Company</th>
                    <th data-options="field:'prod_area_name',halign:'center'" width="60">Prod.Area</th>
                    <th data-options="field:'team_name',halign:'center'" width="60">Team</th>
                    <th data-options="field:'teammember',halign:'center'" width="80">Marketing Member</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="100" formatter="MsSubInbMarketingReport.formatimage">Customer</th>
                    <th data-options="field:'mkt_date',halign:'center'" width="70">Date</th>
                    <th data-options="field:'contact',halign:'center'" width="100">Contact</th>
                    <th data-options="field:'contact_no',halign:'center'" width="100">Contact No</th>
                    <th data-options="field:'refered_by',halign:'center'" width="100">Refered By</th>
                    
                    <th data-options="field:'sample_req_qty',halign:'center'" width="90" align="right">Sample Qty</th>
                    <th data-options="field:'qty',halign:'center'" width="90" align="right">Projected Qty</th>
                    <th data-options="field:'rate',halign:'center'" width="70" align="right">Quoted Rate</th>
                    <th data-options="field:'uom_id',halign:'center'" width="50" align="right">Uom</th>   
                    <th data-options="field:'amount',halign:'center'" width="100" align="right" formatter="MsSubInbMarketingReport.formatDetail">Amount</th>
                    <th data-options="field:'currency_id',halign:'center'" width="60">Currency</th>
                    <th data-options="field:'remarks',halign:'center'" width="350">Remarks</th>
                </tr>
            </thead>
        </table>
    </div> --}}
    <div data-options="region:'center',border:true,title:'Textile Marketing Report',footer:'#ft3'" style="padding:2px" id="subinbmarketingreportContainer">
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:300px; padding:2px">
    <form id="subinbmarketingreportFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row">
                     <div class="col-sm-5 req-text">Company</div>
                     <div class="col-sm-7">
                       {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                     </div>
                 </div>
                 <div class="row middle">
                    <div class="col-sm-5">Team</div>
                    <div class="col-sm-7">
                        {!! Form::select('team_id', $team,'',array('id'=>'team_id','onchange'=>'MsSubInbMarketingReport.getTeamMember(this.value)')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Marketing Member</div>
                    <div class="col-sm-7">
                        {!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}
                    </div>
                </div>
               <div class="row middle">
                   <div class="col-sm-5 req-text areaCon">Prod. Area</div>
                   <div class="col-sm-7">
                       {!! Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id')) !!}
                   </div>
               </div>
               <div class="row middle">
                    <div class="col-sm-5">Date </div>
                        <div class="col-sm-3" style="padding-right:0px">
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
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSubInbMarketingReport.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSubInbMarketingReport.resetForm('subinbmarketingreportFrm')" >Reset</a>
        </div>   
      </form>
    </div>
 </div>

  <div id="subinbDetailWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
        <table id="subinbDetailTbl" style="width:900px">
                    <thead>
                        <tr>
                        <th data-options="field:'colorrange',halign:'center'" width="100">Color Range</th>
                        <th data-options="field:'dyeing_type_id',halign:'center'" width="100">Dyeing type</th>
                        <th data-options="field:'fabricshape',halign:'center'" width="100">Fabric Shape</th>
                        <!----AOP---->
                        <th data-options="field:'aop_type',halign:'center'" width="80">AOP Type</th>
                        <th data-options="field:'from_coverage',halign:'center'" width="80" align="right">From <br/>Coverage%</th>
                        <th data-options="field:'to_coverage',halign:'center'" width="80" align="right">To <br/> Coverage %</th>
                        <th data-options="field:'from_impression',halign:'center'" width="80" align="right">From <br/> Impression</th>
                        <th data-options="field:'to_impression',halign:'center'" width="80" align="right">To <br/> Impression</th>
                        <!----knitting---->
                        <th data-options="field:'fabrication',halign:'center'" width="100">Construction</th>
                        <th data-options="field:'fabric_look_id',halign:'center'" width="100">Fabric Looks</th>
                        <th data-options="field:'gmtspart_id',halign:'center'" width="100">GMTS Part</th>
                        <th data-options="field:'yarncount_id'" width="100">Yarn Count</th>
                        <th data-options="field:'gauge',halign:'center'" width="100">Gauge</th>
                        <!------General------>
                        <th data-options="field:'from_gsm',halign:'center'" width="100">From GSM</th>
                        <th data-options="field:'to_gsm',halign:'center'" width="100">To GSM</th>
                        <th data-options="field:'sample_req_qty',halign:'center'" width="90" align="right">Sample Qty</th>
                        <th data-options="field:'qty',halign:'center'" width="100" align="right">Qty</th>
                        <th data-options="field:'rate',halign:'center'" width="100" align="right">Rate</th>
                        <th data-options="field:'amount',halign:'center'" width="100" align="right">Amount</th>
                        <th data-options="field:'remarks',halign:'center'" width="100">Remarks</th>
                        </tr>
                    </thead>
                </table>
    
    </div>

    <div id="subinbImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="subinbImageWindowoutput" src=""/>
    </div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Inbound/MsSubInbMarketingReportController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>
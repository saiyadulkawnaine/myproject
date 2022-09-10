<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Knit Plan Report'" style="padding:2px">
        <table id="plknitexireportTbl" style="width:1890px">
            <thead>
                <tr>
                <th data-options="field:'company_name'" width="60">Bnf. Company</th>
                <th data-options="field:'prod_company_name'" width="60">Prod. Company</th>
                <th data-options="field:'buyer_name'" width="80" align="center">GMT. Buyer</th>
                <th data-options="field:'style_ref'" width="80" align="center">GMT. Style</th>
                <th data-options="field:'sales_order_no'" width="100" align="center">GMT. Order</th>
                <th data-options="field:'knit_sale_order'" width="100" align="center">Knit Order</th>
                <th data-options="field:'customer_name'" width="100" align="center">Customer</th>
                <th data-options="field:'gmtspart'" width="100" align="center">GMT Part</th>
                <th data-options="field:'fabrication'" width="100">Fabrication</th>
                <th data-options="field:'fabric_color'" width="100">Fabric Color</th>
                <th data-options="field:'colorrange_name'" width="100">Color Range</th>
                <th data-options="field:'req_gsm_weight'" width="60">Req. GSM</th>
                <th data-options="field:'req_dia'" width="60">Req. Dia</th>
                <th data-options="field:'pl_gsm_weight'" width="60">PL. GSM</th>
                <th data-options="field:'pl_dia'" width="60">PL. Dia</th>
               
                <th data-options="field:'fabricshape'" width="70">Shape</th>
                <th data-options="field:'fabriclooks'" width="80">Look</th>
                <th data-options="field:'knit_company'" width="60">Dyed By</th>
                <th data-options="field:'machine_no'" width="80">Machine No</th>
                <th data-options="field:'brand'" width="80">Brand</th>
                <th data-options="field:'gauge'" width="80">M/C Gauge</th>
                <th data-options="field:'no_of_feeder'" width="80">No Of Feeder</th>
                <th data-options="field:'dia_width'" width="80">M/C Dia</th>
                <th data-options="field:'capacity'" width="80">Capacity</th>
                <th data-options="field:'pl_start_date'" width="80">Plan Start Date</th>
                <th data-options="field:'pl_end_date'" width="80">Plan End Date</th>
                <th data-options="field:'qty'" width="80" align="right">Qty</th>
                <th data-options="field:'prod_qty'" width="80" align="right">Knit Qty</th>
                <th data-options="field:'id'" width="60">Plan ID</th>
                </tr>
            </thead>
        </table>
        
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#plknitexireportFrmft'" style="width:350px; padding:2px">
    <form id="plknitexireportFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row">
                     <div class="col-sm-4 req-text">Company</div>
                     <div class="col-sm-8">
                       {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                     </div>
                 </div>
                 
               <div class="row middle">
                    <div class="col-sm-4 req-text">Plan Date </div>
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
        <div id="plknitexireportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitExiReport.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitExiReport.resetForm('plknitexireportFrm')" >Reset</a>
        </div>   
      </form>
    </div>
 </div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Kniting/MsPlKnitExiReportController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>
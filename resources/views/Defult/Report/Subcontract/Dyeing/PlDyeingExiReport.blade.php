<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Dyeing Plan Report'" style="padding:2px">
        <table id="pldyeingexireportTbl" style="width:1890px">
            <thead>
                <tr>
                <th data-options="field:'company_name'" width="60">Bnf. Company</th>
                <th data-options="field:'prod_company_name'" width="60">Prod. Company</th>
                <th data-options="field:'buyer_name'" width="80" align="center">GMT. Buyer</th>
                <th data-options="field:'style_ref'" width="80" align="center">GMT. Style</th>
                <th data-options="field:'sales_order_no'" width="100" align="center">GMT. Order</th>
                <th data-options="field:'dyeing_sale_order'" width="100" align="center">Dyeing Order</th>
                <th data-options="field:'customer_name'" width="100" align="center">Customer</th>
                <th data-options="field:'gmtspart'" width="100" align="center">GMT Part</th>
                <th data-options="field:'fabrication'" width="100">Fabrication</th>
                <th data-options="field:'fabric_color'" width="100">Fabric Color</th>
                <th data-options="field:'req_gsm_weight'" width="60">GSM</th>
                <th data-options="field:'req_dia'" width="60">Dia</th>
               
                <th data-options="field:'fabricshape'" width="70">Shape</th>
                <th data-options="field:'fabriclooks'" width="80">Look</th>
                <th data-options="field:'dyeing_company'" width="60">Dyed By</th>
                <th data-options="field:'machine_no'" width="80">Machine No</th>
                <th data-options="field:'brand'" width="80">Brand</th>
                <th data-options="field:'capacity'" width="80">Capacity</th>
                <th data-options="field:'pl_start_date'" width="80">Plan Start Date</th>
                <th data-options="field:'pl_end_date'" width="80">Plan End Date</th>
                <th data-options="field:'qty'" width="80" align="right">Qty</th>
                <th data-options="field:'dyed_qty'" width="80">Dyed Qty</th>
                <th data-options="field:'id'" width="60">Plan ID</th>
                </tr>
            </thead>
        </table>
        
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#pldyeingexireportFrmft'" style="width:350px; padding:2px">
    <form id="pldyeingexireportFrm">
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
        <div id="pldyeingexireportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlDyeingExiReport.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlDyeingExiReport.resetForm('pldyeingexireportFrm')" >Reset</a>
        </div>   
      </form>
    </div>
 </div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Dyeing/MsPlDyeingExiReportController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>
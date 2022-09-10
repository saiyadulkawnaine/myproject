<style>

#tnareporttblplancol{
    background:#2cf4f1;
    font-weight: bold;
    color: #FFFFFF;
}
#tnareporttblactulacol{
    background:#021344;
    font-weight: bold;
    color: #FFFFFF;
}
.datagrid-header-inner {
  float: left;
  width: 20000px;
}

    
</style>
<div class="easyui-layout"  data-options="fit:true" id="tnareportlayout">
    <div data-options="region:'center',border:true,title:'TNA Report'" style="padding:2px;">
        <table id="tnareportTbl">
            <thead frozen="true">
                <tr>
                    <th data-options="halign:'center',colspan:9,fixed:true,width:750">Order Info</th>
                </tr>
                <tr>
                    <th data-options="field:'company_code',halign:'center',width:60" class="companycode">Company</th>
                    <th data-options="field:'produced_company_code',halign:'center',width:60" >Producing <br/> Unit</th>
                    <th data-options="field:'buyer_name',halign:'center',width:120">Buyer</th>
                    <th data-options="field:'style_ref',halign:'center',width:80" >Style No</th>
                    <th data-options="field:'team_member_name',halign:'center',width:100" >Dealing <br/>Merchant</th>
                    <th data-options="field:'sale_order_no',halign:'center',width:80" >Order No</th>
                    <th data-options="field:'ship_date',halign:'center',width:80" >Ship Date</th>
                    <th data-options="field:'lead_time',halign:'center',width:80" >Lead Time</th>
                    <th data-options="field:'po_qty',halign:'center',width:90"  align="right">Order Qty <br/> (Pcs)</th>
                    
                </tr>
            </thead>
            <thead>
                <tr>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Dyed Yarn Receive</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Yarn Issue</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Kniting</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Dyeing</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >AOP</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Dyeing  Finishing</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Trims In-House</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >PP Sample</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Cutting</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Screen Print</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Sewing</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Finishing</th>
                    <th data-options="halign:'center',colspan:6,fixed:true,width:420" >Inspection</th>
                </tr>    
                <tr>

                    <th data-options="field:'dyed_yarn_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'dyed_yarn_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_dyed_yarn_receive_date',halign:'center',styler:MsTnaReport.dyedyarnaclstart" width="80" id="tnareporttblactulacol">Actual  Start</th>
                    <th data-options="field:'max_dyed_yarn_receive_date',halign:'center',styler:MsTnaReport.dyedyarnaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'dyed_yarn_start_delay',halign:'center'" width="50" align="center"> Delay <br/> Start</th>
                    <th data-options="field:'dyed_yarn_end_delay',halign:'center'" width="50" align="center">  Delay<br/> End</th>
                    

                    <th data-options="field:'yarn_isu_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'yarn_isu_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_yarn_isu_date',halign:'center',styler:MsTnaReport.yarnisuaclstart" width="80" id="tnareporttblactulacol">Actual  Start</th>
                    <th data-options="field:'max_yarn_isu_date',halign:'center',styler:MsTnaReport.yarnisuaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'yarn_isu_start_delay',halign:'center'" width="50" align="center"> Delay <br/> Start</th>
                    <th data-options="field:'yarn_isu_end_delay',halign:'center'" width="50" align="center">  Delay<br/> End</th>


                    <th data-options="field:'knit_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'knit_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_knit_date',halign:'center',styler:MsTnaReport.knitaclstart" width="80" id="tnareporttblactulacol">Actual   Start</th>
                    <th data-options="field:'max_knit_date',halign:'center',styler:MsTnaReport.knitaclend" width="80" id="tnareporttblactulacol"> Actual   End</th>
                    <th data-options="field:'knit_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start </th>
                    <th data-options="field:'knit_end_delay',halign:'center'" width="50" align="center">  Delay<br/> End</th>


                    <th data-options="field:'dyeing_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'dyeing_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_dyeing_date',halign:'center',styler:MsTnaReport.dyeingaclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_dyeing_date',halign:'center',styler:MsTnaReport.dyeingaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'dyeing_start_delay',halign:'center'" width="50" align="center">Delay<br/>Start </th>
                    <th data-options="field:'dyeing_end_delay',halign:'center'" width="50" align="center"> Delay<br/>End </th> 


                    <th data-options="field:'aop_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'aop_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_aop_date',halign:'center',styler:MsTnaReport.aopaclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_aop_date',halign:'center',styler:MsTnaReport.aopaclend" width="80" id="tnareporttblactulacol">Actual  End</th>
                    <th data-options="field:'aop_start_delay',halign:'center'" width="50" align="center">Delay<br/>Start </th>
                    <th data-options="field:'aop_end_delay',halign:'center'" width="50" align="center"> Delay <br/>End </th> 


                    <th data-options="field:'dyeingfinish_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'dyeingfinish_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_dyeingfinish_date',halign:'center',styler:MsTnaReport.dyeingfinishaclstart" width="80" id="tnareporttblactulacol">Actual   Start</th>
                    <th data-options="field:'max_dyeingfinish_date',halign:'center',styler:MsTnaReport.dyeingfinishaclend" width="80" id="tnareporttblactulacol">Actual   End</th>
                    <th data-options="field:'dyeingfinish_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start </th>
                    <th data-options="field:'dyeingfinish_end_delay',halign:'center'" width="50" align="center">  Delay <br/> End</th> 


                    <th data-options="field:'trim_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'trim_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_trim_date',halign:'center',styler:MsTnaReport.trimaclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_trim_date',halign:'center',styler:MsTnaReport.trimaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'trim_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'trim_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'pp_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'pp_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_pp_date',halign:'center',styler:MsTnaReport.ppaclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_pp_date',halign:'center',styler:MsTnaReport.ppaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'pp_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'pp_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'cut_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'cut_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_cut_date',halign:'center',styler:MsTnaReport.cutaclstart" width="80" id="tnareporttblactulacol">Actual   Start</th>
                    <th data-options="field:'max_cut_date',halign:'center',styler:MsTnaReport.cutaclend" width="80" id="tnareporttblactulacol">Actual  End</th>
                     <th data-options="field:'cut_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'cut_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'embsp_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'embsp_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_rcv_scr_date',halign:'center',styler:MsTnaReport.embspaclstart" width="80" id="tnareporttblactulacol">Actual  Start</th>
                    <th data-options="field:'max_rcv_scr_date',halign:'center',styler:MsTnaReport.embspaclend" width="80" id="tnareporttblactulacol">Actual  End</th>
                    <th data-options="field:'embsp_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'embsp_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'sew_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'sew_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_sew_date',halign:'center',styler:MsTnaReport.sewaclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_sew_date',halign:'center',styler:MsTnaReport.sewaclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'sew_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'sew_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'fin_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'fin_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_car_date',halign:'center',styler:MsTnaReport.caraclstart" width="80" id="tnareporttblactulacol">Actual Start</th>
                    <th data-options="field:'max_car_date',halign:'center',styler:MsTnaReport.caraclend" width="80" id="tnareporttblactulacol">Actual End</th>
                    <th data-options="field:'car_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'car_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 


                    <th data-options="field:'insp_start_date',halign:'center'" width="80" id="tnareporttblplancol">Plan Start</th>
                    <th data-options="field:'insp_end_date',halign:'center'" width="80" id="tnareporttblplancol">Plan End</th>
                    <th data-options="field:'min_insp_date',halign:'center',styler:MsTnaReport.inspaclstart" width="80" id="tnareporttblactulacol">Actual  Start</th>
                    <th data-options="field:'max_insp_date',halign:'center',styler:MsTnaReport.inspaclend" width="80" id="tnareporttblactulacol">Actual  End</th>
                    <th data-options="field:'insp_start_delay',halign:'center'" width="50" align="center">Delay <br/>Start</th>
                    <th data-options="field:'insp_end_delay',halign:'center'" width="50" align="center"> Delay <br/> End</th> 
               </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="tnareportFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row middle">
                        <div class="col-sm-4 req-text">Ship Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                            <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                        </div>
                        <div class="col-sm-4" style="padding-left:0px">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4"> Company</div>
                        <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                        </div>
                    </div>
                   	<div class="row middle">
                        <div class="col-sm-4">Buyer </div>
                        <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                    </div>
                            
                    
                   
                   
                    
              </code>
           </div>
        </div>
        <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsTnaReport.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaReport.resetForm('tnareportFrm')" >Reset</a>
        </div>

      </form>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsTnaReportController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    </script>

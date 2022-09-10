<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Line Wise Hourly Production'" style="padding:2px">
    <table id="prodgmtlinewisehourlyTbl" border="1" style="width:1890px">
        <thead>
            <tr>
                <th data-options="halign:'center'" width="80">1</th>
                <th data-options="halign:'center'" width="100">2</th>
                <th data-options="halign:'center'" width="80">3</th>
                <th data-options="halign:'center'" width="80">4</th>
                <th data-options="halign:'center'" width="80">5</th>
                <th data-options="halign:'center'"  width="100" align="left" >6 </th>
                <th data-options="halign:'center'" width="40" align="right">7</th>
                <th data-options="halign:'center'" width="40" align="right">8</th>
                <th data-options="halign:'center'" width="40" align="right">9  </th>
                <th data-options="halign:'center'" width="40" align="right" >10</th>
                <th data-options="halign:'center'" width="80" align="right" >11</th>
                <th data-options="halign:'center'" width="80" align="right" >12</th>
                <th data-options="halign:'center'" width="60" align="right" >13</th>
                <th data-options="halign:'center'" width="60" align="right" >14</th>
                <th data-options="halign:'center'"  width="60" align="right">15</th> 
                <th data-options="halign:'center'"  width="60" align="right"  >16
                </th> 
                <th data-options="halign:'center'"  width="60" align="right">17
                </th>
                <th data-options="halign:'center'"  width="60" align="right" >17
                </th>
                <th data-options="halign:'center'"  width="60" align="right">19
                </th>
                <th data-options="halign:'center'"  width="60" align="right">20
                </th>
                <th data-options="halign:'center'"  width="60" align="right">21
                </th>
                <th data-options="halign:'center'"  width="60" align="right">22
                </th>
                <th data-options="halign:'center'"  width="60" align="right">23
                </th>
                <th data-options="halign:'center'"  width="60" align="right">24
                </th>
                <th data-options="halign:'center'"  width="60" align="right">25
                </th>
                <th data-options="halign:'center'"  width="60" align="right">26
                </th>
                <th data-options="halign:'center'"  width="60" align="right">27
                </th>
                <th data-options="halign:'center'"  width="60" align="right">28
                <th data-options="halign:'center'"  width="60" align="right">29
                <th data-options="halign:'center'"  width="60" align="right">30
                </th>
                <th data-options="halign:'center'"  width="60" align="right" >31
                </th>
                <th data-options="halign:'center'" width="80" align="right">32</th>                
                <th data-options="halign:'center'" width="80" align="right" >33</th>
                <th data-options="halign:'center'" width="80" align="right" >34</th>
                <th data-options="halign:'center'" width="80" align="right">35  </th>  
                <th data-options="halign:'center'" width="60" align="right">36  </th>
                <th data-options="halign:'center'" width="60" align="right">37  </th>
                <th data-options="halign:'center'" width="180" align="right">38</th>
                <th data-options="halign:'center'"  width="60" align="left">39
                <th data-options="halign:'center'"  width="60" align="left">40
                </th>
                <th data-options="halign:'center'"  width="60" align="right"> 41</th>
                <th data-options="halign:'center'" width="50" align="right">42</th>               
                <th data-options="halign:'center'" width="80" align="right">43 </th>
                <th data-options="halign:'center'" width="100" align="right">44</th>
                <th data-options="halign:'center'" width="100" align="right">45</th>
            </tr>
            <tr>
                <th data-options="field:'apm',halign:'center'" width="80">Supervisor</th>
                <th data-options="field:'line',halign:'center'" width="100">Line</th>
                <th data-options="field:'buyer_code',halign:'center'" width="80">Buyer</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Order no.</th>
                <th data-options="field:'totday',halign:'center'" width="80">Running Day</th>
                <th data-options="field:'item_description',halign:'center'"  width="100" align="left" formatter="MsProdGmtLineWiseHourly.formatimage">GMT Item </th>
                <th data-options="field:'operator',halign:'center'" width="40" align="right">Used <br/>Opt.</th>
                <th data-options="field:'helper',halign:'center'" width="40" align="right">Used <br/>Hlp.</th>
                <th data-options="field:'manpower',halign:'center'" width="40" align="right">TTL.<br/> Mnp  </th>
                <th data-options="field:'wh',halign:'center'" width="40" align="right" >WH </th>
                <th data-options="field:'actual_terget',halign:'center'"  width="60" align="right">Req.Tgt/Hr</th>
                <th data-options="field:'target_per_hour',halign:'center'"  width="60" align="right">Tgt/Hr </th>
                
                <th data-options="field:'sew7am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"  formatter="MsProdGmtLineWiseHourly.formatdetail7am">7am
                <th data-options="field:'sew8am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"  formatter="MsProdGmtLineWiseHourly.formatdetail8am">8am
                <th data-options="field:'sew9am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"  formatter="MsProdGmtLineWiseHourly.formatdetail9am">9am
                </th> 
                <th data-options="field:'sew10am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail10am">10am
                </th>
                <th data-options="field:'sew11am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail11am">11am
                </th>
                <th data-options="field:'sew12pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail12pm">12pm
                </th>
                <th data-options="field:'sew1pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail1pm">1pm
                </th>
                <th data-options="field:'sew2pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail2pm">2pm
                </th>
                <th data-options="field:'sew3pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail3pm">3pm
                </th>
                <th data-options="field:'sew4pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail4pm">4pm
                </th>
                <th data-options="field:'sew5pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail5pm">5pm
                </th>
                <th data-options="field:'sew6pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail6pm">6pm
                </th>
                <th data-options="field:'sew7pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail7pm">7pm
                </th>
                <th data-options="field:'sew8pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail8pm">8pm
                </th>
                <th data-options="field:'sew9pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right"formatter="MsProdGmtLineWiseHourly.formatdetail9pm">9pm
                </th>
                <th data-options="field:'sew10pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail10pm">10pm
                <th data-options="field:'sew11pm_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail11pm">11pm
                <th data-options="field:'sew12am_qty',halign:'center',styler:MsProdGmtLineWiseHourly.targetprice"  width="60" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail12am">12am
                </th>
                <th data-options="field:'sew_qty',halign:'center'" width="80" align="right" formatter="MsProdGmtLineWiseHourly.formatdetail">Line.<br/> Achievement</th>
                <th data-options="field:'day_target',halign:'center'" width="80" align="right" >Day<br/> Target</th>
                <th data-options="field:'target_per_hour_var',halign:'center'" width="80" align="right" >TGT<br/> Variance</th>
                <th data-options="field:'target_per_hour_ach',halign:'center'" width="80" align="right" >TGT<br/> Achieve %</th>
                <th data-options="field:'capacity_qty',halign:'center'" width="80" align="right">Capacity<br/> Qty.  </th>  
                <th data-options="field:'capacity_dev',halign:'center'" width="60" align="right">Capacity <br/>Variance  </th>
                <th data-options="field:'capacity_ach',halign:'center'" width="60" align="right">Capacity <br/>Achieve %  </th>
                <th data-options="field:'remarks',halign:'center'" width="180" align="right">Comments</th>
                <th data-options="field:'prodused_fob',halign:'center'"  width="60" align="left">Sewing <br/>FOB Value
                <th data-options="field:'cm_earned_usd',halign:'center'"  width="60" align="left">CM
                </th>
                <th data-options="field:'smv',halign:'center'"  width="60" align="left">SMV
                </th>
                <th data-options="field:'smv_used',halign:'center'"  width="60" align="right"> Used <br/> Mnts./ Pcs
                </th>
                <th data-options="field:'effi_per',halign:'center'" width="50" align="right">Effi. %</th>  
                <th data-options="field:'produced_mint',halign:'center'" width="80" align="right">Prod.<br/> Mnts. </th>  
                <th data-options="field:'used_mint',halign:'center'" width="100" align="right">Available Mnts.</th>    
            </tr>
        </thead>
    </table>
</div>
<div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
    <form id="prodgmtlinewisehourlyFrm">
    <div id="container">
         <div id="body">
           <code>  
                <div class="row middle" style="display: none">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">
                    <input type="hidden" name="line_id" id="line_id"/>
                    </div>
                </div>            
                <div class="row middle">
                    <div class="col-sm-4 req-text">Company </div>
                    <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Buyer </div>
                    <div class="col-sm-8">
                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border:2')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Sewing. Date</div>
                    <div class="col-sm-8">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" value="<?php echo date('Y-m-d') ?>" />
                        
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Order Source</div>
                    <div class="col-sm-8">
                        {!! Form::select('order_source_id', $ordersource,'',array('id'=>'order_source_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Prod. Sourse </div>
                    <div class="col-sm-8">
                        {!! Form::select('prod_source_id', $productionsource,'',array('id'=>'prod_source_id')) !!}
                    </div>
                </div>      
                <div class="row middle">
                    <div class="col-sm-4">Prod. Company</div>
                    <div class="col-sm-8">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Location </div>
                    <div class="col-sm-8">
                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Shift Name </div>
                    <div class="col-sm-8">
                        {!! Form::select('shiftname_id', $shiftname,'',array('id'=>'shiftname_id')) !!}
                    </div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtLineWiseHourly.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtLineWiseHourly.resetForm('prodgmtcartonqtyFrm')" >Reset</a>
    </div>

  </form>
</div>
</div>
<div id="linewisehourlyImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <div id="linewisehourlyimagegrid" style="width: 100%; height: 100%">
    </div>
</div>

<div id="linewisehourlyDetailWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="prodgmtlinewisehourlydetailsTbl" border="1" style="width:1890px">
        <thead>
            <tr>
            <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">Sales Order</th>
            <th data-options="field:'item_description',halign:'center'" width="80">Gmt Item</th>
            <th data-options="field:'color_name',halign:'center'" width="80">Color</th>
            <th data-options="field:'size_name',halign:'center'" width="80">Size</th>
            <th data-options="field:'smv',halign:'center'" width="80">SMV</th>
            <th data-options="field:'sewing_effi_per',halign:'center'" width="80">Eff. %</th>
            <th data-options="field:'sew_qty',halign:'center'" align="right" width="80">Qty</th>
        </tr>
    </thead>
    
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtLineWiseHourlyController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
</script>


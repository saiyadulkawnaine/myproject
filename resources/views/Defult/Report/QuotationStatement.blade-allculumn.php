<div class="easyui-layout animated rollIn"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="quotationstatementTbl" style="width:1890px">
<thead>
    <tr>
        <th data-options="field:'pdf',halign:'center'" width="40" halign:'center' formatter="MsQuotationStatement.formatpdf" align="center">Details</th>
        <th data-options="field:'team_member',halign:'center'" width="70" halign:'center'>Team <br/> Member</th>
        <th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
        <th data-options="field:'style_description',halign:'center'" width="100">Style Desc.</th>
        <th data-options="field:'season_name',halign:'center'" width="80">Season</th>
        <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
        <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsQuotationStatement.formatimage" width="30">Image</th>
        <th data-options="field:'offer_qty',halign:'center'" width="80" align="right">Offered Qty</th>
        <th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
        <th data-options="field:'est_ship_date',halign:'center'" width="80">Est. Ship Date</th>
        <th data-options="field:'quot_date',halign:'center'" width="80"> Costing date</th>

        <th data-options="field:'fab_amount',halign:'center'" width="60" align="right">Fabric <br/>Cost /Dzn</th>
        <th data-options="field:'yarn_amount',halign:'center'" width="60" align="right">Yarn Cost/ <br/> Dzn</th>
        <th data-options="field:'prod_amount',halign:'center'" width="60" align="right">Fabric Prod. <br/> Cost /Dzn</th>
        <th data-options="field:'trim_amount',halign:'center'" width="60" align="right">Trims Cost /Dzn</th>
        <th data-options="field:'emb_amount',halign:'center'" width="60" align="right">Embel. <br/>Cost /Dzn</th>
        <th data-options="field:'cm_amount',halign:'center'" width="60" align="right">CM Cost / <br/>Dzn</th>
        <th data-options="field:'other_amount',halign:'center'" width="60" align="right">Other Cost / <br/>Dzn</th>
        <th data-options="field:'commercial_amount',halign:'center'" width="60" align="right">Commercial <br/> Cost /Dzn</th>
        <th data-options="field:'total_cost',halign:'center'" width="60" align="right">Total Cost <br/> /Dzn</th>
        <th data-options="field:'profit_amount',halign:'center',styler:MsQuotationStatement.frofitformat" width="60" align="right">Profit <br/> /Dzn</th>
         <th data-options="field:'price_bfr_commission',halign:'center'" width="60" align="right">Price <br/> Before Comm. /Dzn</th>
         <th data-options="field:'commission_amount',halign:'center'" width="60" align="right">Comm</th>
         <th data-options="field:'price_aft_commission',halign:'center'" width="60" align="right">Price After<br/> Comm. /Pcs</th>
         <th data-options="field:'price',halign:'center',styler:MsQuotationStatement.quotedprice" width="60" align="right">Quote Price /Pcs</th>
         <th data-options="field:'t_price',halign:'center'" width="60" align="right">Target Price <br/>/Dzn</th>
        <th data-options="field:'remarks',halign:'center'" width="100" >Remarks</th>
        <th data-options="field:'id'" width="50">ID</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New CadCon',footer:'#ft2'" style="width:350px; padding:2px">
<form id="quotationstatementFrm">
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
                    <div class="col-sm-4 req-text">Buyer </div>
                    <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Style Ref</div>
                    <div class="col-sm-8">
                   <input type="text" name="style_ref" id="style_ref" />

                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Job No </div>
                    <div class="col-sm-8"><input type="text" name="job_no" id="job_no" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Ship Date </div>
                    <div class="col-sm-4" style="padding-right:0px"><input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" /></div>
                    <div class="col-sm-4" style="padding-left:0px"><input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsQuotationStatement.get()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsQuotationStatement.resetForm('quotationstatementFrm')" >Reset</a>
    </div>

  </form>
</div>
</div>
<div id="quotationstatementImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="quotationstatementImageWindowoutput" src=""/>
    </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsQuotationStatementController.js"></script>


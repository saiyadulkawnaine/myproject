<div class="easyui-layout"  data-options="fit:true" id="centralsewingplanlayout">
    <div data-options="region:'center',border:true,title:'Ship Date Wise Central Sewing Plan'" style="padding:2px">
        <table id="centralsewingplanTbl" style="width:100%">
            <thead>    
                <tr>
                    <th data-options="field:'entry_id',halign:'center'" width="60">Entry ID</th>
                    <th data-options="field:'company_code',halign:'center'" width="60">Company</th>
                    <th data-options="field:'produced_company_code',halign:'center'" width="60">Producing Compamy Unit</th>
                    <th data-options="field:'team_ld_name',halign:'center'" width="80" >Team Leader<br/>Leader</th>
                    <th data-options="field:'team_member_name',halign:'center'" width="100">Dealing <br/>Merchant</th>
                    <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
                    <th data-options="field:'buyer_name',halign:'center'" width="120">Buyer</th>
                    <th data-options="field:'buying_house_name',halign:'center'"  width="70">Buying House</th>
                    <th data-options="field:'style_ref',halign:'center'" width="80">Style No</th>
                    <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                    <th data-options="field:'department_name',halign:'center'" width="80">Prod. Dept</th>
                    <th data-options="field:'item_description',halign:'center'" width="100">GMT Item</th>
                    <th data-options="field:'flie_src',halign:'center',align:'center'" formatter="MsCentralSewingPlan.formatimage" width="30">Image</th>
                    <th data-options="field:'date_from',halign:'center'" width="80">Sew Start Date</th>
                    <th data-options="field:'date_to',halign:'center'" width="80">Sew End Date</th>
                    <th data-options="field:'qty',halign:'center'" nowrap="false" width="80" align="right"> Plan Qty</th>
                    <th data-options="field:'sewing_days',halign:'center'" width="100" align="right">Sewing Days</th>
                    <th data-options="field:'no_of_line',halign:'center'" width="100" align="right">No Of Line (Tips)</th>
                    <th data-options="field:'smv',halign:'center'" width="60" align="right">SMV</th>
                    <th data-options="field:'booked_minute',halign:'center'" width="100" align="right">Booked Minute</th>
                    <th data-options="field:'booked_hour',halign:'center'" width="100" align="right">Booked Hour</th>
                    <th data-options="field:'remarks',halign:'center'" width="200">Remarks</th>

               </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="centralsewingplanFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row middle">
                        <div class="col-sm-4 req-text">Tgt. Date </div>
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
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" plain="true" id="save" onClick="MsCentralSewingPlan.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCentralSewingPlan.resetForm('centralsewingplanFrm')" >Reset</a>
        </div>

      </form>
    </div>
</div>
<div id="centralsewingplanImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="centralsewingplanImageWindowoutput" src=""/>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/report/MsCentralSewingPlanController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    </script>

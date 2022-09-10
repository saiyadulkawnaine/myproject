<div class="easyui-layout animated rollIn"  data-options="fit:true" id="dyechemstockatreorderlevelpanel">
    <div data-options="region:'center',border:true,title:'Dyes & Chemical Stock Report At Reorder Level'" style="padding:2px">
        
        
                <table id="dyechemstockatreorderlevelTbl" style="width:1890px">
                    <thead>
                        <tr>
                            <th data-options="field:'id',halign:'center'" width="80" >ID</th>
                            <th data-options="field:'itemcategory_name',halign:'center'" width="100" >Category</th>
                            <th data-options="field:'itemclass_name',halign:'center'" align="left" width="100">Item Class</th>
                            <th data-options="field:'sub_class_name',halign:'center'" align="left" width="100">Sub Class</th>
                            <th data-options="field:'item_desc',halign:'center'" width="250">Item Description</th>
                            <th data-options="field:'specification',halign:'center'" width="250">Specification</th>
                            <th data-options="field:'uom_code',halign:'center'" width="50">UOM</th>
                            <th data-options="field:'stock_qty',halign:'center'" width="80" align="right">Stock Qty</th>
                            <th data-options="field:'min_level',halign:'center'" width="80" align="right">Min. Stock Qty</th>
                            <th data-options="field:'reorder_level',halign:'center'" width="80" align="right">Reorder Level</th>
                            <th data-options="field:'avg_month_cons',halign:'center'" width="80" align="right">Avg. Month Cons.</th>
                            <th data-options="field:'avg_day_cons',halign:'center'" width="80" align="right">Avg. Day Cons.</th>
                            <th data-options="field:'req_qty',halign:'center'" width="80" align="right">Req. Qty</th>
                            <th data-options="field:'rate',halign:'center'" width="80" align="right">Avg. Rate</th>
                            <th data-options="field:'req_amount',halign:'center'" width="120" align="right">Req. Amount</th>
                            <th data-options="field:'supplier_name',halign:'center'" width="120" align="left">Supplier</th>
                           
                        </tr>
                    </thead>
                </table>
            
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#dyechemstockatreorderlevelFrmFt'" style="width:350px; padding:2px">
        <form id="dyechemstockatreorderlevelFrm">
            <div id="container">
                <div id="body">
                <code>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">As On</div>
                        
                        <div class="col-sm-8">
                            <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  As On" />
                        </div>
                    </div>
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Average Of</div>
                        <div class="col-sm-8">
                            <input type="text" name="avg_of" id="avg_of" class="number integer" placeholder=" Days"/>
                        </div>
                    </div>             
                    <div class="row middle">
                        <div class="col-sm-4 req-text">Required For</div>
                        <div class="col-sm-8">
                            <input type="text" name="req_for" id="req_for" class="number integer" placeholder=" Days"/>
                        </div>
                    </div>  
                     <div class="row middle">
                        <div class="col-sm-4">Company</div>
                        <div class="col-sm-8">
                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}                        
                    </div>
                </div>
                    
                    <div class="row middle">
                        <div class="col-sm-4">Item Category</div>
                        <div class="col-sm-8">
                        {!! Form::select('item_category_id', $itemcategory,'',array('id'=>'item_category_id')) !!}                        
                    </div>
                    </div>
                    
                    
                    <!--<div class="row middle">
                        <div class="col-sm-4">Type</div>
                        <div class="col-sm-8">
                            <input type="text" name="type" id="type" />
                        </div>
                    </div>  --> 
                    
                              
                </code>
            </div>
            </div>
            <div id="dyechemstockatreorderlevelFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsDyeChemStockAtReorderLevel.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsDyeChemStockAtReorderLevel.resetForm('dyechemstockatreorderlevelFrm')" >Reset</a>
            </div>
      </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Inventory/MsDyeChemStockAtReorderLevelController.js"></script>
<script>
    $(".datepicker" ).datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true
    });
    $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
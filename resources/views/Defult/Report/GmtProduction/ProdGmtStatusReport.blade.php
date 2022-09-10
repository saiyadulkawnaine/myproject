<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Garments Status',footer:'#ft3'" style="padding:2px" id="prodgmtstatusreportcontainer">

    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:350px; padding:2px">
        <form id="prodgmtstatusreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" onDblClick="MsProdGmtStatusReport.openGmtStyleWindow()" placeholder=" Double Click" readonly/>
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Buyer </div>
                            <div class="col-sm-8">
                                <input type="text" name="buyer_name" id="buyer_name" value="" disabled/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Dealing Merchant</div>                     
                            <div class="col-sm-8">
                                <input type="text" name="team_member_name" id="team_member_name" value="" disabled/>
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtStatusReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px; border-radius:1px" iconCls="icon-pdf" plain="true" id="pdf" onClick="MsProdGmtStatusReport.pdf()">PDF</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdGmtStatusReport.resetForm()" >Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Style Filtering Search Window --}}
<div id="gmtstylesearchWindow" class="easyui-window" title="Style Search Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1200px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="gmtstylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
                                <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                                    <div class="col-sm-2 req-text">Style Ref. </div>
                                <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-2">Style Des.  </div>
                                <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProdGmtStatusReport.searchGmtStyle()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="gmtstylesearchTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="50">ID</th>
                        <th data-options="field:'buyer'" width="70">Buyer</th>
                        <th data-options="field:'receivedate'" width="80">Receive Date</th>
                        <th data-options="field:'style_ref'" width="120">Style Refference</th>
                        <th data-options="field:'style_description'" width="150">Style Description</th>
                        <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                        <th data-options="field:'productdepartment'" width="80">Product Department</th>
                        <th data-options="field:'season'" width="80">Season</th>
                        <th data-options="field:'uom_name'" width="80">UOM</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#gmtstylesearchWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>

<div id="sewingWindow" class="easyui-window" title="Line wise Sewing Qty Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:860px;height:100%;padding:2px;">
    <div id="containerDocWindow" style="width:100%;height:100;padding:0px;">

    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/GmtProduction/MsProdGmtStatusReportController.js"></script>
<script>
$(".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
</script>
<div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Production Achivment'" style="padding:2px">
        <div id="pcafabriccolorsizematrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#prodfabriccapacityachievementft4'" style="width:350px; padding:2px">
        <form id="prodfabriccapacityachievementFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date </div>
                            <div class="col-sm-4" style="padding-right:0px; display: none ">
                                <input type="text" name="capacity_date_from" id="capacity_date_from" class="datepicker" placeholder="From" value=""/>
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="capacity_date_to" id="capacity_date_to" class="datepicker"  placeholder="To" value="<?php echo $date_to; ?>" />
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="prodfabriccapacityachievementft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdFabricCapacityAchievement.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdFabricCapacityAchievement.resetForm('prodfabriccapacityachievementFrm')" >Reset</a>
            </div>
        </form>
    </div> 
</div>
<div id="prodFabricMonthTargetWindow" class="easyui-window" title="Fabric Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <table id="prodFabricMonthTargetTbl" border="1" style="width:1890px">
        <thead>
            <tr>
                <th data-options="field:'company_name',halign:'center'" width="60">Beneficiary</th>
                <th data-options="field:'team_member_name',halign:'center'" width="80">Dealing  <br/>Marchent</th>
                <th data-options="field:'buyer_name',halign:'center'" width="70">Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="100">Style No</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Sale Order No</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
                <th data-options="field:'qty',halign:'center'" width="80" align="right">Order Qty</th>
                <th data-options="field:'grey_fab',halign:'center'" width="80" align="right">Grey Fabric</th>
                <th data-options="field:'fin_fab',halign:'center'" width="80" align="right">Fin. Fabric</th>
                <th data-options="field:'fabrication',halign:'center'" width="180">Fabrication</th>
                <th data-options="field:'gsm_weight',halign:'center'" width="50">GSM</th>
                <th data-options="field:'dia',halign:'center'" width="60">Dia</th>
                <th data-options="field:'fabriclooks',halign:'center'" width="80">Looks</th>
                <th data-options="field:'fabricshape',halign:'center'" width="80">Shape</th>
                <th data-options="field:'fabric_color',halign:'center'" width="80">Color</th>

            </tr>
        </thead>
    </table>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsProdFabricCapacityAchievementController.js"></script>
<script>
$(".datepicker" ).datepicker({
    beforeShow:function(input) {
        $(input).css({
            "position": "relative",
            "z-index": 999999
        });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
});
</script>

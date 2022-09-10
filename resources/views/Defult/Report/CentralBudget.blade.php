<div class="easyui-layout"  data-options="fit:true" id="centralbudgetContainerLayout">
        <div data-options="region:'center',border:true,title:'Central Budget'" style="padding:2px" id="centralbudgetwindowcontainerlayoutcenter">
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#centralbudgetFrmFt'" style="width:350px; padding:2px">
            <form id="centralbudgetFrm">
                 <div id="container">
                    <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4">Date Range </div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                    <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                    <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To"/>
                                    </div>
                                </div>
                        </code>
                    </div>
                </div>
                <div id="centralbudgetFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsCentralBudget.get()">Show</a>

                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"  plain="true" id="save" onClick="MsCentralBudget.getBudVsAcl()">Bud Vs Acl</a>
                </div>
            </form> 
        </div>
    </div>

<div id="centralbudgetDetailwindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true" style="padding:2px">
            <div id="centralbudgetdetailwindowcontainer" style="width:100%;height:100%;padding:0px;">
            </div>
        </div>
    </div>
</div>
<div id="centralbudgetRemarkswindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:400px;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true" style="padding:2px">
            <div id="centralbudgetremarkswindowcontainer" style="width:100%;height:100%;padding:0px;">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsCentralBudgetController.js"></script>
<script>
$(".datepicker").datepicker({
beforeShow:function(input) {
$(input).css({
"position": "relative",
"z-index": 999999
});
},
dateFormat: 'yy-mm-dd',
changeMonth: true,
changeYear: true,
});
</script>

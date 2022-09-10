<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Budget Summary'" style="padding:2px">
        <div id="budgetsummarymatrix">
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#budgetsummaryFrmFt'" style="width:350px; padding:2px">
        <form id="budgetsummaryFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle">
            <div class="col-sm-4">Ship Date </div>
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
            <div id="budgetsummaryFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBudgetSummary.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBudgetSummary.resetForm('budgetsummaryFrm')" >Reset</a>
            </div>
        </form>
    </div>
</div>
    
    
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/MsBudgetSummaryController.js"></script>
    <script>
        $(".datepicker" ).datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true
        });
    </script>
<div class="easyui-layout animated rollIn"  data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <div id="todaybepdata">
            </div>
        
        </div>
        <div data-options="region:'west',border:true,title:'Search',footer:'#todaybepFrmFt'" style="width:350px; padding:2px">
            <form id="todaybepFrm">
                <div id="container">
                    <div id="body">
                    <code>
                            <div class="row">
                            <div class="col-sm-4">Company</div>
                            <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Ship Date </div>
                                <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="bep_date_from" id="bep_date_from" class="datepicker" placeholder="From" value=""/>
                                </div>
                                <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="bep_date_to" id="bep_date_to" class="datepicker"  placeholder="To" value="" />
                                </div>
                            </div>
                    </code>
                </div>
                </div>
                <div id="todaybepFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTodayBep.get()">Show</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTodayBep.resetForm('todaybepFrm')" >Reset</a>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo url('/');?>/js/report/Dashbord/MsTodayBepController.js"></script>
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

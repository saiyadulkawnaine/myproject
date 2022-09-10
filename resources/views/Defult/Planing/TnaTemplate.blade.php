<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="tnatemplatetabs">
    <div title="TNA Template ID" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnatemplateTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'buyer_name'" width="140">Buyer</th>
                            <th data-options="field:'embelishment'" width="100">Embelishment</th>
                            <th data-options="field:'dyed_yarn'" width="80">Dyed Yarn</th>
                            <th data-options="field:'aop'" width="100">AOP</th>
                            <th data-options="field:'lead_days'" width="100">Lead Time</th>
                            <th data-options="field:'sale_order_no'" width="100">Order Qty. Lower</th>
                            <th data-options="field:'ship_date'" width="80">Order Qty. Upper</th>            
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#Ft2'" style="width:350px; padding:2px">
                <form id="tnatemplateFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Lead Time</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lead_days" id="lead_days" value="" class="number integer"/>
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Imported Material</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('imported_material_id', $yesno,'',array('id'=>'imported_material_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Embellishment</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('embelishment_needed_id', $yesno,'',array('id'=>'embelishment_needed_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Dyed Yarn</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('dyed_yarn_needed_id', $yesno,'',array('id'=>'dyed_yarn_needed_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">AOP</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('aop_needed_id', $yesno,'',array('id'=>'aop_needed_id')) !!}
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="Ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaTemplate.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnatemplateFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaTemplate.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="TNA Template Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Delay Details',iconCls:'icon-more',footer:'#ft4'" style="width:450px; padding:2px">
                <form id="tnatemplatedtlFrm">
                    <div id="container">
                        <div id="body">
                            <code>                          
                                <div class="row">  
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="tna_template_id" id="tna_template_id" value=""/>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Task Name</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('tnatask_id', $tnatask,'',array('id'=>'tnatask_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Lead Days</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lead_days" id="lead_days" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Lag Days</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lag_days" id="lag_days" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Depending Task</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('depending_task_id', $tnatask,'',array('id'=>'depending_task_id','style'=>'width:100%;border-radius:2px;')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Start/End Basis</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('start_end_basis_id', $startendbasis,'',array('id'=>'start_end_basis_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Basis Days</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="start_end_basis_days" id="start_end_basis_days" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Start Reminder</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="start_reminder_days" id="start_reminder_days" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">End Reminder</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="end_reminder_days" id="end_reminder_days" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Sequence</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sort_id" id="sort_id" value="" class="number integer"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaTemplateDtl.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnatemplatedtlFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaTemplateDtl.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnatemplatedtlTbl" style="width:100%"> 
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'task_name'" width="100">Task Name</th> 
                            <th data-options="field:'lead_days'" width="100">Lead Days</th>
                            <th data-options="field:'lag_days'" width="100">Lag Days</th>
                            <th data-options="field:'depending_task'" width="100">Depending Tasks</th>
                            <th data-options="field:'start_reminder_days'" width="100">Start Basis</th>
                            <th data-options="field:'start_end_basis_id'" width="100">Start Reminder</th>
                            <th data-options="field:'end_reminder_days'" width="100">End Reminder</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Planing/MsAllTnaTemplateController.js"></script>
<script>
    (function(){
        $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
        }
        });

        $('#tnatemplateFrm [id="buyer_id"]').combobox();
        $('#tnatemplatedtlFrm [id="tnatask_id"]').combobox();
        $('#tnatemplatedtlFrm [id="depending_task_id"]').combobox();
    })(jQuery);
</script>
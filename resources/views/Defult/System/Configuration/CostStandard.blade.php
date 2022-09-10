<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="coststandardtabs">
    <div title="Start Up" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="coststandardTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'configuration_type_id'" width="100">Configuration Type</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'remarks'" width="100">Remarks</th>  
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add',footer:'#coststandardFrmft'" style="width: 400px; padding:2px">
                <form id="coststandardFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle" style="display:none">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Config Type</div>
                                    <div class="col-sm-8">
                                            {!! Form::select('configuration_type_id', $configuration,'',array('id'=>'configuration_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                                
                                 <div class="row middle">
                                    <div class="col-sm-4">Remarks </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="coststandardFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCostStandard.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCostStandard.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCostStandard.remove()">Delete</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div title="Cost Heads" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add', iconCls:'icon-more', footer:'#coststandardheadFrmFt'" style="width:450px; padding:2px">
                <form id="coststandardheadFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="cost_standard_id" id="cost_standard_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Account Head</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('acc_chart_ctrl_head_id', $ctrlHead,'',array('id'=>'acc_chart_ctrl_head_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cost %</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="cost_per" id="cost_per"  class="number integer"  />
                                    </div>
                                </div>

                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="remarks" id="remarks"/>
                                    </div>
                                </div>
                                
                            </code>
                        </div>
                    </div>
                    <div id="coststandardheadFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCostStandardHead.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('coststandardheadFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCostStandardHead.remove()">Delete</a>
                    </div>
                </form>  
            </div>
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="coststandardheadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="30">ID</th>
                            <th data-options="field:'acc_head'" width="250">Account Head</th>
                            <th data-options="field:'cost_per'" width="60">Cost %</th>
                            <th data-options="field:'remarks'" width="80">Remarks</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/System/Configuration/MsCostStandardController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/System/Configuration/MsCostStandardHeadController.js"></script>
<script>

    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
      });
    $('#coststandardheadFrm [id="acc_chart_ctrl_head_id"]').combobox();

</script>

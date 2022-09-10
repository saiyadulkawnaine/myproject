<div class="easyui-layout animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="ileconfigTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'configuration_type_id'" width="100">Configuration Type</th>
                    <th data-options="field:'company_id'" width="100">Company</th>
                    <th data-options="field:'itemclass_id'" width="100">Item Group</th>
                    <th data-options="field:'source_id'" width="100">Source</th>
                    <th data-options="field:'percent'" width="100">%</th>
                    <th data-options="field:'status_id'" width="100">Status</th>  
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Employee',footer:'#ft2'" style="width: 400px; padding:2px">
        <form id="ileconfigFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                            <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Config Type</div>
                            <div class="col-sm-8">
                                    {!! Form::select('configuration_type_id', $configuration,'95',array('id'=>'configuration_type_id','disabled'=>'disabled')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Company </div>
                            <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 ">Item Group</div>
                            <div class="col-sm-8">
                                <input type="text" name="itemclass_name" id="itemclass_name" ondblclick="MsIleConfig.openItemGoupWindow()" placeholder=" Double Click" />
                                <input type="hidden" name="itemclass_id" id="itemclass_id" />
                            </div>  
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Source </div>
                            <div class="col-sm-8">
                                {!! Form::select('source_id', $purchasesource,'',array('id'=>'source_id')) !!}
                            </div>
                        </div>
                         <div class="row middle">
                            <div class="col-sm-4">% </div>
                            <div class="col-sm-8">
                                <input type="text" name="percent" id="percent" class="number integer" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Status </div>
                            <div class="col-sm-8">
                                {!! Form::select('status_id', $status,'1',array('id'=>'status_id')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsIleConfig.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsIleConfig.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsIleConfig.remove()">Delete</a>
            </div>

        </form>
    </div>
</div>

<div id="openitemgroupwindow" class="easyui-window" title="User Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Search'" style="width:450px;height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="itemgroupsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Category</div>
                                <div class="col-sm-8">
                                    {!! Form::select('itemcategory_id', $itemcategory,'',array('id'=>'itemcategory_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Type </div>
                                <div class="col-sm-8">
                                    {!! Form::select('trims_type_id', $trimstype,'',array('id'=>'trims_type_id')) !!}
                                </div>
                            </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsIleConfig.showItemGroupGrid()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="itemgroupsearchTbl" style="width:610px">
                <thead>
                    <tr>
                        <th data-options="field:'id'" width="80">ID</th>
                        <th data-options="field:'itemclass_name'" width="100">Name</th>
                        <th data-options="field:'itemcategory'" width="100">Category</th>
                        <th data-options="field:'itemnature'" width="100">Item Nature</th>
                        <th data-options="field:'uomclass'" width="100">Uom Class</th>
                        <th data-options="field:'uom'" width="100">Budget Uom</th>
                        <th data-options="field:'is_pre_account'" width="100">Pre Account Req</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#openitemgroupwindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/System/Configuration/MsIleConfigController.js"></script>
<script>

    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
      });

</script>

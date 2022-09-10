<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="renewalitemtabs">
    <div title="Renewal Item" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Renewal Item'" style="padding:2px">
                <table id="renewalitemTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'renewal_item'" width="100">Renewal Item</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'tenure_start'" width="100">Tenure Starts</th>
                            <th data-options="field:'tenure_end'" width="100">Tenure Ends</th>
                            <th data-options="field:'days'" width="100">Notice Before</th>
                            <th data-options="field:'sort_id'" width="100">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Item Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="renewalitemFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Renewal Item</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="renewal_item" id="renewal_item" /> 
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Code</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Tenure Starts</div>
                                    <div class="col-sm-8">                                       
                                        {!! Form::select('tenure_start', $month,'',array('id'=>'tenure_start')) !!}
                                    </div>            
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Tenure Ends</div>
                                    <div class="col-sm-8">                                       
                                        {!! Form::select('tenure_end', $month,'',array('id'=>'tenure_end')) !!}
                                    </div>                       
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">Notice Before</div>
                                    <div class="col-sm-8">
                                        <input  type="text" name="days" id="days" class="number integer" />     
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" class="number integer" />
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRenewalItem.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('renewalitemFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRenewalItem.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Required Docs" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Documents',iconCls:'icon-more',footer:'#utilrenewalitemdocs'" style="width:450px; padding:2px">
                <form id="renewalitemdocFrm">
                    <div id="container">
                        <div id="body">
                            <code> 
                                <div class="row" style="display:none">
                                    <input type="hidden" name="renewal_item_id" id="renewal_item_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>  
                                <div class="row middle">
                                    <div class="col-sm-4 req-text"> Name </div>
                                    <div class="col-sm-8">
                                       <textarea name="name" id="name" ></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" class="number integer" />
                                    </div>
                                </div>                           
                            </code>
                        </div>
                    </div>
                    <div id="utilrenewalitemdocs" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRenewalItemDoc.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('renewalitemdocTbl')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRenewalItemDoc.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'Document Details'" style="padding:2px">
                <table id="renewalitemdocTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="80"> Name</th>
                            <th data-options="field:'sort_id'" width="100">Sequence</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/Renewal/MsAllRenewalItemController.js"></script>

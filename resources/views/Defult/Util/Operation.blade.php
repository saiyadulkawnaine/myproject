<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilOperationtabs">
    <div title="Operation" style="padding:2px" class="easyui-layout"  data-options="fit:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="operationTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'gmtcategory'" width="100">GMT Category</th>
                            <th data-options="field:'deptcategory'" width="100">Dept. Category</th>
                            <th data-options="field:'gmtspart'" width="100">Gmtspart</th>
                            <th data-options="field:'autoyarn_id'" width="120">Fabrication</th>
                            <th data-options="field:'smvbasis'" width="100">SMV Basis</th>
                            <th data-options="field:'resource'" width="100">Resource</th>
                            <th data-options="field:'machinesmv'" width="100">Machine SMV</th>
                            <th data-options="field:'manualsmv'" width="100">Manual SMV</th>
                            <th data-options="field:'productionarea_id'" width="80">GMT Process</th>
                            <th data-options="field:'seam_length'" width="80">Seam Length</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Operation',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
                <form id="operationFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Name</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="name" id="name" value=""/>
                                        <input type="hidden" name="id" id="id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Code </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="code" id="code" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">GMT Category</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('gmt_category_id', $gmtcategory,'',array('id'=>'gmt_category_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Dept. Category</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('dept_category_id', $deptcategory,'',array('id'=>'dept_category_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Gmtspart</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Fabrication</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="fabrication" id="fabrication" onDblClick="MsOperation.openFabricationWindow()" placeholder="Duble click to search"/>
                                        <input type="hidden" name="autoyarn_id" id="autoyarn_id" readonly/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">SMV Basis</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('smv_basis_id', $smvbasis,'',array('id'=>'smv_basis_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Resource</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('resource_id', $resource,'',array('id'=>'resource_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Machine SMV</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="machine_smv" id="machine_smv" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Manual SMV</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="manual_smv" id="manual_smv" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">GMT Process </div>
                                    <div class="col-sm-7">
                                        {!! Form::select('productionarea_id', $productionarea,'',array('id'=>'productionarea_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Seam Length </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="seam_length" id="seam_length" value=""/>
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsOperation.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('operationFrm')" >Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsOperation.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Attachment" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Available',footer:'#utilattachmentoperationft'" style="width:450px; padding:2px">
                <form id="attachmentoperationFrm">
                    <input type="hidden" name="operation_id" id="operation_id" value="" />
                </form>
                <table id="attachmentoperationTbl">
                </table>
                <div id="utilattachmentoperationft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAttachmentOperation.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Permitted'" style="padding:2px">
                <table id="attachmentoperationsavedTbl">
                </table>
            </div>
        </div>
    </div>     
</div>

<div id="styleFabricationWindow" class="easyui-window" title="Fabrications" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#stylefabricsearchTblft'" style="padding:2px">
            <table id="stylefabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
            <form id="stylefabricsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Construction</div>
                            <div class="col-sm-8"> <input type="text" name="construction_name" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsOperation.searchFabric()">Search</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllOperationController.js"></script>
<script>
	(function(){
	    $('.integer').keyup(function () {
	            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
	            this.value = this.value.replace(/[^0-9\.]/g, '');
	            }
	    });
	    $('#operationFrm [id="gmtspart_id"]').combobox();
			$('#operationFrm [id="resource_id"]').combobox();

	})(jQuery);
</script>
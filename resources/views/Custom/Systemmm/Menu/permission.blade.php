<div class="easyui-panel"  style="width:100%;height:100%; border:none">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center', border:false">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                        <table id="tbl_x" class="easyui-datagrid" style="width:100%"
                        data-options="url:'<?php  echo url()."/permission"; ?>',method:'get',border:false,singleSelect:true,fit:true,fitColumns:true,onClickRow:set_form_data">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="80">ID</th>
                                    <th data-options="field:'name'" width="100">Name</th>
                                    <th data-options="field:'description'" width="150">Description</th>
                                   
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div data-options="region:'north',border:true,title:'Add Permission',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:110px; padding:2px">
                            <div id="container">
                                 <div id="body">
                                   <code>
                                        <form id="permissionform">
                                            <div class="row">
                                                <div class="col-sm-2">Name *:</div>
                                                <div class="col-sm-3">
                                                <input type="text" name="name" id="name" value=""/>
                                                <input type="hidden" name="id" id="id" value=""/>
                                                </div>
                                                <div class="col-sm-2">Description:</div>
                                                <div class="col-sm-5"><input type="text" name="description" id="description" value="" /></div>
                                            </div>
                                        </form>
                                  </code>
                               </div>
                                <!-- <p class="footer">
                                <input type="button" value="save" style="width:100px; height:25px" onClick="crud('<?php echo url()."/permission";?>','POST')"/>
                                <input type="button" value="Delete" style="width:100px; height:25px" onClick="crud('<?php echo url()."/permission";?>','DELETE')")"/>
                                </p>-->
                                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;" >
                                <a href="#" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" onClick="crud('<?php echo url()."/permission";?>','POST')">Save</a>
                                <a href="#" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" onClick="crud('<?php echo url()."/permission";?>','DELETE')" >Delete</a>
                                </div>
                            </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
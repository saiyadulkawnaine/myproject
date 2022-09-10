<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilroletabs">
    <div title="Basic" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center', border:false">
                <div class="easyui-layout" data-options="fit:true" id="group_lay">
                    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                        <table id="roleTbl">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="40">ID</th>
                                    <th data-options="field:'name'" width="100">Name</th>
                                    <th data-options="field:'slug'" width="100">Slug</th>
                                    <th data-options="field:'description'" width="160">Desctiption</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div data-options="region:'west',border:true,title:'Add New Role',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
                        <div id="container">
                            <div id="body">
                                <code>
                                    <form id="roleFrm">
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Name</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" id="name" value=""/>
                                            <input type="hidden" name="id" id="id" value=""/>
                                            {{-- <input type="hidden" name="permission_id" id="permission_id" value=""/> --}}
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4 req-text">Slug</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="slug" id="slug" value="" />
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Level</div>
                                        <div class="col-sm-8">
                                            <input type="text" name="level" id="level" value=""/>
                                        </div>
                                    </div>
                                    <div class="row middle">
                                        <div class="col-sm-4">Description</div>
                                        <div class="col-sm-8">
                                            <textarea name="description" id="description" ></textarea>
                                        </div>
                                    </div>     
                                </form>
                              </code>
                          </div>
                        </div>
                        </div>
                        <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;" >
                            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRole.submit()">Save</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('roleFrm')" >Reset</a>
            
                            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRole.remove()" >Delete</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div title="Permission Role" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Permission',footer:'#utilpermissionroleft'" style="width:450px; padding:2px">
                <form id="permissionroleFrm">
                    <input type="hidden" name="role_id" id="role_id" value=""/>
                </form>
                <table id="permissionroleTbl">
                </table>
                <div id="utilpermissionroleft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPermissionRole.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
                <table id="permissionrolesavedTbl">
                </table>
            </div>
        </div>
    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/user-accounts/MsAllRoleController.js"></script>
  
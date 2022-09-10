<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilusertabs">
    <div title="Basic" style="padding:2px">
            <div class="easyui-layout" data-options="fit:true">
    			<div data-options="region:'center', border:false">
                    <div class="easyui-layout" data-options="fit:true" id="group_lay">
                        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                            <table id="userTbl">
                                <thead>
                                    <tr>
                                        <th data-options="field:'id'" width="80">ID</th>
                                        <th data-options="field:'name'" width="100">Name</th>
                                        <th data-options="field:'email'" width="100">Email</th>
                                        <th data-options="field:'role_name'" width="100">Role</th>

                                    </tr>
                                </thead>
                            </table>

                        </div>
                        <div data-options="region:'west',border:true,title:'Add New Menu',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
                                <div id="container">
                                     <div id="body">
                                       <code>
                                               <form id="userFrm">
                                                <div class="row">
                                                    <div class="col-sm-4 req-text">Name</div>
                                                    <div class="col-sm-8">
                                                    <input type="text" name="name" id="name" value=""/>
                                                    <input type="hidden" name="id" id="id" value=""/>
                                                    </div>
                                                 </div>
                                                 <div class="row middle">
                                                    <div class="col-sm-4 req-text">Email</div>
                                                    <div class="col-sm-8"><input type="text" name="email" id="email" value="" /></div>
                                                </div>
                                                 <div class="row middle">
                                                    <div class="col-sm-4">Password</div>
                                                    <div class="col-sm-8">
                                                    <input type="password" name="password" id="password" value=""/>
                                                    </div>
                                                 </div>
                                                 <div class="row middle">
                                                    <div class="col-sm-4">Confirm Pass.</div>
                                                    <div class="col-sm-8"><input type="password" name="password_confirmation" id="password_confirmation" value="" /></div>
                                                </div>
                                                <div class="row middle">
                                                    <div class="col-sm-4">Role</div>
                                                    <div class="col-sm-8">
                                                    {!! Form::select('role_id', $role,'',array('id'=>'role_id')) !!}
                                                    </div>
                                                 </div>
                                                 <div class="row middle">
                                                    <div class="col-sm-4">Description</div>
                                                    <div class="col-sm-8"><input type="text" name="description" id="description" value="" /></div>
                                                </div>
                                            </form>
                                      </code>
                                   </div>
                                </div>
                                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;" >
                             <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsUser.submit()">Save</a>
                              <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('userFrm')" >Reset</a>
                            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsUser.remove()" >Delete</a>
                            </div>
                        </div>
                    </div>
    			</div>
    		</div>
    </div>
    <div title="Company" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Company',footer:'#utilcompanyuserft'" style="width:450px; padding:2px">
            <form id="companyuserFrm">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            </form>
            <table id="companyuserTbl">
            </table>
            <div id="utilcompanyuserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompanyUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Company'" style="padding:2px">
            <table id="companyusersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Buyer" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Buyer',footer:'#utilbuyeruserft'" style="width:450px; padding:2px">
            <form id="buyeruserFrm">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            </form>
            <table id="buyeruserTbl">
            </table>
            <div id="utilbuyeruserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Buyer'" style="padding:2px">
            <table id="buyerusersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Supplier" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Supplier',footer:'#utilsupplieruserft'" style="width:450px; padding:2px">
            <form id="supplieruserFrm">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            </form>
            <table id="supplieruserTbl">
            </table>
            <div id="utilsupplieruserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSupplierUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Supplier'" style="padding:2px">
            <table id="supplierusersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Approval Setup" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Permission',footer:'#utilpermissionuserft'" style="width:450px; padding:2px">
            <form id="permissionuserFrm">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            </form>
            <table id="permissionuserTbl">
            </table>
            <div id="utilpermissionuserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPermissionUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Permission'" style="padding:2px">
            <table id="permissionusersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Item Category" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Item Category',footer:'#utilitemcategoryuserft'" style="width:450px; padding:2px">
            <form id="itemcategoryuserFrm">
            <input type="hidden" name="user_id" id="user_id" value=""/>
            </form>
            <table id="itemcategoryuserTbl">
            </table>
            <div id="utilitemcategoryuserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsItemcategoryUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Item Category'" style="padding:2px">
            <table id="itemcategoryusersavedTbl">
            </table>
            </div>
        </div>
    </div>
    <div title="Signature" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add File',iconCls:'icon-more',footer:'#assetimageft'" style="width:450px; padding:2px">
                <form id="signatureuserFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Signature Upload</div>
                                    <div class="col-sm-8">
                                        <input type="file" id="signature_file" name="signature_file" />
                                    </div>
                                </div>                               
                            </code>
                        </div>
                    </div>
                    <div id="assetimageft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSignatureUser.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('signatureuserFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSignatureUser.remove()">Delete</a>
                    </div>
                </form>   
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="signatureuserTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="100">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'signature_file',halign:'center',align:'center'" width="100" formatter="MsSignatureUser.formatFile">Download</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/user-accounts/MsAllUserController.js"></script>



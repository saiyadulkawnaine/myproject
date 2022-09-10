<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilstoretabs">
    <div title="Store" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Stores'" style="padding:2px">
                <table id="storeTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'location_id'" width="100">Location</th>
                            <th data-options="field:'name'" width="100">Store Name</th>
                            
                            <th data-options="field:'user_id'" width="100">Store Incharge</th>
                            <th data-options="field:'company_id'" width="100">Company</th>
                            <th data-options="field:'address'" width="100">Address</th>
                        </tr>
                    </thead>
                </table>

            </div>
            <div data-options="region:'west',border:true,title:'Add New Store',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <div id="container">
                    <div id="body">
                        <code>
                            <form id="storeFrm">
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Store Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" />
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Delivery Address</div>
                                    <div class="col-sm-8">
                                        <textarea name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Store Incharge</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('user_id', $user,'',array('id'=>'user_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                    </div>
                                </div>
                            </form>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStore.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('storeFrm')">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStore.remove()">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <div title="Item Category" style="padding:2px">
            <div class="easyui-layout" data-options="fit:true">
                <div data-options="region:'west',border:true,title:'Item Category',footer:'#utilItemCatft'" style="width:450px; padding:2px">
    
                    <form id="storeitemcategoryFrm">
                        <input type="hidden" name="store_id" id="store_id" value="" />
                    </form>
                    <table id="storeitemcategoryTbl">
    
                    </table>
                    <div id="utilItemCatft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
                            iconCls="icon-save" plain="true" id="save" onClick="MsStoreItemcategory.submit()">Save</a>
                    </div>
                </div>
                <div data-options="region:'center',border:true,title:'Taged Category'" style="padding:2px">
                    <table id="storeitemcategorysavedTbl">
    
                    </table>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllStoreController.js"></script>

<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilcolortabs">
    <div title="Color" style="padding:2px" class="easyui-layout"  data-options="fit:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="colorTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">Code</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Size',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px; padding:2px">
                <form id="colorFrm">
                    <div id="container">
                        <div id="body">
                        <code>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Name </div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name" value=""/>
                                    <input type="hidden" name="id" id="id" value=""/>
                                    <input type="hidden" name="menu_id" id="menu_id" value=""/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Model </div>
                                <div class="col-sm-8"><input type="text" name="model" id="model" value=""/></div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Code</div>
                                <div class="col-sm-8">
                                    <input type="text" name="code" id="code" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Sequence </div>
                                <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                            </div>
                        </code>
                    </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsColor.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('colorFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsColor.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Buyer" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Buyer',footer:'#utilbuyercolorft'" style="width:450px; padding:2px">
                <form id="buyercolorFrm">
                    <input type="hidden" name="color_id" id="color_id" value="" />
                </form>
                <table id="buyercolorTbl">
                </table>
                <div id="utilbuyercolorft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerColor.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Saved'" style="padding:2px">
                <table id="buyercolorsavedTbl">
                </table>
            </div>
        </div>
    </div>     
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllColorController.js"></script>
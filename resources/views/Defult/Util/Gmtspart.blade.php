<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilgmtparttabs">
    <div title="Gmts Part" style="padding:2px" class="easyui-layout"  data-options="fit:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="gmtspartTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Gmtspart',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px; padding:2px">
                <form id="gmtspartFrm">
                    <div id="container">
                        <div id="body">
                        <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Name </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" value=""/>
                                        <input type="hidden" name="id" id="id" value=""/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Part Type  </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('part_type_id', $parttype,'',array('id'=>'part_type_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">GMT Catgory  </div>
                                    <div class="col-sm-8">
                                        {!! Form::select('gmt_category_id', $gmtcategory,'',array('id'=>'gmt_category_id')) !!}
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
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGmtspart.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('gmtspartFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtspart.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Menu" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New Menu',footer:'#utilgmtspartmenuft'" style="width:450px; padding:2px">
                <form id="gmtspartmenuFrm">
                    <input type="hidden" name="gmtspart_id" id="gmtspart_id" value="" />
                </form>
                <table id="gmtspartmenuTbl">
                </table>
                <div id="utilgmtspartmenuft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGmtspartMenu.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Saved'" style="padding:2px">
                <table id="gmtspartmenusavedTbl">
                </table>
            </div>
        </div>
    </div>     
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllGmtspartController.js"></script>
  
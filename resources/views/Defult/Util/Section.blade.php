<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilsectiontabs">
    <div title="Basic" style="padding:2px" class="easyui-layout"  data-options="fit:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="sectionTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'name'" width="100">Name</th>
                            <th data-options="field:'code'" width="100">Code</th>
                            <th data-options="field:'chief_name'" width="100">Chief Name</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Subsection',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:450px; padding:2px">
                <form id="sectionFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Name </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" id="name" />
                                        <input type="hidden" name="id" id="id" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Code  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="code" id="code" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Chief Name</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="chief_name" id="chief_name" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Address </div>
                                    <div class="col-sm-8">
                                        <textarea name="address" id="address"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Sequence  </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="sort_id" id="sort_id" class="number integer" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSection.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('sectionFrm')" >Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSection.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Floor" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Add Floor',footer:'#utilfloorsectionft'" style="width:450px; padding:2px">
                <form id="floorsectionFrm">
                    <input type="hidden" name="section_id" id="section_id" value="" />
                </form>
                <table id="floorsectionTbl">

                </table>
                <div id="utilfloorsectionft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsFloorSection.submit()">Save</a>
                </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged Floors'" style="padding:2px">
                <table id="floorsectionsavedTbl">
                </table>
            </div>
        </div>
    </div>       
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllSectionController.js"></script>
<script>
(function(){
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

    })(jQuery);
</script>
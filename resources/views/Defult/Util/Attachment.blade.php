<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="attachmentTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Name</th>
                    <th data-options="field:'code'" width="100">Code</th>
                    <th data-options="field:'resource'" width="100">Resource</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Attachment',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:350px; padding:2px">
        <form id="attachmentFrm">
            <div id="container">
                <div id="body">
                <code>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Name</div>
                            <div class="col-sm-8">
                                <input type="text" name="name" id="name" value=""/>
                                <input type="hidden" name="id" id="id" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Code </div>
                            <div class="col-sm-8">
                                <input type="text" name="code" id="code" value=""/>
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4 req-text">Resource</div>
                            <div class="col-sm-8">
                                {!! Form::select('resource_id', $resource,'',array('id'=>'resource_id')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Sequence</div>
                            <div class="col-sm-8">
                                <input type="text" name="sort_id" id="sort_id" value=""/>
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsAttachment.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('attachmentFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsAttachment.remove()" >Delete</a>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAttachmentController.js"></script>

<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilcompositiontabs">
  <div title="Composition" style="padding:2px" class="easyui-layout"  data-options="fit:true">
      <div class="easyui-layout"  data-options="fit:true">
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="compositionTbl" style="width:100%">
                  <thead>
                      <tr>
                          <th data-options="field:'id'" width="80">ID</th>
                          <th data-options="field:'name'" width="100">Name</th>
                      </tr>
                  </thead>
              </table>
          </div>
          <div data-options="region:'west',border:true,title:'Add New Composition',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="width:400px; padding:2px">
              <form id="compositionFrm">
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
                      </code>
                  </div>
                  </div>
                  <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsComposition.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('compositionFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsComposition.remove()" >Delete</a>
                  </div>
              </form>
          </div>
      </div>
  </div>
  <div title="Itemcategory" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
          <div data-options="region:'west',border:true,title:'New Itemcategory',footer:'#utilcompositionitemcategoryft'" style="width:450px; padding:2px">
              <form id="compositionitemcategoryFrm">
                  <input type="hidden" name="composition_id" id="composition_id" value="" />
              </form>
              <table id="compositionitemcategoryTbl">
              </table>
              <div id="utilcompositionitemcategoryft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                  <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCompositionItemcategory.submit()">Save</a>
              </div>
          </div>
          <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <table id="compositionitemcategorysavedTbl">
              </table>
          </div>
      </div>
  </div>     
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllCompositionController.js"></script>
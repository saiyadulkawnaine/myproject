<div class="easyui-layout animated rollIn" data-options="fit:true"
 style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
 <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
  <table id="yarncountTbl" style="width:100%">
   <thead>
    <tr>
     <th data-options="field:'id'" width="80">ID</th>
     <th data-options="field:'count'" width="100">Count</th>
     <th data-options="field:'symbol'" width="100">Symbol</th>
    </tr>
   </thead>
  </table>
 </div>
 <div
  data-options="region:'north',border:true,title:'Add New Yarncount',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'"
  style="height:160px; padding:2px">
  <form id="yarncountFrm">
   <div id="container">
    <div id="body">
     <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Count</div>
                    <div class="col-sm-3">
                    <input type="text" name="count" id="count" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Symbol </div>
                    <div class="col-sm-3"><input type="text" name="symbol" id="symbol" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">Squence  </div>
                    <div class="col-sm-3"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                </div>

          </code>
    </div>
   </div>
   <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
     iconCls="icon-save" plain="true" id="save" onClick="MsYarncount.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('yarncountFrm')">Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
     iconCls="icon-remove" plain="true" id="delete" onClick="MsYarncount.remove()">Delete</a>
   </div>

  </form>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsYarncountController.js"></script>
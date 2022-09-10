
  <div class="easyui-tabs" style="width:100%;height:100%; border:none" id="libembelishmenttabs">
    <div title="Embelishment Name" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'north',split:true, title:'Embelishment Name',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'" style="height:150px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="embelishmentFrm">
            <div class="row">
                    <div class="col-sm-2 req-text">Name  </div>
                    <div class="col-sm-4">
                    {!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Code </div>
                    <div class="col-sm-4"><input type="text" name="code" id="code" value=""/></div>
                    
                </div>
                <div class="row middle">
                
                    <div class="col-sm-2">Sequence  </div>
                    <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                    
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmbelishment.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('embelishmentFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmbelishment.remove()" >Delete</a>
                </div>
          </form>
          </code>
          </div>
          </div>
      </div>
      <div data-options="region:'center',split:true, title:'List'">
        <table id="embelishmentTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'name'" width="100">Name</th>
                <th data-options="field:'code'" width="100">Code</th>
           </tr>
        </thead>
        </table>
      </div>
      </div>
    </div>
    <div title="Embelishment Type" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Embelishment Type',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft3'" style="height:150px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="embelishmenttypeFrm">
            <div class="row">
                    <div class="col-sm-2 req-text">Name:</div>
                    <div class="col-sm-4">
                    <input type="text" name="name" id="name" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    <input type="hidden" name="embelishment_id" id="embelishment_id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Code: </div>
                    <div class="col-sm-4"><input type="text" name="code" id="code" value=""/></div>
                </div>
                <div class="row middle">
                    
                    <div class="col-sm-2">Sequence</div>
                    <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                </div>
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmbelishmentType.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('embelishmenttypeFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmbelishmentType.remove()" >Delete</a>
            </div>
          </form>
          </code>
          </div>
          </div>
        </div>
        <div data-options="region:'center',split:true, title:'List'">
          <table id="embelishmenttypeTbl" style="width:100%">
          <thead>
          <tr>
          <th data-options="field:'id'" width="80">ID</th>
          <th data-options="field:'name'" width="100">Name</th>
          <th data-options="field:'code'" width="100">Code</th>
          <th data-options="field:'embelishment'" width="100">Embelishment</th>
          </tr>
          </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllEmbelishmentController.js"></script>
  <script>
  <script>
  $(".datepicker" ).datepicker({
  dateFormat: 'yy-mm-dd',
  changeMonth: true,
  changeYear: true
  });
  </script>

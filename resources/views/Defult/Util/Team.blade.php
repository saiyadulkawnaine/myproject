<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<div class="easyui-accordion" data-options="multiple:false,fit:true" style="width:300px;">
<div title="Team List" data-options="iconCls:'icon-ok'" style="overflow:auto;padding:2px;">
<table id="teamTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
   </tr>
</thead>
</table>
</div>
<div title="Member List" data-options="iconCls:'icon-ok'" style="overflow:auto;padding:2px;">
<table id="teammemberTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'type'" width="100">Type</th>
        <th data-options="field:'team'" width="100">Team</th>

   </tr>
</thead>
</table>
</div>
</div>
</div>
<div data-options="region:'west',border:true,title:'Add New Team'" style="width:310px; padding:2px">
<div class="easyui-accordion" data-options="multiple:true" style="width:300px;">
<div title="Team" data-options="iconCls:'icon-ok',footer:'#ft2'" style="overflow:auto;padding:2px;">
<form id="teamFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Name</div>
                    <div class="col-sm-8">
                    <input type="text" name="name" id="name" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>

                </div>
                <div class="row middle">
                    <div class="col-sm-4">Code  </div>
                    <div class="col-sm-8"><input type="text" name="code" id="code" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Team Type </div>
                    <div class="col-sm-8">{!! Form::select('team_type_id', $teamtype,'',array('id'=>'team_type_id')) !!}</div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTeam.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('teamFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTeam.remove()" >Delete</a>
    </div>
  </form>
  </div>
  <div title="Member" data-options="iconCls:'icon-ok',footer:'#ft3'" style="overflow:auto;padding:2px;">
  <form id="teammemberFrm">
    <div id="container">
         <div id="body">
           <code>
                 <div class="row middle">
                    <div class="col-sm-4 req-text">Member: </div>
                    <div class="col-sm-8">
                    {!! Form::select('user_id', $user,'',array('id'=>'user_id')) !!}
                    <input type="hidden" name="team_id" id="team_id" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Type: </div>
                    <div class="col-sm-8">{!! Form::select('type_id', $membertype,'',array('id'=>'type_id')) !!}</div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTeammember.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('teammemberFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTeammember.remove()" >Delete</a>
    </div>

  </form>
  </div>
  </div>

</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsTeamController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsTeammemberController.js"></script>

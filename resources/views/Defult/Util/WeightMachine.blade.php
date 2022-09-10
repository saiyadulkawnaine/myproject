<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="utilweightmachinetabs">
<div title="Basic" style="padding:2px">
<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="weightmachineTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'machine_no'" width="100">Machine No</th>
        <th data-options="field:'work_station_name'" width="100">Work Station</th>
        <th data-options="field:'machine_ip'" width="100">Machine IP</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Machine Entry',footer:'#weightmachineFrmft'" style="width: 450px; padding:2px">
<form id="weightmachineFrm">
    <div id="container">
         <div id="body">
           <code>
             <div class="row">
                    <div class="col-sm-4 req-text">Machine No </div>
                    <div class="col-sm-8">
                      <input type="text" name="machine_no" id="machine_no"/>
                      <input type="hidden" name="id" id="id" />
                    </div>
                </div>

                
               
                <div class="row middle">
                    <div class="col-sm-4 req-text">Work Station  </div>
                    <div class="col-sm-8"><input type="text" name="work_station_name" id="work_station_name"/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Machine IP  </div>
                    <div class="col-sm-8"><input type="text" name="machine_ip" id="machine_ip"/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="weightmachineFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsWeightMachine.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('weightmachineFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsWeightMachine.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
</div>
<div title="User" style="padding:2px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'west',border:true,title:'New User',footer:'#utilweightmachineuserft'" style="width:450px; padding:2px">
            <form id="weightmachineuserFrm">
            <input type="hidden" name="weight_machine_id" id="weight_machine_id" value=""/>
            </form>
            <table id="weightmachineuserTbl">
            </table>
            <div id="utilweightmachineuserft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsWeightMachineUser.submit()">Save</a>
            </div>
            </div>
            <div data-options="region:'center',border:true,title:'Taged User'" style="padding:2px">
            <table id="weightmachineusersavedTbl">
            </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllWeightMachineController.js"></script>
<script>
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>

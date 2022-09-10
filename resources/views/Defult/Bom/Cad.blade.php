<div class="easyui-layout "  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List',footer:'#ftcadcon'" style="padding:2px">
    <form id="cadconFrm">

      <code id="cadconmatrix">
      </code>
        <div id="ftcadcon" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
               <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCadCon.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('cadconFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCadCon.remove()" >Delete</a>
            </div>

        </form>
    </div>
    <div data-options="region:'west',border:true" style="width:350px; padding:2px">
      <div class="easyui-layout "  data-options="fit:true">
        <div data-options="region:'north',border:true,title:'Add New Cad',footer:'#ft2'" style="height:150px; padding:2px">
        <form id="cadFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row">
                            <div class="col-sm-4 req-text">Style </div>
                            <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" placeholder="Double Click"  onDblClick="MsCad.openStyleWindow()" readonly/>
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                                <input type="hidden" name="id" id="id" value=""/>
                            </div>
                          </div>
                          <div class="row middle">
                            <div class="col-sm-4">Cad Date</div>
                            <div class="col-sm-8"><input type="text" name="cad_date" id="cad_date" value="" class="datepicker"/></div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Buyer </div>
                            <div class="col-sm-8">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}
                            </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsCad.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('cadFrm')" >Reset</a>
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsCad.remove()" >Delete</a>
            </div>

        </form>
      </div>
      <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
          <table id="cadTbl" style="width:100%">
              <thead>
              <tr>
                  <th data-options="field:'created_by'" width="80">UserName</th>
                  <th data-options="field:'style'" width="80">Style</th>
                  <th data-options="field:'buyer'" width="120">Buyer</th>
                  <th data-options="field:'cad_date'" width="80">Cad Date</th>
                  <th data-options="field:'id'" width="40">ID</th>
              </tr>
              </thead>
          </table>
      </div>
      </div>
    </div>
</div>
<div id="cadw" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
<div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
         <div class="easyui-layout" data-options="fit:true">
                 <div id="body">
                     <code>
                         <form id="cadstylesearch">
                             <div class="row">
                                 <div class="col-sm-2">Buyer</div>
                                 <div class="col-sm-4">
                                {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                 </div>
                                  <div class="col-sm-2">Style Ref.  </div>
                                 <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                             </div>
                             <div class="row middle">
                                  <div class="col-sm-2">Style Des.  </div>
                                  <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                             </div>
                         </form>
                     </code>
                 </div>
                     <p class="footer">
                      <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsCad.getStyle()">Search</a>
                     </p>
             </div>
         </div>
   <div data-options="region:'center'" style="padding:10px;">
     <table id="cadstyleTbl" style="width:610px">
       <thead>
           <tr>
               <th data-options="field:'id'" width="50">ID</th>
               <th data-options="field:'buyer'" width="70">Buyer</th>
               <th data-options="field:'receivedate'" width="80">Receive Date</th>
               <th data-options="field:'style_ref'" width="100">Style Refference</th>
               <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
               <th data-options="field:'productdepartment'" width="80">Product Department</th>
               <th data-options="field:'season'" width="80">Season</th>
          </tr>
       </thead>
     </table>
   </div>
   <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
     <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#cadw').window('close')" style="width:80px">Close</a>
   </div>
 </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCadController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsCadConController.js"></script>
<script>
(function(){
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });
    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });
    $('#cadstylesearch [id="buyer_id"]').combobox();
})(jQuery);
</script>

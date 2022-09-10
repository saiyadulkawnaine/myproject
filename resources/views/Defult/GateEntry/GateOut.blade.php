<div class="easyui-layout"  data-options="fit:true">
  <div data-options="region:'center',border:true" style="padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'center',border:true,footer:'#gtoutTblFt',title:'List'" style="height:500px; padding:2px">
        <table id="gateoutTbl">
          <thead>
            <tr>
              <th data-options="field:'id'" width="40">Id</th>
              <th data-options="field:'menu_name'" width="160">Menu Name</th> 
              <th data-options="field:'gin_no'" width="80">GIN No</th>
              <th data-options="field:'to_company_name'" width="180">To Company</th>
              <th data-options="field:'barcode_no_id'" width="80">Barcode No</th>
              <th data-options="field:'out_date'" width="80">Out Date</th> 
              <th data-options="field:'created_by_name'" width="100">Entered By</th> 
              <th data-options="field:'entry_time'" width="80">Time</th> 
            </tr>
          </thead>
        </table>
        <div id="gtoutTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
          <form id="gatesearchFrm">
          Menu: {!! Form::select('barcode_menu_id',
          $menu,'',array('id'=>'barcode_menu_id','style'=>'width:100px;height: 23px')) !!}    
            Date: <input type="text" name="from_date" id="from_date" class="datepicker" style="width: 100px ;height: 23px" />
          <input type="text" name="to_date" id="to_date" class="datepicker" style="width: 100px;height: 23px" />
          <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsGateOut.searchList()">Show</a>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div data-options="region:'west',border:true,title:'Gate In Out', footer:'#gateoutFt'" style="width:300px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
      <div data-options="region:'north',border:true,title:'Out Detail'" style="padding:2px;height:110px">
        <div class="easyui-layout"  data-options="fit:true">
          <form id="gateoutFrm" onsubmit="return false;">
            <div id="container">
              <div id="body">
                <code>
                    <div class="row middle" style="display:none">
                      <input type="hidden" name="id" id="id" value="" />
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text">Menu</div>
                      <div class="col-sm-8">
                        {!! Form::select('menu_id',
                          $menu,'',array('id'=>'menu_id','onchange'=>'MsGateOut.purchaseOnchage(this.value)')) !!}
                      </div>
                    </div>
                    <div class="row middle">
                      <div class="col-sm-4 req-text"><strong>Barcode</strong></div>
                      <div class="col-sm-8">
                        <input type="text" name="barcode_no_id" id="barcode_no_id" value=""  onchange="MsGateOut.getPurchaseNo()" />
                      </div>
                    </div>
                </code>
              </div>
            </div>
            <div id="gateoutFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGateOut.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGateOut.submit()">Save</a>
            </div>
          </form>
        </div>
      </div>
      <div data-options="region:'center',border:true,title:'Item List'" style="padding:2px">
        <table id="gateoutitemTbl">
          <thead>
            <tr>
              <th data-options="field:'item_id'" width="40">ID</th>
              <th data-options="field:'item_description'" width="130">Product Des.</th>
              <th data-options="field:'qty'" width="136">Qty</th>
              <th data-options="field:'returnable_qty'" width="136">Return Qty</th>
              <th data-options="field:'uom_code'" width="60">UOM</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript" src="<?php echo url('/');?>/js/GateEntry/MsGateOutController.js"></script>

<script>
$(".datepicker" ).datepicker({
dateFormat: 'yy-mm-dd',
changeMonth: true,
changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
    this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});

$("#barcode_no_id").focus();

</script>
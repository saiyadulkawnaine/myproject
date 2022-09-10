<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'" style="padding:3px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',title:'Fabrication Roll Receive By Qc',split:true,
            footer:'#ft3'" style="padding:3px;width:100%">
            {{-- <form>
                <code>
                    <input type="hidden" name="id" id="id" />
                    <input type="hidden" name="prod_knit_item_roll_id" id="prod_knit_item_roll_id" />
                </code>
            </form> --}}
                <table id="prodknitrcvbyqcGetTbl" style="width:100%">
                    <thead>
                        <tr>
                       {{--  <th data-options="field:'id'" width="40">ID</th> --}}
                        <th data-options="field:'prod_knit_id'" width="50">Prod <br/>Mst.ID</th>
                        <th data-options="field:'prod_knit_item_id'" width="50">Prod <br/>Dtail.ID</th>
                        <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                        <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
                        <th data-options="field:'fabric_look_id'" width="100">Fabric <br/>Look</th>
                        <th data-options="field:'gsm_weight'" width="60">GSM</th>
                        <th data-options="field:'fabric_shape_id'" width="70">Fabric <br/>Shape</th>
                        <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                        <th data-options="field:'yarn_detail'" width="70">Yarn <br/>Detail</th>
                        <th data-options="field:'colorrange_name'" width="70">Color Range</th>
                        <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
                        <th data-options="field:'uom_code'" width="60">UOM</th>
                        <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
                        <th data-options="field:'custom_no'" width="100">M/C No</th>
                        <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                        <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                        <th data-options="field:'shift_id'" width="80">Shift Name</th>
                        <th data-options="field:'supplier_name'" width="100">Company Name</th>
                        <th data-options="field:'location_name'" width="80">Location</th>
                        <th data-options="field:'subcon_order_source'" width="100">Order Source</th>
                        <th data-options="field:'subcon_order_no'" width="100">Order No</th>
                        <th data-options="field:'subcon_buyer_code'" width="100">Buyer</th>
                        </tr>
                    </thead>
                </table>
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitRcvByQc.getSelectedRoll()">Close</a>
                </div>
            </div>
            <div data-options="region:'south', title:' List'" style="height:200px;padding:3px">
                <table id="prodknitrcvbyqcTbl" style="width:100%">
                    <thead>
                        <tr>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'prod_knit_id'" width="50">Prod <br/>Mst.ID</th>
                        <th data-options="field:'prod_knit_item_id'" width="50">Prod <br/>Dtail.ID</th>
                        <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                        <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
                        <th data-options="field:'fabric_look_id'" width="100">Fabric <br/>Look</th>
                        <th data-options="field:'gsm_weight'" width="60">GSM</th>
                        <th data-options="field:'fabric_shape_id'" width="70">Fabric <br/>Shape</th>
                        <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                        <th data-options="field:'yarn_detail'" width="70">Yarn <br/>Detail</th>
                        <th data-options="field:'colorrange_name'" width="70">Color Range</th>
                        <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
                        <th data-options="field:'uom_code'" width="60">UOM</th>
                        <th data-options="field:'floor_id'" width="100">Prod. <br/>Floor</th>
                        <th data-options="field:'custom_no'" width="100">M/C No</th>
                        <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
                        <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
                        <th data-options="field:'shift_id'" width="80">Shift Name</th>
                        <th data-options="field:'supplier_id'" width="100">Company Name</th>
                        <th data-options="field:'location_id'" width="80">Location</th>
                        <th data-options="field:'subcon_order_source'" width="100">Order Source</th>
                        <th data-options="field:'subcon_order_no'" width="100">Order No</th>
                        <th data-options="field:'subcon_buyer_code'" width="100">Buyer</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{--Selected Roll Save Window --}}
<div id="prodknititemrollrcvWindow" class="easyui-window" title="Production Knitting Roll Receive By Qc" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#itemrollfooter'" style="padding:2px">
            <form id="prodknitrcvbyqcFrm">
                <code id="prodknitrcvqcscs" style="margin:0px">
                </code>
            </form>
        </div>
        <div id="itemrollfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitRcvByQc.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitRcvByQc.resetForm()" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitRcvByQc.remove()" >Delete</a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitRcvByQc.submitAndClose()">Close</a>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitRcvByQcController.js"></script>


<script>
    (function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true,
      });
      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) 
         {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });

   })(jQuery);
</script>
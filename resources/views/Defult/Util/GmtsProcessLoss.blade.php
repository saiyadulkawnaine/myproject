
  <div class="easyui-tabs" style="width:100%;height:100%; border:none"  id="UtilGmtProcessLosstabs">
    <div title="GMT Process Loss" style="padding:1px" data-options="selected:true">
      <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'GMTS Process Loss',footer:'#ft2'" style="width:350px;padding:3px">
          <div id="container">
            <div id="body">
              <code>
                <form id="gmtsprocesslossFrm">
                  <div class="row">
                    <div class="col-sm-5 req-text">Company </div>
                    <div class="col-sm-7">
                    {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                    <input type="hidden" name="id" id="id" />
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-5 req-text">Buyer  </div>
                    <div class="col-sm-7">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}</div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-5 req-text">Range Start  </div>
                    <div class="col-sm-7"><input type="text" name="gmt_qty_range_start" id="gmt_qty_range_start" class="number integer" /></div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-5 req-text">Range End  </div>
                    <div class="col-sm-7"><input type="text" name="gmt_qty_range_end" id="gmt_qty_range_end" class="number integer"/></div>
                  </div>
                  <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGmtsProcessLoss.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('gmtsprocesslossFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtsProcessLoss.remove()" >Delete</a>
                  </div>
                </form>
              </code>
            </div>
          </div>
        </div>
        <div data-options="region:'center',border:true, title:'List'">
          <table id="gmtsprocesslossTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'company'" width="100">Company</th>
                    <th data-options="field:'buyer'" width="100">Buyer</th>
                    <th data-options="field:'gmt_qty_range_start',align:'right'" width="100">GMT Qty Range Start</th>
                    <th data-options="field:'gmt_qty_range_end',align:'right'" width="100">GMT Qty Range End</th>
              </tr>
            </thead>
            </table>
        </div>
      </div>
    </div>
    <div title="Percent" style="padding:2px">
      <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',split:true, title:'Add New GMTS ProcessLoss %',footer:'#ft3'" style="width: 350px;padding:3px">
          <div id="container">
          <div id="body">
          <code>
          <form id="gmtsprocesslossperFrm">
            <div class="row">
                    <div class="col-sm-5 req-text">Prod. Process </div>
                    <div class="col-sm-7">
                      {!! Form::select('production_process_id', $productionprocess,'',array('id'=>'production_process_id')) !!}
                      <input type="hidden" name="id" id="id" />
                      <input type="hidden" name="gmts_process_loss_id" id="gmts_process_loss_id" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5">Emb. Type </div>
                    <div class="col-sm-7">{!! Form::select('embelishment_type_id', $embelishmenttype,'',array('id'=>'embelishment_type_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-5 req-text">Process Loss % </div>
                    <div class="col-sm-7"><input type="text" name="process_loss_per" id="process_loss_per" class="number integer" /></div>
                </div>
                <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsGmtsProcessLossPer.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('gmtsprocesslossperFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsGmtsProcessLossPer.remove()" >Delete</a>
                </div>
          </form>
          </code>
          </div>
          </div>
        </div>
        <div data-options="region:'center',split:true, title:'List'">
          <table id="gmtsprocesslossperTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'productionprocess'" width="100">Production Process</th>
                    <th data-options="field:'embelishmenttype'" width="100">Embellishment Type</th>
                    <th data-options="field:'process_loss_per',align:'right'" width="100">Process Loss %</th>
               </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllGmtsProcessLossController.js"></script>

  <script>
  $('.integer').keyup(function () {
      if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
         this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });
  </script>

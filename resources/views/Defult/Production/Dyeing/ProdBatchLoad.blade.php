<div class="easyui-tabs animated rollIn" style="width:100%;height:100%; border:none" id="prodbatchloadtabs">
    <div title="Batch" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#prodbatchloadTblFt'" style="padding:2px">
                <table id="prodbatchloadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="40">ID</th>
                            <th data-options="field:'company_code'" width="80">Company</th>
                            <th data-options="field:'batch_no'" width="80">Batch No</th>
                            <th data-options="field:'batch_date'" width="100">Batch Date</th>
                            <th data-options="field:'batch_for'" width="100">Batch For</th>
                            <th data-options="field:'machine_no'" width="80">Machine No</th>
                            <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                            <th data-options="field:'color_name'" width="80">Roll Color</th>
                            <th data-options="field:'color_range_name'" width="80">Color Range</th>
                            <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                            <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>

                            <th data-options="field:'load_date'" width="80">Load Date</th>
                            <th data-options="field:'load_time'" width="80">Load Time</th>
                            <th data-options="field:'load_posting_date'" width="80">Prod.Date</th>
                            <th data-options="field:'tgt_hour'" width="80">Tgt Hour</th>
                            <th data-options="field:'load_remarks'" width="80">Remarks</th>

                            <th data-options="field:'root_batch_no'" width="80">Org. Batch No</th>
                            <th data-options="field:'root_batch_id'" width="80">Org. Batch ID</th>
                            
                        </tr>
                    </thead>
                    <div id="prodbatchloadTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    Batch Date: <input type="text" name="from_batch_date" id="from_batch_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_batch_date" id="to_batch_date" class="datepicker" style="width: 100px;height: 23px" />
                     Posting Date: <input type="text" name="from_load_posting_date" id="from_load_posting_date" class="datepicker" style="width: 100px ;height: 23px" />
                    <input type="text" name="to_load_posting_date" id="to_load_posting_date" class="datepicker" style="width: 100px;height: 23px" />
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-search" plain="true" id="save" onClick="MsProdBatchLoad.searchList()">Show</a>
                </div>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add Details',footer:'#prodbatchloadFrmft'" style="width: 400px; padding:2px">
                <form id="prodbatchloadFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                
                                <div class="row">
                                    <div class="col-sm-4">Batch ID</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="id" id="id" ondblclick="MsProdBatchLoad.batchWindow()" placeholder=" Double Click" readonly />
                                    </div>
                                </div>
                                 <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_no" id="batch_no"  disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_date" id="batch_date" class="datepicker" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch For</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Machine No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="machine_no" id="machine_no" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Brand</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="brand" id="brand" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Capacity</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="prod_capacity" id="prod_capacity" disabled/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Batch Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('batch_color_id', $color,'',array('id'=>'batch_color_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Roll Color</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('fabric_color_id', $color,'',array('id'=>'fabric_color_id','disabled'=>'disabled','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Color Range</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('colorrange_id', $colorrange,'',array('id'=>'colorrange_id','disabled'=>'disabled')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">lab Dip No</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="lap_dip_no" id="lap_dip_no" disabled />
                                    </div>
                                </div> 
                                <div class="row middle">
                                    <div class="col-sm-4">Fabric Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="fabric_wgt" id="fabric_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Batch Wgt.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch_wgt" id="batch_wgt" disabled />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Load Date Time</div>
                                    <div class="col-sm-4" style="padding-right:0px">
                                        <input type="text" name="load_date" id="load_date" value="{{ date('Y-m-d') }}" placeholder="date" class="datepicker"/>
                                    </div>
                                    <div class="col-sm-4" style="padding-left:0px">
                                        <input type="text" name="load_time" id="load_time" value="" placeholder="time"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Tgt. Pro. Hour.</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tgt_hour" id="tgt_hour" class="number integer">
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Prod.Date</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="load_posting_date" id="load_posting_date" value="{{ date('Y-m-d') }}" readonly>
                                    </div>
                                </div>
                                  <div class="row middle">
                                    <div class="col-sm-4 req-text">Shift</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('load_shift', $shiftname,'',array('id'=>'load_shift')) !!}
                                        <input type="hidden" name="id" id="id"  readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="load_remarks" id="remarks" />
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="prodbatchloadFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdBatchLoad.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchLoad.resetForm()">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdBatchLoad.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Roll" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="prodbatchloadrollTbl" style="width:100%">
                    <thead>
                        <tr>
                          <th data-options="field:'id'" width="70">ID</th>
                          <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
                          <th data-options="field:'custom_no'" width="70">Custom No</th>
                          <th data-options="field:'sale_order_no'" width="100">Order No</th>
                          <th data-options="field:'style_ref'" width="100">Style Ref</th>
                          <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
                          <!-- <th data-options="field:'root_roll_qty',halign:'center'" width="80" align="right">Org. <br/>Batch Qty</th> -->
                          <th data-options="field:'batch_qty',halign:'center'" width="80" align="right">Batch Qty</th>
                          <th data-options="field:'colorrange_name'" width="80">Color Range</th>
                          <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                          <th data-options="field:'dyeing_color'" width="80">Roll Color</th>
                          <th data-options="field:'body_part'" width="100">Body Part</th>
                          <th data-options="field:'fabrication'" width="200">Fabric <br/>Description</th>
                          <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
                          <th data-options="field:'fabric_look'" width="70">Fabric <br/>Look</th>
                          <th data-options="field:'gsm_weight'" width="40">GSM</th>
                          <th data-options="field:'dia_width'" width="40">Dia/<br/>Widht</th>
                          <th data-options="field:'measurement'" width="70">Measurement</th>
                          <th data-options="field:'roll_length'" width="70">Roll Length</th>
                          <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
                          <th data-options="field:'shrink_per'" width="60">Shrink %</th>
                          
                          <th data-options="field:'dyetype'" width="80">Dyeing Type</th>
                          
                          <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
                          
                          <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
                          <th data-options="field:'supplier_name'" width="100">Produced By</th>
                          <th data-options="field:'customer_name'" width="100">Produced For</th>
                          <th data-options="field:'yarndtl'" width="300">Yarn</th>

                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="prodbatchloadbatchWindow" class="easyui-window" title="Machine Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
       <div data-options="region:'center',border:true,footer:'#prodbatchloadbatchsearchTblFt'" style="padding:2px">
            <table id="prodbatchloadbatchsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'company_code'" width="80">Company</th>
                    <th data-options="field:'batch_no'" width="80">Batch No</th>
                    <th data-options="field:'batch_date'" width="100">Batch Date</th>
                    <th data-options="field:'batchfor'" width="100">Batch For</th>
                    <th data-options="field:'machine_no'" width="80">Machine No</th>
                    <th data-options="field:'batch_color_name'" width="80">Batch Color</th>
                    <th data-options="field:'color_name'" width="80">Roll Color</th>
                    <th data-options="field:'color_range_name'" width="80">Color Range</th>
                    <th data-options="field:'fabric_wgt'" width="80">Fabric Wgt</th>
                    <th data-options="field:'batch_wgt'" width="80">Batch Wgt</th>
                    </tr>
                </thead>
            </table>
            <div id="prodbatchloadbatchsearchTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#prodbatchloadbatchWindow').window('close')" style="width:80px">Close</a>
                </div>
        </div>
        <div data-options="region:'west',border:true,footer:'#prodbatchloadbatchsearchFrmFt'" style="padding:2px; width:350px">
            <form id="prodbatchloadbatchsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Company</div>
                            <div class="col-sm-8">
                            {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch No</div>
                            <div class="col-sm-8">
                            <input type="text" name="batch_no" id="batch_no" />
                            </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4">Batch For</div>
                            <div class="col-sm-8">
                            {!! Form::select('batch_for', $batchfor,'',array('id'=>'batch_for')) !!}
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="prodbatchloadbatchsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true"  onClick="MsProdBatchLoad.getBatch()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>









<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Dyeing/MsProdBatchLoadController.js"></script>

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
         changeYear: true
      });

      $('#load_time').timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });


   })(jQuery);

</script>
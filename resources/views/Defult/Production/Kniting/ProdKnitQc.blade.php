<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Knit Qc',footer:'#prodknitqcFrmFt'" style="width: 450px; padding:2px">
        <form id="prodknitqcFrm">
            <div id="container">
            <div id="body">
            <code>
            <div class="row middle" style="display:none">
            <input type="hidden" name="id" id="id" value="" />
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Roll No</div>
            <div class="col-sm-8">
            <input type="text" name="roll_no" id="roll_no" readonly ondblclick="MsProdKnitQc.rollWindow()" />
            <input type="hidden" name="prod_knit_item_roll_id" id="prod_knit_item_roll_id" readonly />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4">Other Roll</div>
            <div class="col-sm-8">
            <input type="text" name="other_roll_no" id="other_roll_no" readonly ondblclick="MsProdKnitQc.rollWindowSimilar()" />
            <input type="hidden" name="other_prod_knit_item_roll_id" id="other_prod_knit_item_roll_id" readonly />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Qc Date</div>
            <div class="col-sm-8">
            <input type="text" name="qc_date" id="qc_date" value="" class="datepicker"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Prod. Ref</div>
            <div class="col-sm-8">
            <input type="text" name="prod_no" id="prod_no" value="" disabled/>
            </div>
            </div>
            
            <div class="row middle">
            <div class="col-sm-4 req-text">Body Part</div>
            <div class="col-sm-8">
            <input type="text" name="body_part" id="body_part" value="" disabled/>
            </div>
            </div>


            <div class="row middle">
            <div class="col-sm-4 req-text">Fabric Description</div>
            <div class="col-sm-8">
            <input type="text" name="fabrication" id="fabrication" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Fabric Shape</div>
            <div class="col-sm-8">
            <input type="text" name="fabric_shape" id="fabric_shape" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Fabric Look</div>
            <div class="col-sm-8">
            <input type="text" name="fabric_look" id="fabric_look" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">GSM</div>
            <div class="col-sm-8">
            <input type="text" name="gsm_weight" id="gsm_weight" class="number integer" />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Dia / Width</div>
            <div class="col-sm-8">
            <input type="text" name="dia_width" id="dia_width" class="number integer" />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Measurement</div>
            <div class="col-sm-8">
            <input type="text" name="measurement" id="measurement"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Roll Length</div>
            <div class="col-sm-8">
            <input type="text" name="roll_length" id="roll_length" class="number integer"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Shrink %</div>
            <div class="col-sm-8">
            <input type="text" name="shrink_per" id="shrink_per" class="number integer"/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Color Range</div>
            <div class="col-sm-8">
            <input type="text" name="colorrange_name" id="colorrange_name" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Fabric Color</div>
            <div class="col-sm-8">
            <input type="text" name="fabric_color_name" id="fabric_color_name" disabled />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Stitch Length</div>
            <div class="col-sm-8">
            <input type="text" name="stitch_length" id="stitch_length" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Roll/ Fab Wgt</div>
            <div class="col-sm-8">
            <input type="text" name="roll_weight" id="roll_weight" class="number integer" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">PCS</div>
            <div class="col-sm-8">
            <input type="text" name="qty_pcs" id="qty_pcs" class="number integer" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Reject/KG</div>
            <div class="col-sm-8">
            <input type="text" name="reject_qty" id="reject_qty" class="number integer" onchange="MsProdKnitQc.calculate_kg()" />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">QC Pass/KG</div>
            <div class="col-sm-8">
            <input type="text" name="qc_pass_qty" id="qc_pass_qty" class="number integer" readonly />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Reject/PCS</div>
            <div class="col-sm-8">
            <input type="text" name="reject_qty_pcs" id="reject_qty_pcs" class="number integer" onchange="MsProdKnitQc.calculate_pcs()" />
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">QC Pass/PCS</div>
            <div class="col-sm-8">
            <input type="text" name="qc_pass_qty_pcs" id="qc_pass_qty_pcs" class="number integer" readonly />
            </div>
            </div>

           

            <div class="row middle">
            <div class="col-sm-4 req-text">QC Result</div>
            <div class="col-sm-8">
                {!! Form::select('qc_result', $rollqcresult,'',array('id'=>'qc_result')) !!}
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Prod Floor</div>
            <div class="col-sm-8">
            <input type="text" name="floor_name" id="floor_name" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">M/C No</div>
            <div class="col-sm-8">
            <input type="text" name="machine_no" id="machine_no" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">M/C Dia</div>
            <div class="col-sm-8">
            <input type="text" name="machine_dia" id="machine_dia" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">M/C Gauge</div>
            <div class="col-sm-8">
            <input type="text" name="machine_gg" id="machine_gg" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Shift Name</div>
            <div class="col-sm-8">
            <input type="text" name="shift_name" id="shift_name" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Order No</div>
            <div class="col-sm-8">
            <input type="text" name="sale_order_no" id="sale_order_no" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Style Ref</div>
            <div class="col-sm-8">
            <input type="text" name="style_ref" id="style_ref" disabled/>
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Buyer</div>
            <div class="col-sm-8">
            <input type="text" name="buyer_name" id="buyer_name" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">GMT Sample</div>
            <div class="col-sm-8">
            <input type="text" name="gmt_sample" id="gmt_sample" disabled/>
            </div>
            </div>

            <div class="row middle">
            <div class="col-sm-4 req-text">Produced By</div>
            <div class="col-sm-8">
            <input type="text" name="supplier_name" id="supplier_name" disabled />
            </div>
            </div>
            <div class="row middle">
            <div class="col-sm-4 req-text">Produced For</div>
            <div class="col-sm-8">
            <input type="text" name="customer_name" id="customer_name" disabled />
            </div>
            </div>

            </code>
            </div>
            </div>
            <div id="prodknitqcFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitQc.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('prodknitqcFrm')">Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove"   plain="true" id="delete"  zdy90 onClick="MsProdKnitQc.remove()">Delete</a>
            </div>
        </form>
        
    </div>
    <div data-options="region:'center',title:'List',footer:'#prodknitqcTblFt'" style="padding:2px">
        <table id="prodknitqcTbl" style="width:100%">
        <thead>
        <tr>
        <th data-options="field:'id'" width="70">ID</th>
        <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
        <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
        <th data-options="field:'body_part'" width="70">Body Part</th>

        <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
        <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
        <th data-options="field:'fabric_look'" width="100">Fabric <br/>Look</th>
        <th data-options="field:'gsm_weight'" width="60">GSM</th>
        <th data-options="field:'dia_width'" width="60">Dia/Widht</th>
        <th data-options="field:'measurement'" width="60">Measurement</th>
        <th data-options="field:'roll_length'" width="60">Roll Length</th>
        <th data-options="field:'colorrange_name'" width="70">Color Range</th>
        <th data-options="field:'fabric_color_name'" width="70">Fabric Color</th>
        <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
        <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
        <th data-options="field:'reject_qty'" width="100"> Reject/<br/> Qty</th>
        <th data-options="field:'qty_pcs'" width="100">PCS</th>
        <th data-options="field:'reject_qty_pcs'" width="100">Reject PCS</th>
        <th data-options="field:'qc_result'" width="100">QC Result</th>
        <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
        <th data-options="field:'machine_no'" width="100">M/C No</th>
        <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
        <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
        <th data-options="field:'shift_name'" width="80">Shift Name</th>
        <th data-options="field:'sale_order_no'" width="100">Order No</th>
        <th data-options="field:'style_ref'" width="100">Style Ref</th>
        <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
        <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
        <th data-options="field:'supplier_name'" width="100">Produced By</th>
        <th data-options="field:'customer_name'" width="100">Produced For</th>

        </tr>
        </thead>
        </table>
            <div id="prodknitqcTblFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            QC Date: <input type="text" name="from_qc_date" id="from_qc_date" class="datepicker" style="width: 100px ;height: 23px" />
            <input type="text" name="to_qc_date" id="to_qc_date" class="datepicker" style="width: 100px;height: 23px" />
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove"   plain="true" id="delete"  zdy90 onClick="MsProdKnitQc.searchRoll()">Show</a>
            </div>
        
    </div>
</div>

{{--Selected Roll Save Window --}}
<div id="prodknitqcWindow" class="easyui-window" title="Production Knitting Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'west',border:true,title:'Knit Qc',footer:'#prodknitqcsearchFrmFt'" style="width: 400px; padding:2px">
        <form id="prodknitqcsearchFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4">Buyer</div>
        <div class="col-sm-8">
        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Produced By</div>
        <div class="col-sm-8">
        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Prod. Date </div>
        <div class="col-sm-4" style="padding-right:0px">
        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
        </div>
        <div class="col-sm-4" style="padding-left:0px">
        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
        </div>
        </div>

        </code>
        </div>
        </div>
        <div id="prodknitqcsearchFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitQc.getRoll()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitQc.resetSearchForm('prodknitqcsearchFrm')">Reset</a>
        </div>
        </form>

        </div>
        <div data-options="region:'center',border:true,footer:'#itemrollfooter'" style="padding:2px">
            <table id="prodknitqcrollTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
            <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
            <th data-options="field:'body_part'" width="70">Body Part</th>

            <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
            <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
            <th data-options="field:'fabric_look'" width="100">Fabric <br/>Look</th>
            <th data-options="field:'gsm_weight'" width="60">GSM</th>
            <th data-options="field:'dia_width'" width="60">Dia/Widht</th>
            <th data-options="field:'measurement'" width="60">Measurement</th>
            <th data-options="field:'roll_length'" width="60">Roll Length</th>
            <th data-options="field:'colorrange_name'" width="70">Color Range</th>
            <th data-options="field:'fabric_color_name'" width="70">Fabric Color</th>
            <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
            <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
            <th data-options="field:'qty_pcs'" width="100">PCS</th>
            <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
            <th data-options="field:'machine_no'" width="100">M/C No</th>
            <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
            <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
            <th data-options="field:'shift_name'" width="80">Shift Name</th>
            <th data-options="field:'sale_order_no'" width="100">Order No</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
            <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
            <th data-options="field:'supplier_name'" width="100">Produced By</th>
            <th data-options="field:'customer_name'" width="100">Produced For</th>
            
            </tr>
            </thead>
            </table>
            <div id="itemrollfooter" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitQc.submitAndClose()">Close</a>
            </div>
        </div>
    </div>
</div>

<div id="prodknitqcsimilarWindow" class="easyui-window" title="Production Knitting Roll" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'west',border:true,title:'Knit Qc',footer:'#prodknitqcsearchsimilarFrmFt'" style="width: 400px; padding:2px">
        <form id="prodknitqcsearchsimilarFrm">
        <div id="container">
        <div id="body">
        <code>
        <div class="row middle">
        <div class="col-sm-4">Buyer</div>
        <div class="col-sm-8">
        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Produced By</div>
        <div class="col-sm-8">
        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id','style'=>'width: 100%; border-radius:2px')) !!}
        </div>
        </div>
        <div class="row middle">
        <div class="col-sm-4">Prod. Date </div>
        <div class="col-sm-4" style="padding-right:0px">
        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
        </div>
        <div class="col-sm-4" style="padding-left:0px">
        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
        </div>
        </div>

        </code>
        </div>
        </div>
        <div id="prodknitqcsearchsimilarFrmFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitQc.getRollSimilar()">Show</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProdKnitQc.resetSearchForm('prodknitqcsearchsimilarFrm')">Reset</a>
        </div>
        </form>

        </div>
        <div data-options="region:'south',border:true,footer:'#prodknitqcsimilarWindowFt'" style="padding:2px; height: 250px">
            <table id="prodknitqcrollsimilarselectedTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
            <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
            <th data-options="field:'body_part'" width="70">Body Part</th>

            <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
            <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
            <th data-options="field:'fabric_look'" width="100">Fabric <br/>Look</th>
            <th data-options="field:'gsm_weight'" width="60">GSM</th>
            <th data-options="field:'dia_width'" width="60">Dia/Widht</th>
            <th data-options="field:'measurement'" width="60">Measurement</th>
            <th data-options="field:'roll_length'" width="60">Roll Length</th>
            <th data-options="field:'colorrange_name'" width="70">Color Range</th>
            <th data-options="field:'fabric_color_name'" width="70">Fabric Color</th>
            <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
            <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
            <th data-options="field:'qty_pcs'" width="100">PCS</th>
            <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
            <th data-options="field:'machine_no'" width="100">M/C No</th>
            <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
            <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
            <th data-options="field:'shift_name'" width="80">Shift Name</th>
            <th data-options="field:'sale_order_no'" width="100">Order No</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
            <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
            <th data-options="field:'supplier_name'" width="100">Produced By</th>
            <th data-options="field:'customer_name'" width="100">Produced For</th>
            
            </tr>
            </thead>
            </table>
            <div id="prodknitqcsimilarWindowFt" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdKnitQc.getSelections()">Close</a>
            </div>
        </div>
        <div data-options="region:'center',border:true" style="padding:2px">
            <table id="prodknitqcrollsimilarTbl" style="width:100%">
            <thead>
            <tr>
            <th data-options="field:'prod_no'" width="70">Prod. Ref</th>
            <th data-options="field:'prod_knit_item_roll_id'" width="70">Roll No</th>
            <th data-options="field:'body_part'" width="70">Body Part</th>

            <th data-options="field:'fabrication'" width="60">Fabric <br/>Description</th>
            <th data-options="field:'fabric_shape'" width="70">Fabric <br/>Shape</th>
            <th data-options="field:'fabric_look'" width="100">Fabric <br/>Look</th>
            <th data-options="field:'gsm_weight'" width="60">GSM</th>
            <th data-options="field:'dia_width'" width="60">Dia/Widht</th>
            <th data-options="field:'measurement'" width="60">Measurement</th>
            <th data-options="field:'roll_length'" width="60">Roll Length</th>
            <th data-options="field:'colorrange_name'" width="70">Color Range</th>
            <th data-options="field:'fabric_color_name'" width="70">Fabric Color</th>
            <th data-options="field:'stitch_length'" width="70">Stitch<br/>Length</th>
            <th data-options="field:'roll_weight'" width="100"> Roll/<br/> Fab Wgt</th>
            <th data-options="field:'qty_pcs'" width="100">PCS</th>
            <th data-options="field:'floor_name'" width="100">Prod. <br/>Floor</th>
            <th data-options="field:'machine_no'" width="100">M/C No</th>
            <th data-options="field:'machine_dia'" width="60">M/C Dia</th>
            <th data-options="field:'machine_gg'" width="60">M/C Gauge</th>
            <th data-options="field:'shift_name'" width="80">Shift Name</th>
            <th data-options="field:'sale_order_no'" width="100">Order No</th>
            <th data-options="field:'style_ref'" width="100">Style Ref</th>
            <th data-options="field:'buyer_name'" width="100">Buyer Name</th>
            <th data-options="field:'gmt_sample'" width="100">Gmt Sample</th>
            <th data-options="field:'supplier_name'" width="100">Produced By</th>
            <th data-options="field:'customer_name'" width="100">Produced For</th>
            
            </tr>
            </thead>
            </table>
            
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Production/Kniting/MsProdKnitQcController.js"></script>


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
      $('#prodknitqcsearchFrm [id="buyer_id"]').combobox();
      $('#prodknitqcsearchFrm [id="supplier_id"]').combobox();
      $('#prodknitqcsearchsimilarFrm [id="buyer_id"]').combobox();
      $('#prodknitqcsearchsimilarFrm [id="supplier_id"]').combobox();

   })(jQuery);
</script>
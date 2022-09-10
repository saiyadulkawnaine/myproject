<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="projectionqtyTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'projection'" width="100">Projection</th>
        <th data-options="field:'style'" width="100">Style</th>
        <th data-options="field:'stylegmts'" width="100">Style Gmts</th>
        <th data-options="field:'qty'" width="100">Quantity</th>
        <th data-options="field:'rate'" width="100">Rate</th>
        <th data-options="field:'amount'" width="100">Amount</th>
        <th data-options="field:'currency'" width="100">Currency</th>
        <th data-options="field:'projno'" width="100">Proj No</th>
        <th data-options="field:'country'" width="100">Country</th>
        <th data-options="field:'cutoff'" width="100">Cut Off</th>
        <th data-options="field:'shipdate'" width="100">Ship Date</th>
        <th data-options="field:'fileno'" width="100">File No</th>
        <th data-options="field:'exchrate'" width="100">Exchange Rate</th>
        <th data-options="field:'uom'" width="100">Uom</th>
        <th data-options="field:'season'" width="100">Season</th>
        <th data-options="field:'sam'" width="100">Sam</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New ProjectionQty',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:360px; padding:2px">
<form id="projectionqtyFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Projection</div>
                    <div class="col-sm-4">
                    {!! Form::select('projection_id', $projection,'',array('id'=>'projection_id')) !!}
                    <input type="hidden" name="id" id="id" />
                    </div>
                    <div class="col-sm-2 req-text">Style </div>
                    <div class="col-sm-4">{!! Form::select('style_id', $style,'',array('id'=>'style_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Style Gmts</div>
                    <div class="col-sm-4">{!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}</div>
                    <div class="col-sm-2 req-text">Quantity</div>
                    <div class="col-sm-4"><input type="text" name="qty" id="qty" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Rate</div>
                    <div class="col-sm-4"><input type="text" name="rate" id="rate" /></div>
                    <div class="col-sm-2 req-text">Amount</div>
                    <div class="col-sm-4"><input type="text" name="amount" id="amount" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Currency</div>
                    <div class="col-sm-4">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                    <div class="col-sm-2">Proj No </div>
                    <div class="col-sm-4"><input type="text" name="proj_no" id="proj_no" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Country</div>
                    <div class="col-sm-4">{!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}</div>
                    <div class="col-sm-2">Cut Off </div>
                    <div class="col-sm-4"><input type="text" name="cut_off" id="cut_off" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Ship Date</div>
                    <div class="col-sm-4"><input type="text" name="ship_date" id="ship_date" /></div>
                    <div class="col-sm-2 req-text">File No</div>
                    <div class="col-sm-4"><input type="text" name="file_no" id="file_no" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Exchange Rate</div>
                    <div class="col-sm-4"><input type="text" name="exch_rate" id="exch_rate" /></div>
                    <div class="col-sm-2 req-text">Uom</div>
                    <div class="col-sm-4">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Season</div>
                    <div class="col-sm-4">{!! Form::select('season_id', $season,'',array('id'=>'season_id')) !!}</div>
                    <div class="col-sm-2">Sam </div>
                    <div class="col-sm-4"><input type="text" name="sam" id="sam" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">Remarks </div>
                    <div class="col-sm-4"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProjectionQty.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('projectionqtyFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProjectionQty.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProjectionQtyController.js"></script>

<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="stylegmtsTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'style'" width="100">Style</th>
        <th data-options="field:'itemaccount'" width="100">Item Account</th>
        <th data-options="field:'gmtqty'" width="100">GMT Qty</th>
        <th data-options="field:'itemcomplexity'" width="100">Item Complexity</th>
        <th data-options="field:'gmtcategory'" width="100">GMT Category</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New StyleGmts',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:160px; padding:2px">
<form id="stylegmtsFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Style</div>
                    <div class="col-sm-4">
                    {!! Form::select('style_id', $style,'',array('id'=>'style_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Item Account </div>
                    <div class="col-sm-4">{!! Form::select('item_account_id', $itemaccount,'',array('id'=>'item_account_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2">Article </div>
                    <div class="col-sm-4"><input type="text" name="article" id="article" value=""/></div>
                    <div class="col-sm-2 req-text">GMT Qty </div>
                    <div class="col-sm-4"><input type="text" name="gmt_qty" id="gmt_qty" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Item Complexity</div>
                    <div class="col-sm-4"><input type="text" name="item_complexity" id="item_complexity" value=""/></div>
                    <div class="col-sm-2">Custom Category  </div>
                    <div class="col-sm-4"><input type="text" name="custom_catg" id="custom_catg" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">GMT Category</div>
                    <div class="col-sm-4"><input type="text" name="gmt_catg" id="gmt_catg" value=""/></div>
                    <div class="col-sm-2">SMV  </div>
                    <div class="col-sm-4"><input type="text" name="smv" id="smv" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleGmts.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylegmtsFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleGmts.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsStyleGmtsController.js"></script>

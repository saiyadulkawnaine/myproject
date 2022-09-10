<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="trimcosttempleteTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'buyer'" width="100">Buyer</th>
        <th data-options="field:'trimgroup'" width="100">Trim Group</th>
        <th data-options="field:'supplier'" width="100">Supplier</th>
        <th data-options="field:'uom'" width="100">Uom</th>
        <th data-options="field:'sort'" width="100">Sort</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'north',border:true,title:'Add New Trimcosttemplete',iconCls:'icon-more',hideCollapsedContent:false,collapsed:true,footer:'#ft2'" style="height:180px; padding:2px">
<form id="trimcosttempleteFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-2 req-text">Buyer</div>
                    <div class="col-sm-4">
                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                        <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2 req-text">Trim Group </div>

                    <div class="col-sm-4"><input type="text" name="trim_group_id" id="trim_group_id" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Supplier </div>
                    <div class="col-sm-4">
                        {!! Form::select('supplier_id', $supplier,'',array('id'=>'supplier_id')) !!}
                        <input type="hidden" name="id" id="" value=""/>
                    </div>
                    <div class="col-sm-2">Cons :</div>
                    <div class="col-sm-4"><input type="text" name="cons" id="cons" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Uom </div>
                    <div class="col-sm-4">
                        {!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}
                        <input type="hidden" name="id" id="id" value=""/>
                    </div>
                    <div class="col-sm-2">Rate :</div>
                    <div class="col-sm-4"><input type="text" name="rate" id="rate" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-2 req-text">Sequence </div>
                    <div class="col-sm-4"><input type="text" name="sort_id" id="sort_id" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTrimcosttemplete.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('trimcosttempleteFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTrimcosttemplete.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsTrimcosttempleteController.js"></script>

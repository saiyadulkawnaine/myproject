<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="buyerbranchshipdayTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'buyer'" width="100">Buyer</th>
        <th data-options="field:'buyerbranch'" width="100">Buyer Name</th>
        <th data-options="field:'dayname'" width="100">Day Name</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New Buyer Branch Shipday',footer:'#ft2'" style="width: 350px; padding:2px">
<form id="buyerbranchshipdayFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Buyer</div>
                    <div class="col-sm-8">
                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                    <input type="hidden" name="id" id="id" value=""/>
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Buyer Branch </div>
                    <div class="col-sm-8">{!! Form::select('buyer_branch_id', $buyerbranch,'',array('id'=>'buyer_branch_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text"> Day Name </div>
                    <div class="col-sm-8"><input type="text" name="day_name" id="day_name" value=""/></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerBranchShipday.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerbranchshipdayFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerBranchShipday.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBuyerBranchShipdayController.js"></script>

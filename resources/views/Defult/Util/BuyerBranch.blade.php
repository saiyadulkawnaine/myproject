<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="buyerbranchTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'code'" width="100">Code</th>
        <th data-options="field:'buyer'" width="100">Buyer</th>
        <th data-options="field:'country'" width="100">Country</th>
   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New Buyer Branch',footer:'#ft2'" style="width:350px; padding:2px">
<form id="buyerbranchFrm">
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
                    <div class="col-sm-4 req-text">Country </div>
                    <div class="col-sm-8">{!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Name </div>
                    <div class="col-sm-8"><input type="text" name="name" id="name" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Code </div>
                    <div class="col-sm-8"><input type="text" name="code" id="code" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Contact Person  </div>
                    <div class="col-sm-8"><input type="text" name="contact_person" id="contact_person" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Email :</div>
                    <div class="col-sm-8"><input type="text" name="email" id="email" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Designation </div>
                    <div class="col-sm-8"><input type="text" name="designation" id="designation" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Address :</div>
                    <div class="col-sm-8"><input type="text" name="address" id="address" value="" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Shipment Day </div>
                    <div class="col-sm-8"><input type="text" name="shipment_day" id="shipment_day" value=""/></div>
                </div>
          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsBuyerBranch.submit()">Save</a>
         <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('buyerbranchFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsBuyerBranch.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsBuyerBranchController.js"></script>

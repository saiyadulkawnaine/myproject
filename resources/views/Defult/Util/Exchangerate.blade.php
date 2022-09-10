<div class="easyui-layout animated rollIn"  data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="exchangerateTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'name'" width="100">Name</th>
        <th data-options="field:'rate_buy'" align="right" width="100">Rate Buy</th>
        <th data-options="field:'rate_sell'"align="right" width="100">Rate Sell</th>
        <th data-options="field:'applied_date'" width="100">Applied Date</th>
   </tr>
</thead>
</table>

</div>
<div data-options="region:'west',border:true,title:'Add New Exchange Rate',footer:'#ft2'" style="width: 350px; padding:2px">
<form id="exchangerateFrm">
    <div id="container">
         <div id="body">
           <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Currency </div>
                    <div class="col-sm-8">
                    {!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
                    <input type="hidden" name="id" id="id" />
                    </div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4 req-text">Rate Buy </div>
                    <div class="col-sm-8"><input type="text" name="rate_buy" id="rate_buy" class="number integer" /></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Rate Sell  </div>
                    <div class="col-sm-8"><input type="text" name="rate_sell" id="rate_sell" class="number integer" /></div>
                  </div>
                  <div class="row middle">
                    <div class="col-sm-4">Applied Date</div>
                    <div class="col-sm-8"><input type="text" name="applied_date" id="applied_date" class="datepicker"  /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class=" easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsExchangerate.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('exchangerateFrm')" >Reset</a>

        <a href="javascript:void(0)" class="easyui-linkbutton  c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsExchangerate.remove()" >Delete</a>
    </div>

</form>
</div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsExchangerateController.js"></script>
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
</script>

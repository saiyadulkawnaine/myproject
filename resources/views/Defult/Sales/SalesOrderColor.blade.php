<div class="easyui-layout"  data-options="fit:true" >
<div data-options="region:'center',border:true,title:'Size Entry',footer:'#ft6'" style="padding:2px">
<form id="salesordersizeFrm">
<input type="hidden" name="job_id" id="job_id" value=""/>
<input type="hidden" name="sale_order_id" id="sale_order_id" value=""/>
<input type="hidden" name="sale_order_country_id" id="sale_order_country_id" value=""/>
<input type="hidden" name="sale_order_color_id" id="sale_order_color_id" value=""/>
<input type="hidden" name="id" id="id" value=""/>
<code id="sizetable">
</code>
</form>
<div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderSize.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordersizeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderSize.remove()" >Delete</a>
    </div>
</div>
<div data-options="region:'west',border:true" style="width:450px; padding:2px">
<div class="easyui-layout"  data-options="fit:true" >
<div data-options="region:'north',border:true,title:'Color Entry',iconCls:'icon-more',footer:'#ft5'" style="height:250px; padding:2px">
<form id="salesordercolorFrm">
    <div id="container">
         <div id="body">
           <code>
                <div class="row">
                   <div class="col-sm-4 req-text">Job No </div>
                   <div class="col-sm-8"><input type="text" name="job_no" id="job_no" disabled/></div>
                   <input type="hidden" name="job_id" id="job_id" value=""/>
                   <input type="hidden" name="sale_order_id" id="sale_order_id" value=""/>
                   <input type="hidden" name="sale_order_country_id" id="sale_order_country_id" value=""/>
                   <input type="hidden" name="id" id="id" value=""/>
                </div>
                <div class="row middle">
                   <div class="col-sm-4 req-text">Style Ref </div>
                   <div class="col-sm-8"><input type="text" name="style_ref" id="style_ref" disabled/></div>
                </div>
                <div class="row middle">
                   <div class="col-sm-4 req-text">Sales Order No </div>
                   <div class="col-sm-8"><input type="text" name="sale_order_no" id="sale_order_no" disabled/></div>
                </div>
                <div class="row middle">
                   <div class="col-sm-4 req-text">Country</div>
                   <div class="col-sm-8"><input type="text" name="country_name" id="country_name" disabled/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Style Color</div>
                    <div class="col-sm-8">
                    {!! Form::select('style_color_id', $stylecolor,'',array('id'=>'style_color_id','onchange'=>'MsSalesOrderColor.setColorDetails(this.value)')) !!}
                    </div>
                  </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Color Code</div>
                    <div class="col-sm-8"><input type="text" name="color_code" id="color_code" value="" readonly/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Sequence</div>
                    <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number" readonly /></div>
                </div>

          </code>
       </div>
    </div>
    <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderColor.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordercolorFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderColor.remove()" >Delete</a>
    </div>

  </form>
</div>
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="salesordercolorTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'name'" width="80">Color</th>
        <th data-options="field:'qty'" width="60" align="right">Qty</th>
        <th data-options="field:'rate'" width="60" align="right">Rate</th>
        <th data-options="field:'amount'" width="80" align="right">Amount</th>
        <th data-options="field:'sort'" width="50" align="right">Sequence</th>
   </tr>
</thead>
</table>
</div>
</div>
</div>
</div>

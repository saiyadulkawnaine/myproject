<div class="easyui-layout"  data-options="fit:true" >
<div data-options="region:'center',border:true,title:'Size Entry',footer:'#ftsocosi'" style="padding:2px">
<form id="salesordersizeFrm">
<input type="hidden" name="job_id" id="job_id" value=""/>
<input type="hidden" name="sale_order_id" id="sale_order_id" value=""/>
<input type="hidden" name="sale_order_country_id" id="sale_order_country_id" value=""/>
<input type="hidden" name="sale_order_item_id" id="sale_order_item_id" value=""/>
<input type="hidden" name="id" id="id" value=""/>
<code id="colorsizetable">
</code>
</form>
<div id="ftsocosi" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderColorSize.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordercolorsizeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderColorSize.remove()" >Delete</a>
    </div>
</div>
<div data-options="region:'west',border:true" style="width:450px; padding:2px">
<div class="easyui-layout"  data-options="fit:true" >
<div data-options="region:'north',border:true,title:'Item Entry',iconCls:'icon-more',footer:'#ftsoitem'" style="height:250px; padding:2px">
<form id="salesorderitemFrm">
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
                    <div class="col-sm-4 req-text">Style GMT</div>
                    <div class="col-sm-8">{!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Article No</div>
                    <div class="col-sm-8"><input type="text" name="article_no" id="article_no" value=""/></div>
                </div>
          </code>
       </div>
    </div>
    <div id="ftsoitem" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderItem.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesorderitemFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderItem.remove()" >Delete</a>
    </div>

  </form>
</div>
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="salesorderitemTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="40">ID</th>
        <th data-options="field:'name'" width="80">GMT Item</th>
        <th data-options="field:'article'" width="80">Article</th>
        <th data-options="field:'qty'" width="60" align="right">Qty</th>
        <th data-options="field:'rate'" width="60" align="right">Rate</th>
        <th data-options="field:'amount'" width="80" align="right">Amount</th>
   </tr>
</thead>
</table>
</div>
</div>
</div>
</div>

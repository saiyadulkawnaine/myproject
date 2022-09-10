<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'Add Color Size',footer:'#ftwgmtcosi'" style="padding:2px">
<form id="salesordergmtcolorsizeFrm">
        <input type="hidden" name="job_id" id="job_id" value=""/>
        <input type="hidden" name="sale_order_id" id="sale_order_id" value=""/>
        <input type="hidden" name="sale_order_country_id" id="sale_order_country_id" value=""/>
        <input type="hidden" name="id" id="id" value=""/>
        <code id="gmtcosi">
        </code>
        <div id="ftwgmtcosi" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderGmtColorSize.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordergmtcolorsizeFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderGmtColorSize.remove()" >Delete</a>
        </div>
    </form>
</div>
<div data-options="region:'west',border:true" style="width:350px; padding:2px">
<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'north',border:true,title:'Add New SalesOrderCountry',iconCls:'icon-more',footer:'#ft4'" style="height:300px; padding:2px">
<form id="salesordercountryFrm">
    <div id="container">
         <div id="body">
           <code>
             <div class="row">
                 <div class="col-sm-4">Sales Order No </div>
                 <div class="col-sm-8">
                 <input type="text" name="sale_order_no" id="sale_order_no" readonly/>
                 <input type="hidden" name="id" id="id" />
                 <input type="hidden" name="sale_order_id" id="sale_order_id"/>
                 <input type="hidden" name="job_id" id="job_id"/>
                 </div>
               </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Country</div>
                 <div class="col-sm-8">
                 {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                 </div>
               </div>

             <div class="row middle">
                 <div class="col-sm-4 req-text">Fabric Looks</div>
                 <div class="col-sm-8">{!! Form::select('fabric_looks', $fabriclooks,'',array('id'=>'fabric_looks')) !!}</div>
               </div>
               <div class="row middle">
                 <div class="col-sm-4 req-text">Cut Off Date</div>
                 <div class="col-sm-8"><input type="text" name="cut_off_date" id="cut_off_date" readonly/></div>
             </div>
             <div class="row middle">
               <div class="col-sm-4 req-text">Cut Off</div>
               <div class="col-sm-8">{!! Form::select('cut_off', $cutoff,'',array('id'=>'cut_off','onchange'=>'MsSalesOrderCountry.setCutOffDate(this.value)')) !!}</div>
             </div>
             <div class="row middle">
               <div class="col-sm-4 req-text">Ship Date</div>
               <div class="col-sm-8"><input type="text" name="country_ship_date" id="country_ship_date" readonly/></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Breakdown Basis</div>
                 <div class="col-sm-8">{!! Form::select('breakdown_basis', $breakdownbasis,'',array('id'=>'breakdown_basis')) !!}</div>
               </div>

             <div class="row middle">
                 <div class="col-sm-4">SAM</div>
                 <div class="col-sm-8"><input type="text" name="sam" id="sam" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">No of Carton</div>
                 <div class="col-sm-8"><input type="text" name="no_of_carton" id="no_of_carton" class="number integer" /></div>
             </div>
          </code>
       </div>
    </div>
    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrderCountry.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesordercountryFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrderCountry.remove()" >Delete</a>
    </div>

  </form>
</div>
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="salesordercountryTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'id'" width="80">ID</th>
        <th data-options="field:'salesorder'" width="100">Sales Order</th>
        <th data-options="field:'country'" width="100">Country</th>
        <th data-options="field:'qty'" width="80" align="right">Qty</th>
        <th data-options="field:'rate'" width="60" align="right">Rate</th>
        <th data-options="field:'amount'" width="80" align="right">Amount</th>
        <th data-options="field:'shipdate'" width="80">Ship Date</th>
   </tr>
</thead>
</table>
</div>

</div>
</div>
</div>
<div id="wgmtcosi" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true" style="padding:2px">


        </div>
        <div data-options="region:'south',border:true,title:'List'" style="height:300px; padding:2px">
        </div>
    </div>
</div>

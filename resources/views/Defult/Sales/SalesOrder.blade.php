<div class="easyui-layout"  data-options="fit:true">
<div data-options="region:'center',border:true,title:'List'" style="padding:2px">
<table id="salesorderTbl" style="width:100%">
<thead>
    <tr>
      <th data-options="field:'id'" width="30">ID</th>
      <th data-options="field:'job'" width="70">Job</th>
      <th data-options="field:'saleorderno'" width="100">Sale Order No</th>
      <th data-options="field:'qty'" width="70" align="right">Qty</th>
      <th data-options="field:'rate'" width="60" align="right">Rate</th>
      <th data-options="field:'amount'" width="80" align="right">Amount</th>
      <th data-options="field:'lead_time'" width="80" align="right">Lead Time</th>
      <th data-options="field:'placedate'" width="80">Place Date</th>
      <th data-options="field:'receivedate'" width="80">Receive Date</th>
      <th data-options="field:'shipdate'" width="80">Ship Date</th>
      <th data-options="field:'produced_company_id'" width="80">Produced Company</th>
      <th data-options="field:'fileno'" width="70">File No</th>


   </tr>
</thead>
</table>
</div>
<div data-options="region:'west',border:true,title:'Add New SalesOrder',footer:'#ft3'" style="width:350px; padding:2px">
<form id="salesorderFrm">
    <div id="container">
         <div id="body">
           <code>

             <div class="row">
                 <div class="col-sm-4 req-text">Job</div>
                 <div class="col-sm-8">
                 <input type="text" name="job_no" id="job_no" disabled/>
                 <input type="hidden" name="job_id" id="job_id" />
                 <input type="hidden" name="id" id="id" />
                 </div>
               </div>
               <div class="row middle">
                 <div class="col-sm-4 req-text">Projection </div>
                 <div class="col-sm-8">

                 {!! Form::select('projection_id', $projection,'',array('id'=>'projection_id')) !!}
                 </div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Sale Order No</div>
                 <div class="col-sm-8"><input type="text" name="sale_order_no" id="sale_order_no" /></div>
              </div>
             <div class="row middle">
                 <div class="col-sm-4">Produced Company</div>
                 <div class="col-sm-8">{!! Form::select('produced_company_id', $company,'',array('id'=>'produced_company_id')) !!}</div>
              </div>
              <div class="row middle">
                 <div class="col-sm-4 req-text">Place Date</div>
                 <div class="col-sm-8"><input type="text" name="place_date" id="place_date" class="datepicker" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Receive Date</div>
                 <div class="col-sm-8"><input type="text" name="receive_date" id="receive_date" class="datepicker" /></div>
              </div>
              <div class="row middle">
                 <div class="col-sm-4 req-text">Ship Date</div>
                 <div class="col-sm-8"><input type="text" name="ship_date" id="ship_date" class="datepicker" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4 req-text">Price/Unit</div>
                 <div class="col-sm-8"><input type="text" name="rate" id="rate" class="number integer" /></div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">File No</div>
                 <div class="col-sm-8"><input type="text" name="file_no" id="file_no" /></div>
               </div>
               <div class="row middle">
                   <div class="col-sm-4">Internal Ref.</div>
                   <div class="col-sm-8"><input type="text" name="internal_ref" id="internal_ref" /></div>
                 </div>
                 
             <div class="row middle">
                 <div class="col-sm-4">TNA To </div>
                 <div class="col-sm-8">{!! Form::select('tna_to', $tnatask,'',array('id'=>'tna_to')) !!}</div>
               </div>
               <div class="row middle">
                 <div class="col-sm-4">TNA From </div>
                 <div class="col-sm-8">{!! Form::select('tna_from', $tnatask,'',array('id'=>'tna_from')) !!}</div>
             </div>
             <div class="row middle">
                 <div class="col-sm-4">Remarks </div>
                 <div class="col-sm-8"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
             </div>

             <div class="row middle">
                 <div class="col-sm-4 req-text">Status </div>
                 <div class="col-sm-8">

                 {!! Form::select('order_status', $status,'',array('id'=>'order_status')) !!}
                 </div>
             </div>

          </code>
       </div>
    </div>
    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsSalesOrder.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('salesorderFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsSalesOrder.remove()" >Delete</a>
    </div>

  </form>
</div>
</div>

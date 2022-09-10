<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="workstudylinetabs">
 <div title="Reference Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="wstudylinesetupTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'company_id'" width="100">Company</th>
       <th data-options="field:'location_id'" width="100">Location</th>
       <th data-options="field:'line_code'" width="100">Line Code</th>
       <th data-options="field:'line_floor'" width="100">Line Floor</th>
       <th data-options="field:'line_merged_id'" width="100">Line Merged</th>
      </tr>
     </thead>
    </table>
   </div>
   <div
    data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft2'"
    style="width:350px; padding:2px">
    <form id="wstudylinesetupFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Company</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                        <input type="hidden" name="id" id="id" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Location</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Line Merged</div>
                                    <div class="col-sm-8">
                                        {!! Form::select('line_merged_id', $yesno,'',array('id'=>'line_merged_id')) !!}
                                    </div>
                                </div>  
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#CCC;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsWstudyLineSetup.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('wstudylinesetupFrm')">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsWstudyLineSetup.remove()">Delete</a>
     </div>
    </form>
   </div>
  </div>
 </div>
 <div title="Line No" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'New Subsection',footer:'#linenoft'"
    style="width:450px; padding:2px">
    <form id="wstudylinesetuplineFrm">
     <input type="hidden" name="wstudy_line_setup_id" id="wstudy_line_setup_id" value="" />
    </form>
    <table id="wstudylinesetuplineTbl">
    </table>
    <div id="linenoft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
     <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
      iconCls="icon-save" plain="true" id="save" onClick="MsWstudyLineSetupLine.submit()">Save</a>
    </div>
   </div>
   <div data-options="region:'center',border:true,title:'Taged Subsection'" style="padding:2px">
    <table id="wstudylinesetuplinesavedTbl">
    </table>
   </div>
  </div>
 </div>
 <div title="Engaged Resource Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'New Details',iconCls:'icon-more',footer:'#workstudydetailft'"
    style="width:350px; padding:2px">
    <form id="wstudylinesetupdtlFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="wstudy_line_setup_id" id="wstudy_line_setup_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Date</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="from_date" id="from_date" class="datepicker" placeholder=" From" />
                                        <input type="hidden" name="to_date" id="to_date" class="datepicker"  placeholder=" To" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Operator</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="operator" id="operator" value="" class="number integer" onchange="MsWstudyLineSetupDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Helper</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="helper" id="helper" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Line Chief</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="line_chief" id="line_chief" value="" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Working Hour</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="working_hour" id="working_hour" value="" class="number integer" onchange="MsWstudyLineSetupDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">OTH</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="overtime_hour" id="overtime_hour" value="" class="number integer" onchange="MsWstudyLineSetupDtl.calculateTotalmnt()" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Total Mnt</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="total_mnt" id="total_mnt" value="" class="number integer" readonly />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Target Hour</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="target_per_hour" id="target_per_hour" value="" class="number integer"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Sewing Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sewing_start_at" id="sewing_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Sewing Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sewing_end_at" id="sewing_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Lunch Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lunch_start_at" id="lunch_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5  req-text">Lunch Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="lunch_end_at" id="lunch_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tiffin Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tiffin_start_at" id="tiffin_start_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Tiffin Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="tiffin_end_at" id="tiffin_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Comments</div>
                                    <div class="col-sm-7">
                                       <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="workstudydetailft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsWstudyLineSetupDtl.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('wstudylinesetupdtlFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsWstudyLineSetupDtl.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="wstudylinesetupdtlTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'from_date'" width="80">From Date</th>
       <th data-options="field:'to_date'" width="80">To Date</th>
       {{-- <th data-options="field:'style_ref'" width="80">Style Ref</th> --}}
       <th data-options="field:'operator'" width="100">Operator</th>
       <th data-options="field:'helper'" width="100">Helper</th>
       <th data-options="field:'line_chief'" width="100">Line Chief</th>
       <th data-options="field:'working_hour'" width="100">WH</th>
       <th data-options="field:'overtime_hour'" width="100">OTH</th>
       <th data-options="field:'total_mnt'" width="100">Total MmT</th>
       <th data-options="field:'sewing_start_at'" width="100">Sewing Started</th>
       <th data-options="field:'sewing_end_at'" width="100">Sewing Ended</th>
       <th data-options="field:'lunch_start_at'" width="100">Lunch Started</th>
       <th data-options="field:'lunch_end_at'" width="100">Lunch Ended</th>
       <th data-options="field:'tiffin_start_at'" width="100">Tiffin Started</th>
       <th data-options="field:'tiffin_end_at'" width="100">Tiffin Ended</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Order Details" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div
    data-options="region:'west',border:true,title:'Order Details',iconCls:'icon-more',footer:'#wstudylinesetupdtlordFrmft'"
    style="width:350px; padding:2px">
    <form id="wstudylinesetupdtlordFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="wstudy_line_setup_dtl_id" id="wstudy_line_setup_dtl_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                
                                <div class="row">
                                    <div class="col-sm-5">GMT Item </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="item_description" id="item_description" ondblclick="MsWstudyLineSetupDtlOrd.openLineSetupStyleRefWindow()" placeholder=" Double click" readonly/>
                                        <input type="hidden" name="style_gmt_id" id="style_gmt_id" value="" />        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-5">Order No </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sale_order_no" id="sale_order_no" disabled/>
                                        <input type="hidden" name="sales_order_id" id="sales_order_id" value="" />        
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Qty</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="qty" id="qty" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Sewing Hr Starts</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sewing_start_at" id="sewing_start_at" value="09:00:00 AM" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Sewing Hr Ends</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="sewing_end_at" id="sewing_end_at" value="" placeholder="time" class="timepicker"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Comments</div>
                                    <div class="col-sm-7">
                                       <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="wstudylinesetupdtlordFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsWstudyLineSetupDtlOrd.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('wstudylinesetupdtlordFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsWstudyLineSetupDtlOrd.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="wstudylinesetupdtlordTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'company_name'" width="80">Company</th>
       <th data-options="field:'style_ref'" width="80">Style Ref</th>
       <th data-options="field:'item_description'" width="120">Gmt Item</th>
       <th data-options="field:'buyer_name'" width="70">Buyer</th>
       <th data-options="field:'sale_order_no'" width="80">Order No</th>
       <th data-options="field:'qty'" width="100">Qty</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
 <div title="Minute Ajustments" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',footer:'#wstudyminadjFrmft'"
    style="width:450px; padding:2px">
    <form id="wstudylinesetupminadjFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row">
                                    <input type="hidden" name="wstudy_line_setup_dtl_id" id="wstudy_line_setup_dtl_id" value="" />
                                    <input type="hidden" name="id" id="id" value="" />
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Adjustment Reasons</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('minute_adj_reason_id', $minuteadjustmentreasons,'',array('id'=>'minute_adj_reason_id')) !!} 
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Hours</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_hour" id="no_of_hour" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">No of employee/resource</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_resource" id="no_of_resource" value="" class="number integer" onchange="MsWstudyLineSetupMinAdj.calculateMinute()"/>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Adjustable Minutes</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_of_minute" id="no_of_minute" value="" class="number integer" />
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="wstudyminadjFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsWstudyLineSetupMinAdj.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('wstudylinesetupminadjFrm')">Reset</a>
      <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsWstudyLineSetupMinAdj.remove()">Delete</a>
     </div>
    </form>
   </div>
   <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
    <table id="wstudylinesetupminadjTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'minute_adj_reason_id'" width="150">Adjustment Reasons</th>
       <th data-options="field:'no_of_hour'" width="100">Hour</th>
       <th data-options="field:'no_of_resource'" width="100">No of employee/<br>resource</th>
       <th data-options="field:'no_of_minute'" width="120">Adjustable Minutes</th>
      </tr>
     </thead>
    </table>
   </div>
  </div>
 </div>
</div>

<div id="openlinesetupstylerefwindow" class="easyui-window" title="Style Reference Search Window"
 data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
 <div class="easyui-layout" data-options="fit:true">
  <div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
   <div class="easyui-layout" data-options="fit:true">
    <div id="body">
     <code>
                        <form id="linesetupstylerefsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Buyer</div>
                                <div class="col-sm-8">
                                    {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Style Ref</div>
                                <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref" value="">
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Job No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="job_no" id="job_no" value="" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
    </div>
    <p class="footer">
     <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
      onClick="MsWstudyLineSetupDtlOrd.searchLineSetupStyleRefGrid()">Search</a>
    </p>
   </div>
  </div>
  <div data-options="region:'center'" style="padding:10px;">
   <table id="linesetupstylerefsearchTbl" style="width:100%">
    <thead>
     <tr>
      <th data-options="field:'style_id'" width="30">Style ID</th>
      <th data-options="field:'style_ref'" width="70">Style Ref</th>
      <th data-options="field:'item_description'" width="120">Gmt Item</th>
      <th data-options="field:'buyer_name'" width="70">Buyer</th>
      <th data-options="field:'sale_order_no'" width="100">Order No</th>
      <th data-options="field:'ship_date'" width="100">Ship Date</th>
      <th data-options="field:'qty'" width="100" align="right">Order Qty</th>
     </tr>
    </thead>
   </table>
  </div>
  <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
   <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
    onclick="$('#openlinesetupstylerefwindow').window('close')" style="width:80px">Close</a>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Workstudy/MsAllWstudyLineSetUpController.js"></script>
<script>
 $(".datepicker").datepicker({
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 999999
            });
        },
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });

    $(".timepicker").timepicker(
        {
            'timeFormat': 'h:i:s A',
            'interval': 60,
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':30,
            'listWidth': 1,
            'width': '200px !important',
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    //$('#sewing_end_at').timepicker({defaultTime: '09:00:00 AM'});

    $(document).ready(function() {
	var chiefname = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/wstudylinesetupdtl/getchiefname?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});
	
	$('#line_chief').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'line_chief',
		limit:1000,
		source: chiefname,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});
});
</script>
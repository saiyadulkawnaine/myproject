<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="tnaordtabs">
    <div title="TNA Details" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'center',border:true,title:'Lists'" style="padding:2px">
                <table id="tnaordTbl" style="width:100%">
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
            <div data-options="region:'west',border:true,title:'Add Details',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#tnaordFrmFt'" style="width:350px; padding:2px">
                <form id="tnaordFrm">
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
                    <div id="tnaordFrmFt" style="padding:0px 0px; text-align:right; background:#CCC;">
                        <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsTnaOrd.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('tnaordFrm')">Reset</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsTnaOrd.remove()">Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript" src="<?php echo url('/');?>/js/Planing/MsTnaOrdController.js"></script>
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
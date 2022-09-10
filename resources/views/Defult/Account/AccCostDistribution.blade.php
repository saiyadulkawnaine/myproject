<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="acccostdistributiontabs">
 <div title="Cost Distribution Entry" style="padding:2px">
  <div class="easyui-layout" data-options="fit:true">
   <div data-options="region:'center',border:true,title:'Cost Distribution List'" style="padding:2px">
    <table id="acccostdistributionTbl" style="width:100%">
     <thead>
      <tr>
       <th data-options="field:'id'" width="80">ID</th>
       <th data-options="field:'cost_type_id'" width="100">Cost Type</th>
       <th data-options="field:'form_date'" width="100">From Date</th>
       <th data-options="field:'to_date'" width="100">To Date</th>
       <th data-options="field:'amount'" width="100">Amount</th>
       <th data-options="field:'remarks'" width="100">Remarks</th>
      </tr>
     </thead>
    </table>
   </div>
   <div data-options="region:'west',border:true,title:'Add New Entry',footer:'#ft2'" style="width: 350px; padding:2px">
    <form id="acccostdistributionFrm">
     <div id="container">
      <div id="body">
       <code>
                                <div class="row middle">
                                 <div class="col-sm-4 req-text">Date Range</div>
                                 <div class="col-sm-4" style="padding-right: 0px;">
                                  <input type="text" name="form_date" id="form_date" class="form_date" placeholder="From" />
                                  <input type="hidden" name="id" id="id" value=""/>
                                 </div>
                                 <div class="col-sm-4" style="padding-left: 0px;">
                                  <input type="text" name="to_date" id="to_date" class="to_date" placeholder="To"/>
                                 </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Cost Type</div>
                                    <div class="col-sm-8">
                                        <select name="cost_type_id" id="cost_type_id">
                                         <option value="">-Select-</option>
                                         <option value="1">Commercial Exp</option>
                                         <option value="2">Couries</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4 req-text">Amount</div>
                                    <div class="col-sm-8">
                                        <input type="text" name="amount" id="amount" value="" class="number integer" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-4">Remarks</div>
                                    <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                            </code>
      </div>
     </div>
     <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
      <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
       iconCls="icon-save" plain="true" id="save" onClick="MsAccCostDistribution.submit()">Save</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsAccCostDistribution.resetForm()">Reset</a>
      <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
       iconCls="icon-remove" plain="true" id="delete" onClick="MsAccCostDistribution.remove()">Delete</a>
     </div>

    </form>
   </div>
  </div>
 </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Account/MsAccCostDistributionController.js"></script>
<script>
 (function(){    
    $(".form_date").datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
            if (date.getDate() == 1) {
                return [true, ''];
            }
            return [false, ''];
        }
    });

    function LastDayOfMonth(Year, Month) {
        return (new Date((new Date(Year, Month + 1, 1)) - 1)).getDate();
    }

    $('.to_date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
            //getDate() returns the day (0-31)
            if (date.getDate() == LastDayOfMonth(date.getFullYear(), date.getMonth())) {
                return [true, ''];
            }
            return [false, ''];
        }
    });
    })(jQuery);
</script>
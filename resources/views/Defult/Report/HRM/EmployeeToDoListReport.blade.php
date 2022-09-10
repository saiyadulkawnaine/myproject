<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Todo List Report'" style="padding:2px">
        <div id="employeetodolistreportmatrix"></div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#ft2'" style="width:280px; padding:2px">
        <form id="employeetodolistreportFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle">
                            <div class="col-sm-4">Date Range</div>
                            <div class="col-sm-4" style="padding-right:0px">
                                <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="  From" />
                            </div>
                            <div class="col-sm-4" style="padding-left:0px">
                                <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="  To" />
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Employee</div>
                            <div class="col-sm-8">
                                {!! Form::select('user_id', $user,'',array('id'=>'user_id','style'=>'width: 100%; border-radius:2px')) !!}
                            </div>
                        </div>
                        <div class="row middle">
                            <div class="col-sm-4">Priority</div>
                            <div class="col-sm-8">
                                {!! Form::select('priority_id', $todopriority,'',array('id'=>'priority_id')) !!}
                            </div>
                        </div>         
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeToDoListReport.get()">Show</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListReport.resetForm('gateentryreportFrm')" >Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeToDoListReport.pdf()" >PDF</a>
            </div>
        </form>
    </div>
</div>

    
<script type="text/javascript" src="<?php echo url('/');?>/js/report/HRM/MsEmployeeToDoListReportController.js"></script>
<script>
    (function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
      });

      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      $('#employeetodolistreportFrm [id="user_id"]').combobox();
   })(jQuery);
</script>
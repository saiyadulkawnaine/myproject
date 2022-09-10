<div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'Knitting Plan Report'" style="padding:2px">
        <div id="plknitreportData">
            
        </div>
    </div>
    <div data-options="region:'west',border:true,title:'Search',footer:'#plknitreportFrmft'" style="width:350px; padding:2px">
    <form id="plknitreportFrm">
        <div id="container">
             <div id="body">
               <code>
                 <div class="row">
                     <div class="col-sm-4 req-text">Company</div>
                     <div class="col-sm-8">
                       {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}  
                     </div>
                 </div>
                 <div class="row middle">
                     <div class="col-sm-4 req-text">Location</div>
                     <div class="col-sm-8">
                       {!! Form::select('location_id', $location,'',array('id'=>'location_id')) !!}  
                     </div>
                 </div>
               <div class="row middle">
                    <div class="col-sm-4 req-text">Plan Date </div>
                        <div class="col-sm-4" style="padding-right:0px">
                        <input type="text" name="date_from" id="date_from" class="datepicker" placeholder="From" />
                    </div>
                    <div class="col-sm-4" style="padding-left:0px">
                        <input type="text" name="date_to" id="date_to" class="datepicker"  placeholder="To" />
                    </div>
                </div>
              </code>
           </div>
        </div>
        <div id="plknitreportFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsPlKnitReport.get()">Show</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsPlKnitReport.resetForm('plknitreportFrm')" >Reset</a>
        </div>   
      </form>
    </div>
 </div>
 
 <script type="text/javascript" src="<?php echo url('/');?>/js/report/Subcontract/Kniting/MsPlKnitReportController.js"></script>
 <script>
     $(".datepicker" ).datepicker({
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true
     });
 </script>
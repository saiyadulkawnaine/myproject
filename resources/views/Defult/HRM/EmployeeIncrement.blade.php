<div class="easyui-layout animated rollIn" data-options="fit:true" style="-moz-box-shadow: 0 0 2px 2px #ccc;-webkit-box-shadow: 0 0 2px 2px #ccc;box-shadow: 0 0 2px 2px #ccc;">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="employeeincrementTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="40">ID</th>
                    <th data-options="field:'file_src'" width="140">File</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New Employee Increment',footer:'#ft2'" style="width: 400px; padding:2px">
        <form id="employeeincrementFrm">
            <div id="container">
                <div id="body">
                    <code>
                        <div class="row middle" style="display:none">
                        <input type="hidden" name="id" id="id" value="" />
                        </div>
                        <div class="row middle">
                        <div class="col-sm-4 req-text">File Upload</div>
                        <div class="col-sm-8">
                        <input type="file" id="increment_file" name="increment_file" value="" />              
                        </div>
                        </div>
                    </code>
                </div>
            </div>
            <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsEmployeeIncrement.submit()">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeIncrement.resetForm()">Reset</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeIncrement.remove()">Delete</a>
                <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsEmployeeIncrement.sendtoapi()">Send</a>
            </div>

        </form>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsEmployeeIncrementController.js"></script>
<script>
    $(".datepicker").datepicker({
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

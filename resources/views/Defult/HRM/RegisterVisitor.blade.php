<div class="easyui-layout animated rollIn" style="width:100%;height:100%; border:none">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
            <table id="registervisitorTbl" style="width:100%">
                <thead>
                    <tr>
                        <th data-options="field:'approved_by'" width="45"></th>
                        <th data-options="field:'id'" width="40">ID</th>
                        <th data-options="field:'name'" width="120">Visitor Name</th>
                        <th data-options="field:'contact_no'" width="100">Phone No</th>
                        <th data-options="field:'organization_dtl'" width="100">Organization</th>
                        <th data-options="field:'arrival_time'" width="120">Arrival Time</th>
                        <th data-options="field:'user_name'" width="120">Meeting With</th>
                        <th data-options="field:'purpose'" width="120">Purpose</th>
                        <th data-options="field:'arrival_date'" width="120">Arrival Date</th>
                        <th data-options="field:'approve_user_name'" width="120">Approving User</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true,title:'Add Information',footer:'#registervisitorFrmft'" style="width: 350px; padding:2px">
            <form id="registervisitorFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row middle" style="display:none">
                                <input type="hidden" name="id" id="id" value="" />
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Visitor Name</div>
                                <div class="col-sm-8">
                                    <input type="text" name="name" id="name" value="" placeholder=" name"/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Phone No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="contact_no" id="contact_no" value="" placeholder=" 01234123456 "/>
                                </div>
                            </div> 
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Organization</div>
                                <div class="col-sm-8">
                                    <textarea name="organization_dtl" id="organization_dtl"></textarea>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Arrival Date</div>
                                <div class="col-sm-8">
                                    <input type="text" name="arrival_date" id="arrival_date" class="datepicker" placeholder="yyyy-mm-dd" value="{{ date('Y-m-d') }}" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Arrival Time</div>
                                <div class="col-sm-8">
                                    <input type="text" name="arrival_time" id="arrival_time" class="timepicker" placeholder=" h:mm am/pm" />
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Meeting With</div>
                                <div class="col-sm-8">
                                    {!! Form::select('user_id', $user,'', array('id'=>'user_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Purpose</div>
                                <div class="col-sm-8">
                                    <textarea name="purpose" id="purpose"></textarea>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Approving User</div>
                                <div class="col-sm-8">
                                    {!! Form::select('approve_user_id', $user,'', array('id'=>'approve_user_id','style'=>'width: 100%; border-radius:2px')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Location</div>
                                <div class="col-sm-8">
                                    {!! Form::select('location_id', $location,'', array('id'=>'location_id')) !!}
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4">Departure Time</div>
                                <div class="col-sm-8">
                                    <input type="text" name="departure_time" id="departure_time" class="timepicker" placeholder=" h:mm am/pm" />
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="registervisitorFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsRegisterVisitor.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRegisterVisitor.resetForm()">Reset</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRegisterVisitor.remove()">Delete</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsRegisterVisitor.pdf()">Approve Ticket</a>
                </div>
            </form>
        </div>
    </div>
</div>
    
<script type="text/javascript" src="<?php echo url('/');?>/js/HRM/MsRegisterVisitorController.js"></script>
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
        changeYear: true,
    });

    $('#arrival_time').timepicker(
        {
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );
    $('#departure_time').timepicker(
        {
            'minTime': '12:00pm',
            'maxTime': '11:59am',
            'showDuration': false,
            'step':1,
            'scrollDefault': 'now',
            'change': function(){
                alert('m')
            }
        }
    );

    $('.integer').keyup(function () {
        if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
        this.value = this.value.replace(/[^0-9\.]/g, '');
        }
    });

    $('#registervisitorFrm [id="user_id"]').combobox();
    $('#registervisitorFrm [id="approve_user_id"]').combobox();
})(jQuery);

$(document).ready(function() {
        $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
        });

        var bloodhound = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/registervisitor/getvisitor?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#name').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
      },
        {
            name: 'name',
            limit:1000,
            source: bloodhound,
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
         
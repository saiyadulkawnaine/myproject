<style>
    table {
        margin: 0 auto;
    }
    th {
        background-color: #ccc;
    }
</style>
@if($is_html)
<table>
    <tr><td align="center"><img width="300" height="100" src="images/logo/{{ $company->logo }}"/></td></tr>
    <tr><td align="center">{{$company->address}}</td></tr>
    <tr><td align="center"><strong><u>PURCHASE REQUISITION :  {{  $invpurreq['remarks'] }} </u></strong></td></tr>
</table>
<br/><br/>
@endif
<table cellspacing="0" cellpadding="2">
    <tr>
        <td align="left" ><strong>PR No&nbsp;:&nbsp;&nbsp;  {{  $invpurreq['requisition_no'] }}</strong></td>
        <td align="left"><strong>Date&nbsp;:&nbsp;  {{  $invpurreq['req_date'] }}</strong></td>
        <td align="left"><strong>Pay Mode &nbsp;:</strong>&nbsp;  {{  $invpurreq['pay_mode'] }}</td>
        <td align="left">&nbsp;<strong>Currency&nbsp;:</strong>&nbsp;  {{  $invpurreq['currency_name'] }}</td>
        <td align="left">&nbsp;<strong>Delivery By&nbsp;:</strong>&nbsp;  {{  $invpurreq['delivery_by'] }}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<table border="1" cellspacing="0" cellpadding="2">
     <?php
        $i=1;
        $total=0;
        $balance_total=0;
        //$paid_amount=0;
    ?>
    <thead>
        <tr>
            <td width="20" align="center"><strong>SL</strong></td>
            <td width="70" align="center"><strong>Item<br/>Category</strong></td>
            <td width="170" align="center"><strong>Item Description</strong></td>
            <td width="50" align="center"><strong>Current<br/>Stock</strong></td>
            <td width="50" align="center"><strong>Re-Order Level</strong></td>
            <td width="50" align="center"><strong>Quantity</strong></td>
            <td width="40" align="center"><strong>Unit</strong></td>
            <td width="60" align="center"><strong>Rate</strong></td>
            <td width="80" align="center"><strong>Amount</strong></td>
            <td width="70" align="center"><strong>Consuming<br/>Department</strong></td>
            <td width="80" align="center"><strong>Remarks</strong></td>
            <td width="100" align="center"><strong>Last <br/>Requisition</strong></td>
            <td width="150" align="center"><strong>Last <br/>Receive</strong></td>
        </tr>
    </thead>
    <tbody>
    @foreach($invpurreq['invpurreqitem'] as $requision)
    <tr>
        <td width="20">{{ $i }}</td>
        <td width="70" align="center">{{ $requision->itemcategory_name }}</td>
        <td width="170">{{ $requision->item_description }}</td>
        <td width="50" align="right">{{ $requision->stock_qty }}</td>
        <td width="50" align="right">{{ $requision->reorder_level }}</td>
        <td width="50" align="right">{{ number_format($requision->item_qty,2) }}</td>
        <td width="40">{{ $requision->uom_code }}</td>
        <td width="60" align="right">{{ number_format($requision->item_rate,2)}}</td>
        <td width="80" align="right">{{ number_format($requision->item_amount,2) }}</td>
        <td width="70">{{ $requision->department_name }}</td>
        <td width="80">{{ $requision->item_remarks }}</td>

        <td width="100">No:{{ $requision->requisition_no }}; {{ $requision->req_date }}<br/>Qty:{{ $requision->last_qty }}
        </td>
        <td width="150">No:{{ $requision->receive_no }}, {{ $requision->receive_date }}; Qty:{{ $requision->receive_qty }}, Rate:{{ $requision->receive_rate }}; {{ $requision->supplier_name}}
        </td>
    </tr>
    <?php
        $i++;
        $total+=$requision->item_amount;
       // $paid_amount+=$requision->paid_amount;
       // $balance_total=$total-$paid_amount;
       // $inword=$requision->inword;
    ?>
    @endforeach 
    </tbody>
</table>
<?php
    $balance_total=$total-$invpurreq['paid_amount'];
?>
<p>
    <table border="0" cellspacing="1" cellpadding="2">
        <tbody>
            <tr>
                <td align="left" colspan="2"><strong>Total :</strong></td>
                <td align="left">{{ number_format($total,2) }}</td>
                <td align="left" colspan="2"><strong>Amount Paid :</strong></td>
                <td align="left">{{ number_format($invpurreq['paid_amount'],2) }}</td>
                <td align="left" colspan="2"><strong>Balance To Pay:</strong></td>
                <td align="left">{{ number_format($balance_total,2) }}</td>
                <td align="center"></td>
                <td align="center"></td>
                <td align="center"></td>
            </tr>
            <tr>
                <td colspan="5"><strong>In Word : {{ $invpurreq['invpurreqitem']->inword }} </strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</p>
@if ($invpurreq['invpurreqassetbreakdown']->isNotEmpty())
    <p><strong>Breakdown Details:</strong><br/>
    <table cellspacing="0" cellpadding="2" border="1">
        <tr>
            <td width="30" align="center"><strong>SL</strong></td>
            <td width="70" align="center"><strong>System ID</strong></td>
            <td width="100" align="center"><strong>Asset No</strong></td>
            <td width="200" align="center"><strong>Asset Name</strong></td>
            <td width="70" align="center"><strong>Date</strong></td>
            <td width="130" align="center"><strong>Reason</strong></td>
            <td width="130" align="center"><strong>Decision</strong></td>
            <td width="240" align="center"><strong>Findings</strong></td>
        </tr>
        <?php
            $i=1;
        ?>
        @foreach ($invpurreq['invpurreqassetbreakdown'] as $breakdowndata)
        <tbody>
            <tr>
                <td width="30" align="center">{{ $i++ }}</td>
                <td width="70" align="left">{{ $breakdowndata->asset_breakdown_id }}</td>
                <td width="100" align="left">{{ $breakdowndata->custom_no }}</td>
                <td width="200" align="left">{{ $breakdowndata->asset_name }}</td>
                <td width="70" align="left">{{ $breakdowndata->breakdown_date }}</td>
                <td width="130" align="left">{{ $breakdowndata->reason }}</td>
                <td width="130" align="left">{{ $breakdowndata->decision }}</td>
                <td width="240" align="left">{{ $breakdowndata->remarks }}</td>
            </tr>
        </tbody>
        @endforeach
    </table>
    </p>
@endif
<table cellspacing="0" cellpadding="2">
    <tr>     
        <td colspan="2" align="left"><strong>Date:</strong></td>
        <td colspan="2" align="center"><strong>Amount</strong></td>
        <td colspan="2" align="center"><strong>Paid To:</strong></td>
        <td colspan="2" align="center"></td>
        <td colspan="3" align="left">Entry By</td>
        <td colspan="3" align="center">Entry Date</td>
        <td colspan="1" align="center"></td>
    </tr>
    @foreach ($invpurreq['invpurreqpaid'] as $item)
    <tr>       
        <td colspan="2" align="left">{{ $item->paid_date }}</td>
        <td colspan="2" align="center">{{ number_format($item->paid_amount,2) }}</td>
        <td colspan="2" align="center">{{ $item->user_name }}</td>
        <td colspan="2" align="center"></td>
        <td colspan="3" align="left">{{ $item->updatedby_user_name }}</td>
        <td colspan="3" align="center">{{ $item->entry_date }}</td>
        <td colspan="1" align="center"></td>
    </tr>
    
    @endforeach
</table>
<br/><br/><br/>
@if($is_html)
    <table border="1" cellspacing="0" cellpadding="2">
    <tr>
        <th width="30">#</th>
        <th width="500">Comments</th>
        <th width="150">Comments By</th>
        <th width="200">Comments AT</th>
    </tr>  
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($invpurreq['comment_histories'] as $comments)
        <tr>
            <td width="30">{{$i}}</td>
            <td width="500">{{$comments->comments}}</td>
            <td width="150">{{$comments->user_name}}</td>
            <td width="200">{{$comments->comments_at}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
    </tbody>
</table>
    <br/><br/><br/>
    <table>
        <tr>
            <td width="638" align="center">
                <form id="invpurreqaprovalreturncommentFrm">
                    <textarea cols="3" rows="5" id="inv_pur_req_aproval_return_comments" name="inv_pur_req_aproval_return_comments"></textarea>
                </form>
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <br/>
    <table>
        <tr>
            <td width="638" align="center">
            @permission('approvefirst.invpurreqs')
                <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.approveSingle('{{$approval_type}}',{{$invpurreq['id']}})">Approve</a>
                <a href="javascript:void(0)" class="easyui-linkbutton  c2" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsInvPurReqApproval.appReturn('{{$approval_type}}',{{$invpurreq['id']}})">Return</a>
            @endpermission
                <a href="javascript:void(0)" class="easyui-linkbutton  c4" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px" iconCls="icon-save" plain="true" id="save" onClick="closeWindow()">Close</a>
            </td>
        </tr>
        <tr><td width="638" align="center"></td></tr>
    </table>
    <br/><br/><br/>
@endif
@if(!$is_html)
<table border="0" cellspacing="0" cellpadding="2">
    <tr>
        <td></td>
        <td>@if ($invpurreq['first_approval_signature'])<img src="{{ $invpurreq['first_approval_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($invpurreq['demand_user_signature'])<img src="{{ $invpurreq['demand_user_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($invpurreq['price_varify_signature'])<img src="{{ $invpurreq['price_varify_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($invpurreq['second_approval_signature'])<img src="{{ $invpurreq['second_approval_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($invpurreq['third_approval_signature'])<img src="{{ $invpurreq['third_approval_signature'] }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($invpurreq['final_approval_signature'])<img src="{{ $invpurreq['final_approval_signature'] }}" width="100", height="40"/>
            @endif
            @if (!$invpurreq['final_approval_name'])
            <h3 style="font-stretch: ultra-expanded">UNAPPROVED</h3>
        @endif</td>
    </tr>
    <tr>
        <td><strong>Entered By</strong></td>
        <td><strong>No Stock</strong></td>
        <td><strong>Demand By</strong></td>
        <td><strong>Price Verified By</strong></td>
        <td><strong>2nd Approved</strong></td>
        <td><strong>3rd Approved</strong></td>
        <td><strong>Final Approved</strong></td>
    </tr>
    <tr>
        <td><strong>{{ $invpurreq['user_name'] }}<br/>
            {{ $invpurreq['created_at'] }}
            </strong>
        </td>
        <td><strong>{{ $invpurreq['first_approval_emp_name'] }}<br/>
            {{ $invpurreq['first_approval_emp_contact'] }}<br/>
            {{ $invpurreq['first_approval_emp_designation'] }}<br/>
            {{ $invpurreq['first_approved_at'] }}
            </strong>
        </td>
        <td><strong>{{ $invpurreq['demand_user_name'] }}<br/>
            {{ $invpurreq['dd_designation'] }}<br/>
            {{ $invpurreq['demand_contact'] }} 
            </strong>
        </td>
        <td><strong>{{ $invpurreq['price_varify_user_name'] }}<br/>
            {{ $invpurreq['pv_designation'] }}<br/>
            {{ $invpurreq['price_varify_user_contact'] }}
            </strong>
        </td>
        <td><strong>{{ $invpurreq['second_approval_name'] }}<br/>
        {{ $invpurreq['second_approved_at'] }}</strong>
        </td>
        <td><strong>{{ $invpurreq['third_approval_name'] }}<br/>
            {{ $invpurreq['third_approved_at'] }}
            </strong>
        </td>
        <td><strong>{{ $invpurreq['final_approval_name'] }}<br/>
            {{ $invpurreq['final_approved_at'] }}
            </strong>
        </td>
    </tr>
    <tr>
        <td colspan="5"><strong>Edited By</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $invpurreq['update_user_name'] }}&nbsp;&nbsp;&nbsp;{{ $invpurreq['updated_at'] }}</td>
        <td></td>
        <td></td>
    </tr>
</table>
@endif
@if($is_html)
<script>
    function closeWindow(){
        $('#invpurreqApprovalDetailContainer').html('');
        $('#invpurreqApprovalDetailWindow').window('close');
    }
</script>
@endif
<h2 align="center"><u>Employee Movement Ticket</u></h2>
<h3 align="center">{{ $rows['name'] }}</h3>
<table>
    <tr>
        <td width="60">Department</td>
        <td width="450">{{ $rows['department_name'] }}</td>
        <td width="60">ID Card</td>
        <td width="200">{{ $rows['code'] }}</td>
    </tr>
    <tr>
        <td width="60">Designation</td>
        <td width="450">{{ $rows['designation_name'] }}</td>
        <td width="60">Phone</td>
        <td width="200">{{ $rows['contact'] }}</td>
    </tr>
    <tr>
        <td width="60">Section</td>
        <td width="450"></td>
        <td width="60">ERP ID</td>
        <td width="200">{{ $rows['employee_h_r_id'] }}</td>
    </tr>
</table>
<p></p>
<table border="1" cellpadding="1">
    <tr>
        <td align="center" width="20">SL</td>
        <td align="center" width="55">Out Date</td>
        <td align="center" width="55">Out Time</td>
        <td align="center" width="55">Return Date</td>
        <td align="center" width="55">Return Time</td>
        <td align="center" width="70">Purpose</td>
        <td align="center" width="100">Work Details</td>
        <td align="center" width="80">Destination</td>
        <td align="center" width="60">Conv.<br/>Amount</td>
        <td align="center" width="60">DA</td>
        <td align="center" width="50">Transport</td>
    </tr>
    <?php 
        $i=1;
        $conv_amount=0;
        $ta_da_amount=0;
    ?>
    @foreach ($empmovedtail as $item)
    <tr>
        <td align="center">{{ $i++ }}</td>
        <td align="center">{{ $item->out_date }}</td>
        <td align="center">{{ $item->out_time }}</td>
        <td align="center">{{ $item->return_date }}</td>
        <td align="center">{{ $item->return_time }}</td>
        <td align="center">{{ $item->purpose_id }}</td>
        <td align="center">{{ $item->work_detail }}</td>
        <td align="center">{{ $item->destination }}</td>
        <td align="right">{{ $item->amount }}</td>
        <td align="right">{{ $item->ta_da_amount }}</td>
        <td align="center">{{ $item->transport_mode_id }}</td>
    </tr>
    <?php 
        $conv_amount+=$item->amount;
        $ta_da_amount+=$item->ta_da_amount;
    ?>
    @endforeach
    <tr>
        <td align="center" colspan="8"></td>
        <td align="right">{{ $conv_amount }}</td>
        <td align="right">{{ $ta_da_amount }}</td>
        <td align="center"></td>
    </tr>
</table>
<p></p>
<p>Total TK: {{ $conv_amount+$ta_da_amount }}&nbsp;&nbsp;&nbsp;( In Words:{{ $rows['inword'] }} )</p>
<p></p>
<p></p>
<p></p>
<table>
    <tr>
        <td align="center" width="220">Prepared By</td>
        <td align="center" width="220">Line Manager</td>
        <td align="center" width="220">Approved By</td>
    </tr>
    <tr>
        <td align="center">{{ $rows['user_name'] }},date:{{ date('Y-m-d',strtotime($rows['created_at'])) }}; Updated by:{{ $rows['updated_user_name'] }},date:{{ ($rows['updated_at'] !== null)?date('Y-m-d',strtotime($rows['updated_at'])):null }}</td>
        <td align="center"></td>
        <td align="center">{{ $rows['approval_user_name'] }}</td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
    </tr>

    <tr>
        <td align="center"></td>
        <td align="center">Bill Approved By</td>
        <td align="center"></td>
    </tr>
</table>
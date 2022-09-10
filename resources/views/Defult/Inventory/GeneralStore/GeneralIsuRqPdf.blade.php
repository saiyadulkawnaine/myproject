<table cellspacing="0" cellpadding="2">
    <tr>
        <td width="250"><br/>
       
        </td>
        <td width="400" align="left">
            
        </td>
        <td width="120">
            <br/>
            Requisition No:<br/>
            Requisition Date:<br/>
        </td>
        <td width="140">
            <br/>
            {{ $data['master']->rq_no }}<br/>
            {{ $data['master']->rq_date }}<br/>
        </td>
    </tr>
    <tr>
        <td width="910">
            <br/>
        </td>
    </tr>
    <tr>
        <td width="910">
          Remarks: {{$data['master']->remarks }}
        </td>
    </tr>
</table>
<br/>
<?php
    $i=1;
?>
<table cellspacing="0" cellpadding="2" border="1">
    <thead>
        <tr>
            <td width="30" align="center">#</td>
            <td width="60" align="center">Item Category</td>
            <td width="60" align="center">Item Class</td>
            <td width="200" align="center">Item Des.</td>
            <td width="60" align="center">M/C No</td>
            <td width="70" align="center">Qty</td>
            <td width="45" align="center">UOM</td>
            <td width="115" align="center">Sale Order No</td>
            <td width="70" align="center">Department</td>
            <td width="60" align="center">Purpose</td>
            <td width="76" align="center">Last Req. Qty & Date  </td>
            <td width="100" align="center">Remarks</td>
        </tr>
    </thead>
    <tbody>
        @foreach($data['details'] as $row)
        <tr>
            <td width="30" align="center">{{ $i }}</td>
            <td width="60" align="center">{{$row->category_name}}</td>
            <td width="60" align="center">{{$row->class_name}}</td>
            <td width="200" align="left">{{$row->item_desc}}, {{$row->specification}}</td>
            <td width="60" align="center">{{$row->custom_no}}</td>
            <td width="70" align="right">{{$row->qty}}</td>
            <td width="45" align="center">{{$row->uom_code}}</td>
            <td width="115" align="center">{{$row->sale_order_no}}</td>
            <td width="70" align="center">{{$row->department_name}}</td>
            <td width="60" align="center">{{$row->purpose_id}}</td>
            <td width="76" align="center">{{$row->last_qty }}<br/>{{$row->last_date }}</td>
            <td width="100" align="center">{{$row->remarks}}</td>

        </tr>
        <?php
        $i++;
    ?>
        @endforeach
        <tr>
            <td width="410" align="right">Total</td>
            <td width="70" align="right">{{$data['details']->sum('qty')}}</td>
            <td width="45" align="center"></td>
            <td width="115" align="center"></td>
            <td width="70" align="right">{{$data['details']->sum('store_amount')}}</td>
            <td width="60" align="center"></td>
            <td width="76" align="center"></td>
            <td width="100" align="center"></td>

        </tr>
    </tbody>
</table>


<br/>
<br/>
<br/>
<br/>
<br/>
<br/>
<table>
    
    <tr align="center">
        <td><strong>Requisition By</strong></td>
        <td><strong>1st Approved</strong></td>
        <td><strong>2nd Approved</strong></td>
        <td><strong>3rd Approved</strong></td>
        <td><strong>Final Approved</strong></td>
        
    </tr>
    <tr align="center">
        <td>&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;<strong>{{ $data['master']->user_name }},&nbsp;&nbsp;{{ $data['master']->contact }}<br/>
            &nbsp;&nbsp;&nbsp;{{ $data['master']->created_at }}</strong>
        </td>
        <td><strong>{{ $data['master']->first_approval_name  }}<br/>
            {{ $data['master']->first_approved_at}}
            </strong>
        </td>
        <td><strong>{{ $data['master']->second_approval_name }}<br/>
        {{  $data['master']->second_approved_at  }}</strong>
        </td>
        <td><strong>{{ $data['master']->third_approval_name  }}<br/>
            {{ $data['master']->third_approved_at}}
            </strong>
        </td>
        <td><strong>{{ $data['master']->final_approval_name}}<br/>
            {{ $data['master']->final_approved_at }}
            </strong>
        </td>
        
    </tr>
</table>
<h2 align="center">{{ $cgroup->company_name }}</h2>
<p align="center"><strong>{{ $cgroup->company_address }}</strong></p>
<p align="center">Lisence or Certificate Status</p>
<table border="1">
    <thead>
        <tr>
            <th rowspan="2">SL</th>
            <th rowspan="2">Document Name</th>
            <th align="center" colspan="3">A21</th>
            <th align="center" colspan="3">LAL</th>
            <th align="center" colspan="3">FFL</th>
            <th align="center" colspan="3">FDL</th>
            <th align="center" colspan="3">FPL</th>
            <th align="center" colspan="3">MIL</th>
        </tr>
        <tr>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Expire Date</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <?php
    $i=1;
    ?>
    <tbody>
        @foreach ($renewalitem as $key=>$item)
        <tr>
            <td>{{ $i }}</th>
            <td>{{ $item->renewal_item }}</td>
            
            <td align="center" colspan="3">A21</td>
            <td align="center" colspan="3">LAL</td>
            <td align="center" colspan="3">FFL</td>
            <td align="center" colspan="3">FDL</td>
            <td align="center" colspan="3">FPL</td>
            <td align="center" colspan="3">MIL</td>
        </tr>
    <?php
    $i++;
    ?>
        @endforeach
    </tbody>
</table>
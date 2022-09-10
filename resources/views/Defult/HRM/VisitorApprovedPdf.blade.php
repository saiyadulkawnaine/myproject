<h2 align="center">Lithe Group</h2>
<table>
    @foreach ($rows as $item)  
        <tr>
            <td>Visitor Name</td>
            <td>{{ $item->name }}</td>
        </tr>
        <tr>
            <td>Phone No</td>
            <td>{{ $item->contact_no }}</td>
        </tr>
        <tr>
            <td>Organization</td>
            <td>{{ $item->organization_dtl }}</td>
        </tr>
        <tr>
            <td>Arrival Date</td>
            <td>{{ $item->arrival_date }}</td>
        </tr>
        <tr>
            <td>Arrived at</td>
            <td>{{ $item->arrival_time }}</td>
        </tr>
        <tr>
            <td>To Whom</td>
            <td>{{ $item->user_name }}</td>
        </tr>
        <tr>
            <td>Purpose</td>
            <td>{{ $item->purpose }}</td>
        </tr>
        <tr>
            <td>Approving User</td>
            <td>{{ $item->approve_user_id }}</td>
        </tr>
    @endforeach
</table>
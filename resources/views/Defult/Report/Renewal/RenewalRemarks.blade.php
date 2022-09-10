<table cellspacing=2 cellpadding=2>
    <thead>
        <tr>
            <th>
               Document Renewal Comments
            </th>
        </tr>
    </thead>
    @foreach ($renewalentry as $data)

    <tr>
        <td>
            {{ $data->remarks }}
        </td>
    </tr>
    @endforeach
</table>
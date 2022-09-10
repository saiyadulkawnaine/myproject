

<table  border="1">
      <thead>
            <tr>
                  <th width="30" align="center">Sl</th>
                  <th width="50" align="center">Code</th>
                  <th width="50" align="center">Name</th>
                  <th width="50" align="center">Department</th>
                  <th width="50" align="center">Designation</th>
                  <th width="50" align="center">Company</th>
                  <th width="50" align="center">Date of Join</th>
                  <th width="50" align="center">Address</th>
                  <th width="50" align="center">Grade</th>
                  <th width="50" align="center">Email</th>
                  <th width="50" align="center">Contact</th>
                  <th width="50" align="center">Date of Birth</th>
                  <th width="50" align="center">Advanced Payable</th>
            </tr>
      </thead>
      <tr>
            <td width="30" align="center">1</td>
            <td width="50" align="center">{{ $employeelist['master']->code}}</td>
            <td width="50" align="left">{{ $employeelist['master']->name}}</td>
            <td width="50" align="left">{{ $employeelist['master']->department_id}}</td>
            <td width="50" align="left">{{ $employeelist['master']->designation_id}}</td>
            <td width="50" align="left">{{ $employeelist['master']->company_id}}</td>
            <td width="50" align="left">{{ $employeelist['master']->date_of_join}}</td>
            <td width="50" align="left">{{$employeelist['master']->address}}</td>
            <td width="50" align="left">{{$employeelist['master']->grade}}</td>
            <td width="50" align="left">{{ $employeelist['master']->email }}</td>
            <td width="50">{{ $employeelist['master']->contact}}</td>
            <td width="50">{{$employeelist['master']->date_of_birth}}</td>
            <td width="50">{{$employeelist['master']->is_advanced_applicable}}</td>
      </tr>
</table>


<h3 align="center">Recruitment Requisition of {{ $rows->designation_name }}</h3>
<h4 align="center">Department: {{ $rows->department_name }}, {{ $rows->location_name }}</h4>
<p></p>
<table cellpadding="2">
    <tr>
        <td width="320"><strong>Position Details</strong></td>
        <td width="20"></td>
        <td width="320"><strong>Employee Specification</strong></td>
    </tr>
    <tr>
        <td width="320"><table border="1" style="border-style: dotted" cellpadding="2">
            <tr>
                <td width="20" align="center">1</td>
                <td width="120">Required Position</td>
                <td width="180">{{ $rows->no_of_required_position }}</td>
            </tr>
            <tr>
                <td width="20" align="center">2</td>
                <td width="120">Vacancies Available</td>
                <td width="180">{{ $rows->vacancy_available }}</td>
            </tr>
            <tr>
                <td width="20" align="center">3</td>
                <td width="120">Budgeted Position</td>
                <td width="180">{{ $rows->no_of_post }}</td>
            </tr>
            <tr>
                <td width="20" align="center">4</td>
                <td width="120">Replacement Of</td>
                <td width="180">{{ $rows->replaced_employee }}</td>
            </tr>
            <tr>
                <td width="20" align="center">5</td>
                <td width="120">Expected DOJ</td>
                <td width="180">{{ $rows->date_of_join }}</td>
            </tr>
            <tr>
                <td width="20" align="center">6</td>
                <td width="120">Minimum Salary</td>
                <td width="180">{{ $rows->min_salary }}</td>
            </tr>
            <tr>
                <td width="20" align="center">7</td>
                <td width="120">Maximum Salary</td>
                <td width="180">{{ $rows->max_salary }}</td>
            </tr>
            <tr>
                <td width="20" align="center">8</td>
                <td width="120">Reporting Officer</td>
                <td width="180">{{ $rows->employee_name }}, {{ $rows->reporting_emp_designation }}</td>
            </tr>
            <tr>
                <td width="20" align="center">9</td>
                <td width="120">Need Assessment</td>
                <td width="180">{{ $rows->justification }}</td>
            </tr>
            <tr>
                <td width="20" align="center">10</td>
                <td width="120">Level</td>
                <td width="180">{{ $rows->designation_level_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">11</td>
                <td width="120">Grade</td>
                <td width="180">{{ $rows->grade }}</td>
            </tr>
            <tr>
                <td width="20" align="center">12</td>
                <td width="120">Division</td>
                <td width="180">{{ $rows->division_name }}</td>
            </tr>
            <tr>
                <td width="20" align="center">13</td>
                <td width="120">Section</td>
                <td width="180">{{ $rows->section_name }}</td>
            </tr>
            <tr>
                <td width="20" align="center">14</td>
                <td width="120">Subsection</td>
                <td width="180">{{ $rows->subsection_name }}</td>
            </tr>
            <tr>
                <td width="20" align="center">14</td>
                <td width="120">New Job Description</td>
                <td width="180"><?php $i="a"; ?>@foreach ($recruitreqjob as $job)
                    {{ $i++ }}) {{ $job->job_description }}<br>
                    @endforeach
                </td>
            </tr>
            </table>
        </td>
        <td width="20"></td>
        <td width="320"><table  border="1" style="border-style: dotted" cellpadding="2">
            <tr>
                <td width="20" align="center">1</td>
                <td width="140">Last Education</td>
                <td width="160">{{ $rows->last_education }}</td>
            </tr>
            <tr>
                <td width="20" align="center">2</td>
                <td width="140">Professional Education</td>
                <td width="160">{{ $rows->professional_education }}</td>
            </tr>
            <tr>
                <td width="20" align="center">3</td>
                <td width="140">Special Qualification</td>
                <td width="160">{{ $rows->special_qualificaiton }}</td>
            </tr>
            <tr>
                <td width="20" align="center">4</td>
                <td width="140">Experience</td>
                <td width="160">{{ $rows->experience }}</td>
            </tr>
            <tr>
                <td width="20" align="center">5</td>
                <td width="140">Age Limit</td>
                <td width="160">{{ $rows->age_limit }}</td>
            </tr>
            <tr>
                <td width="20" align="center">6</td>
                <td width="140">Room Required</td>
                <td width="160">{{ $rows->room_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">7</td>
                <td width="140">Desk Required</td>
                <td width="160">{{ $rows->desk_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">8</td>
                <td width="140">Intercom Required</td>
                <td width="160">{{ $rows->intercom_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">9</td>
                <td width="140">Computer Required</td>
                <td width="160">{{ $rows->computer_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">10</td>
                <td width="140">UPS Required</td>
                <td width="160">{{ $rows->ups_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">11</td>
                <td width="140">Printer Required</td>
                <td width="160">{{ $rows->printer_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">12</td>
                <td width="140">Cell Phone Required</td>
                <td width="160">{{ $rows->cell_phone_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">13</td>
                <td width="140">SIM Required</td>
                <td width="160">{{ $rows->sim_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">14</td>
                <td width="140">Network Required</td>
                <td width="160">{{ $rows->network_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">15</td>
                <td width="140">Transport Required</td>
                <td width="160">{{ $rows->transport_required_id }}</td>
            </tr>
            <tr>
                <td width="20" align="center">16</td>
                <td width="140">Other Items Required</td>
                <td width="160">{{ $rows->other_item_required }}</td>
            </tr>
            </table>
        </td>
    </tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p><strong>Comments of Approving Authority:</strong>
    <br>
<table border="1">
    <tr>
        <td><br><br><br><br><br><br><br><br><br></td>
    </tr>
</table></p>
<p></p>
<p></p>
<p></p>
<p></p>
<table width="680">
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center" style="font-stretch: ultra-expanded">@if (!$rows->approved_at)<strong>UNAPPROVED</strong>@endif</td>
    </tr>
    <tr>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center"></td>
        <td align="center" style="font-stretch: ultra-expanded"></td>
    </tr>
    <tr>
        <td align="center"><strong>Prepared By</strong><br>{{ $rows->user_name }}<br>{{ $rows->user_designation_name }}<br>{{ $rows->user_contact }}</td>
        <td align="center"><strong>Head of<br>Department</strong></td>
        <td align="center"><strong>Recommended By</strong></td>
        <td align="center"><strong>Approved By</strong></td>
    </tr>
</table>
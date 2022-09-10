<table border="0">
    <tr>
        <td width="640"><strong>{{ $rows->print_date }}</strong></td>
    </tr>
    <tr>
        <td width="640"></td>
    </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td width="640"><Strong>{{ $rows['name'] }}</Strong></td>
    </tr>
    <tr>
        <td width="320"><Strong>{{ $rows['address'] }},<br>{{ $rows['contact'] }}<br>{{ $rows['email'] }}</Strong></td>
        <td width="320"></td>
    </tr>
    <tr>
        <td width="640"></td>
    </tr>
    <tr>
        <td width="640" align="center">
            <strong>SUBJECT: LETTER OF APPOINTMENT</strong>
        </td>
    </tr>
    <tr>
        <td width="640"></td>
    </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td width="640"><Strong>Dear {{ $rows['name'] }},</Strong>
        </td>
    </tr>
    <tr>
        <td width="640" align="left">With reference to discussions with you and your willingness to join in our company, we are pleased to offer you appointment as <Strong>“{{ $rows['designation_name'] }}”</Strong> in Lithe Group. The terms and conditions of which shall be as follows
        </td>
    </tr>
    <tr>
        <td width="640"></td>
    </tr>
</table>
<table border="0" cellpadding="2" cellspacing="0">
    <tr>
        <td width="30" align="left">1.</td>
        <td width="610"><strong>Joining</strong>&nbsp;:&nbsp;We request you to join on <strong>{{ $rows['date_of_join'] }}</strong>. Your  joining in the company to be effective upon submission of release order from your last attended organization and joining report.</td>
    </tr>
    <tr>
        <td width="30" align="left">2.</td>
        <td width="610"><strong>Work Station</strong>&nbsp;:&nbsp;{{ $rows['location_address'] }}, {{ $rows['location_name'] }}. <strong>Department – </strong>{{ $rows['department_name'] }}</td>
    </tr>
    <tr>
        <td width="30" align="left">3.</td>
        <td width="610"><strong>Salary & Benefits</strong>&nbsp;:&nbsp;You will be entitled for a consolidated salary of BDT<strong>{{ $rows['salary'] }} ({{ $rows['inword'] }} ) only per month</strong></td>
    </tr>
    <tr>
        <td width="30" align="left">4.</td>
        <td width="610"><strong>Income Tax</strong>&nbsp;:&nbsp;Employer will deduct tax on salary income at the time of salary disbursement and pay to Govt. authority in favor of you. But you are responsible to submit return and complying all the subsequent queries if any. </td>
    </tr>
    <tr>
        <td width="30" align="left">5.</td>
        <td width="610"><strong>Secrecy about Salary</strong>&nbsp;:&nbsp;Please note that individual salary should be treated as strictly confidential. Disclosure of salary or other benefits will result in termination of service by the employer</td>
    </tr>
    <tr>
        <td width="30" align="left">6.</td>
        <td width="610"><strong>Medical Benefits</strong>&nbsp;:&nbsp;You will be entitled to medical benefits in accordance with the medical rules of the company.</td>
    </tr>
    <tr>
        <td width="30" align="left">7.</td>
        <td width="610"><strong>Transport</strong>&nbsp;:&nbsp;{{ $rows['transport'] }}</td>
    </tr>
    <tr>
        <td width="30" align="left">8.</td>
        <td width="610"><strong>Group Insurance</strong>&nbsp;:&nbsp;{{ $rows['group_insurance'] }}</td>
    </tr>
    <tr>
        <td width="30" align="left">9.</td>
        <td width="610"><strong>Utility Bills</strong>&nbsp;:&nbsp;{{ $rows['utility_bill'] }}</td>
    </tr>
    <tr>
        <td width="30" align="left">10.</td>
        <td width="610"><strong>Allowance</strong>&nbsp;:&nbsp;{{ $rows['allowance'] }}</td>
    </tr>
    <tr>
        <td width="30" align="left">11.</td>
        <td width="610"><strong>Leave</strong>&nbsp;:&nbsp;After confirmation of your employment with the company, you will be entitled to avail leave annually as follows:
            <ol>
                @foreach ($leave as $item)
                    <li>{{ $item['leave_description'] }}</li>
                @endforeach
            </ol>
        </td>
    </tr>
    <tr>
        <td width="30" align="left">12.</td>
        <td width="610"><strong>Probation Period</strong>&nbsp;:&nbsp;<strong>{{ $rows['probation_days'] }}</strong> days will be as probation period from the date of joining. Based on your quality performance and depending on verification of your references or any other inquiries which the management may decide to issue job confirmation letter or extend probation period at the discretion of the company. In any circumstances if job seperation is taken place by employee or employer, employee will not be entitled to get any financial benefits other than salary as per attendance recorded.</td>
    </tr>
    <tr>
        <td width="30" align="left">13.</td>
        <td width="610"><strong>Confirmation of Employment</strong>&nbsp;:&nbsp;After expiry of initial or extended probationary period, your service can be confirmed by the company at its discretion in the manner as stated above.</td>
    </tr>
    <tr>
        <td width="30" align="left">14.</td>
        <td width="610"><strong>Termination of Service</strong>&nbsp;:&nbsp;In case of breach of any terms and conditions of this appointment letter or guilty of misconduct or illegal activity or prejudicial activity to the interest of the Company, the employer company can terminate your service by giving 1 month prior notice in writing to you. You can also terminate this service by serving at least 1 month prior notice in writing explaining the reason thereof to the appropriate authority of the company  or by surrendering 1 salary in lieu thereof. </td>
    </tr>
    <tr>
        <td width="30" align="left">15.</td>
        <td width="610"><strong>Transfer</strong>&nbsp;:&nbsp;Company can transfer you in any work station for greater interest.</td>
    </tr>
    <tr>
        <td width="30" align="left">16.</td>
        <td width="610"><strong>Interest of the Company</strong>&nbsp;:&nbsp;You must use your best endeavors to promote the interest of the company and to carry out all reasonable orders and instructions made by or on behalf of the company.</td>
    </tr>
    <tr>
        <td width="30" align="left">17.</td>
        <td width="610"><strong>Restrictions</strong>&nbsp;:&nbsp;Below mentioned activities willbe considered as misconduct and legal action will be taken if proved:
            <ol type="1">
                <li>&nbsp;Willful or disobedience, whether alone or  in combination with others to any lawful or reasonable order of a superior.</li>
                <li>&nbsp;Theft, fraud or dishonesty in connection with business or property employer.</li>
                <li>&nbsp;Talking of giving bribe or connecting with his or any other worker's employment under the employer.</li>
                <li>&nbsp;Habitual negligence in work.</li>
                <li>&nbsp;Altering, forging, wrongfully changing, damaging or causing loss of employers official records.<br>Notwithstanding anything contained in these rules, any worker engaged in the factory or firm or any person with administrative and management responsibility will maintain the confidentiality of the business strategy of the firm in case of performing the duties or changing the job. </li>
            </ol>
        </td>
    </tr>
    <tr>
        <td width="30" align="left">18.</td>
        <td width="610"><strong>Confidentiality</strong>&nbsp;:&nbsp;Please note that individual salary should be treated as strictly confidential. Disclosure of salary or other benefits will result in disciplinary action.</td>
    </tr>
    <tr>
        <td width="30" align="left">19.</td>
        <td width="610"><strong>Company Properties</strong>&nbsp;:&nbsp;Any property of the company, including memorandum, notes, records, sketches, plans, seal, stamps or other documents which may be in your possession or under control at the time of termination of your employment shall be delivered by you to the company or otherwise as the company may direct. You will not be entitled to the copyright in any documents and will not retain copies of any of them.</td>
    </tr>
    <tr>
        <td width="30" align="left">20.</td>
        <td width="610"><strong>Obligation to keep employer informed</strong>&nbsp;:&nbsp;During the period of your employment, you will promptly disclose all necessary information and propitiatory rights to the employer company fully and in writing and will hold such proprietary rights in trust for the sole right and benefit of the employer company. </td>
    </tr>
    <tr>
        <td width="30" align="left">21.</td>
        <td width="610"><strong>Office Place, Hour and Duty</strong>&nbsp;:&nbsp;Office place will include such places as determined by the employer time to time. Office hour will be guided by the Company Rules. Office duty will include all duties as provided under this letter, annexed letter of undertaking, company rules and directives/instructions given by the authorities time to time. </td>
    </tr>
    <tr>
        <td width="30" align="left">22.</td>
        <td width="610"><strong>Keep the Employer Informing</strong>&nbsp;:&nbsp;Complain, objection, dissatisfaction, challenge or dispute (if any) raised on the part of the employee should be raised first before the employer authority in writing within 30 (thirty) days of facing such matter of complain, objection, dissatisfaction, challenge or dispute.</td>
    </tr>
    <tr>
        <td width="30" align="left">23.</td>
        <td width="610"><strong>ERP Operations</strong>&nbsp;:&nbsp;You must do all jobs through ERP as developed.</td>
    </tr>
    <tr>
        <td width="30" align="left">24.</td>
        <td width="610"><strong>Job descriptions</strong> :
            <ol>
                @foreach ($job as $jobs)
                    <li>{{ $jobs['job_description'] }}</li>
                @endforeach
            </ol>
        </td>
    </tr>
    <tr>
        <td width="30" align="left">25.</td>
        <td width="610"><strong>Reporting</strong>&nbsp;:&nbsp;You will be directly reporting to the <strong>{{ $rows['report_name'] }}, {{ $rows['report_designation'] }}</strong> of the company</td>
    </tr>
    <tr>
        <td width="30" align="left">26.</td>
        <td width="610"><strong>Required Documents/Papers Need to Submit</strong>&nbsp;:&nbsp;
        <ol type="a">
            <li>4 copies of passport size photograph</li>
            <li>Receipt of our Appointment Letter (Copy) duly signed by you</li>
            <li>All educational Certificates </li>
            <li>All experience Certificates</li>
            <li>Release / Clearance Letter (from last employer)</li>
            <li>National ID Card</li>
            <li>All training related Certificates (if any)</li>
            <li>Any kind of professional Certificates (if any)</li>
            <li>Certificates of Extra Curricular Activities (if any)</li>
            <li>Any other certificate(s) applicable for you.</li>
            <li>Reference (both from previous employer & personal) </li>
            <li>Visiting Card (if any which is used in Previous Company)</li>
        </ol>
        </td>
    </tr>
    <tr><td></td><td></td></tr>
    <tr>
        <td width="640" colspan="2"><p>Notwithstanding any other provisions of this letter of appointment, your appointment will automatically be terminated if the Company is abolished.</p>
        <p>All other terms and conditions of your appointment will be governed by the standing rules of the company. Please sign this letter as a token of your acceptance and return a copy to us for our records.</p>
        <p>We would like to welcome you to our organization and we look forward to a long and fruitful association between you and Organization.</p>
        </td>
    </tr>
</table>
<table cellpadding="2" cellspacing="0">
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td>Yours sincerely,</td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td>-------------------------------</td>
    </tr>
    <tr>
        <td width="640"><strong>{{ $rows['signatory_name'] }}</strong></td>
    </tr>
    <tr>
        <td width="640">{{ $rows['signatory_designation'] }} </td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td width="640"><strong>Acceptance:</strong></td></tr>
    <tr><td></td></tr>
    <tr>
        <td width="640">I hereby accept my appointment as {{ $rows['designation_name'] }} under the terms and conditions of employment set forth in this offer letter.</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td width="320"><strong><span style="text-decoration: overline">({{ $rows['name'] }})</span></strong></td>
        <td width="320"><strong><span style="text-decoration: overline">Date:
        </span></strong></td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td width="640"></td>
    </tr>
    <tr>
        <td width="320"></td>
        <td width="320"></td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td width="30">CC</td>
        <td width="290">&nbsp;: Head of Finance & Accounts<br>&nbsp;&nbsp;&nbsp; Personal File</td>
        <td width="160"></td>
        <td width="160"></td>
    </tr>
</table>
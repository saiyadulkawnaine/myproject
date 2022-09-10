
<table>
    <tr>
    <td colspan="10" align="center"><strong>Statement of Financial Position as at  {{ $asat }}</strong></td>
    </tr>
</table>

<table  border="0" style="margin: 0 auto;">
    <thead>
        <tr>
            <th width="300" align="center"><strong>ASSETS</strong></th>
            <th width="112" align="center">Current Year</th>
            <th width="114" align="center">Last to Year</th>
        </tr>
    </thead>


    <tr><td width="638"><strong>{{ $accchartgroup[10] }}</strong></td></tr>
    <?php
    $currentTotal10=0;
    $lastTotal10=0;

    ?>
    @if(isset($data[10]))
    @foreach($data[10] as $row)
    <tr>
        <td width="300" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
    </tr>
    <?php

    $currentTotal10+=$row['current_amount'];
    $lastTotal10+=$row['last_amount'];
    ?>
    @endforeach
    @endif

    <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal10,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal10,2) }}</strong></td>
    </tr>

    <tr><td width="638"><strong> {{ $accchartgroup[13] }}</strong></td></tr>
    <?php

    $currentTotal13=0;
    $lastTotal13=0;
    ?>
    @if(isset($data[13]))

    @foreach($data[13] as $row)
    <tr>
        <td width="300" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
    </tr>
    <?php
    $currentTotal13+=$row['current_amount'];
    $lastTotal13+=$row['last_amount'];
    ?>
    @endforeach
    @endif
    <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal13,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal13,2) }}</strong></td>
    </tr>

    <?php
    $currentassetTotal=$currentTotal10+$currentTotal13;
    $lastassetTotal=$lastTotal10+$lastTotal13;
    ?>
    <tr>
        <td width="300"><strong> Total</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentassetTotal,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastassetTotal,2) }}</strong></td>
    </tr>

        
    <tr>
    <td width="300" align="center"><strong>EQUITY & LIABILITIES</strong></td>
    <td width="112" class="text-right"></td>
    <td width="114" class="text-right"></td>
    </tr>
    <tr><td width="638"><strong> {{ $accchartgroup[1] }}</strong></td></tr>
    <?php
    $currentTotal1=0;
    $lastTotal1=0;
    ?>
    @if(isset($data[1]))

    @foreach($data[1] as $row)
    <tr>
        <td width="300" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
    </tr>
    <?php
    $currentTotal1+=$row['current_amount'];
    $lastTotal1+=$row['last_amount'];
    ?>
    @endforeach
    @endif
    <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal1,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal1,2) }}</strong></td>
    </tr>
    <tr><td width="638"><strong> {{ $accchartgroup[4] }}</strong></td></tr>
    <?php
    $currentTotal4=0;
    $lastTotal4=0;
    ?>
    @if(isset($data[4]))

    @foreach($data[4] as $row)
    <tr>
        <td width="300" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
    </tr>
    <?php
    $currentTotal4+=$row['current_amount'];
    $lastTotal4+=$row['last_amount'];
    ?>
    @endforeach
    @endif
    <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal4,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal4,2) }}</strong></td>
    </tr>
    <tr><td width="638"><strong> {{ $accchartgroup[7] }}</strong></td></tr>
    <?php
    $currentTotal7=0;
    $lastTotal7=0;
    ?>
    @if(isset($data[7]))

    @foreach($data[7] as $row)
    <tr>
        <td width="300" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
    </tr>
    <?php
    $currentTotal7+=$row['current_amount'];
    $lastTotal7+=$row['last_amount'];
    ?>
    @endforeach
    @endif
    <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal7,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal7,2) }}</strong></td>
    </tr>

    <?php
    $currentlibalitiesTotal=$currentTotal1+$currentTotal4+$currentTotal7;
    $lastlibalitiesTotal=$lastTotal1+$lastTotal4+$lastTotal7;
    ?>
    <tr>
        <td width="300"><strong>Total </strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentlibalitiesTotal,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastlibalitiesTotal,2) }}</strong></td>
    </tr>
    
        
</table>

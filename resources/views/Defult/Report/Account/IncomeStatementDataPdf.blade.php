
<table>
<tr>
<td colspan="10" align="center"><strong>Income  Statement for ( {{ $currentPeriod }} )</strong></td>
</tr>
</table>
<table  border="0" style="margin: 0 auto;">
     
        <thead>
        <tr>
        <th width="300" class="text-center"></th>
        <th width="112" class="text-center">{{ $lastPeriod }}</th>
        <th width="112" class="text-center">{{ $currentPeriod }}</th>
        <th width="114" class="text-center">{{$yearUpto}}</th>
        </tr>
        <tr>
        <th width="300" class="text-center">Particulars</th>
        <th width="112" class="text-center">Last Period</th>
        <th width="112" class="text-center">Current Period</th>
        <th width="114" class="text-center">Year to Date</th>
        </tr>
        </thead>
        <tr><td width="638"></td></tr>
        
        <tr><td width="638"><strong>A. {{ $accchartgroup[16] }}</strong></td></tr>
        <?php
        $lastTotal16=0;
        $currentTotal16=0;
        $yearTotal16=0;
        ?>
        @if(isset($data[16]))
        @foreach($data[16] as $row)
        <tr>
        <td width="300">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
        <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
        <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
        </tr>
        <?php
        $lastTotal16+=$row['last_amount'];
        $currentTotal16+=$row['current_amount'];
        $yearTotal16+=$row['year_amount'];
        ?>
        @endforeach
        @endif
        
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal16,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal16,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal16,2) }}</strong></td>
        </tr>
       
        
        <tr><td width="638"><strong>B. {{ $accchartgroup[19] }}</strong></td></tr>
        <?php
        
        $currentTotal19=0;
        $lastTotal19=0;
        $yearTotal19=0;
        ?>
        @if(isset($data[19]))
            
            @foreach($data[19] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal19+=$row['current_amount'];
            $lastTotal19+=$row['last_amount'];
            $yearTotal19+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal19,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal19,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal19,2) }}</strong></td>
        </tr>
       
        <?php
        $currentGrossprofit=$currentTotal16 - $currentTotal19;
        $lastGrossprofit=$lastTotal16 - $lastTotal19;
        $yearGrossprofit=$yearTotal16 - $yearTotal19
        ?>  

        <tr>
        <td width="300"><strong>C. GROSS PROFIT (A-B)</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastGrossprofit,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentGrossprofit,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearGrossprofit,2) }}</strong></td>
        </tr>

        <tr><td width="638"><strong>D. {{ $accchartgroup[22] }}</strong></td></tr>
        <?php
        $currentTotal22=0;
         $lastTotal22=0;
        $yearTotal22=0;
        ?>
        @if(isset($data[22]))
            
            @foreach($data[22] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal22+=$row['current_amount'];
            $lastTotal22+=$row['last_amount'];
            $yearTotal22+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal22,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal22,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal22,2) }}</strong></td>
        </tr>
        
        <?php
        $currentOperatingprofit=$currentGrossprofit-$currentTotal22;
        $lastOperatingprofit=$lastGrossprofit-$lastTotal22;
        $yearOperatingprofit=$yearGrossprofit-$yearTotal22;
        ?>
        <tr>
        <td width="300"><strong>E. OPERATING PROFIT (C-D)</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastOperatingprofit,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentOperatingprofit,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearOperatingprofit,2) }}</strong></td>
        </tr>


        <tr><td width="638"><strong>F. {{ $accchartgroup[24] }}</strong></td></tr>
        <?php
        $currentTotal24=0;
        $lastTotal24=0;
        $yearTotal24=0;
        ?>
        @if(isset($data[24]))
            
            @foreach($data[24] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal24+=$row['current_amount'];
            $lastTotal24+=$row['last_amount'];
            $yearTotal24+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal24,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal24,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal24,2) }}</strong></td>
        </tr>

        <tr><td width="638"><strong>G. {{ $accchartgroup[25] }}</strong></td></tr>
        <?php
        $currentTotal25=0;
        $lastTotal25=0;
        $yearTotal25=0;
        ?>
        @if(isset($data[25]))
            
            @foreach($data[25] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal25+=$row['current_amount'];
            $lastTotal25+=$row['last_amount'];
            $yearTotal25+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal25,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal25,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal25,2) }}</strong></td>
        </tr>


        <?php
        $profitBfrNonOperatingExpenses=($currentOperatingprofit-$currentTotal24)+($currentTotal25);
        $lastprofitBfrNonOperatingExpenses=($lastOperatingprofit-$lastTotal24)+($lastTotal25);
        $yearprofitBfrNonOperatingExpenses=($yearOperatingprofit-$yearTotal24)+($yearTotal25);
        ?>
        <tr>
        <td width="300"><strong>H. PROFIT BEFORE NON-OPERATING EXPENSES (E-F+G)</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastprofitBfrNonOperatingExpenses,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($profitBfrNonOperatingExpenses,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearprofitBfrNonOperatingExpenses,2) }}</strong></td>
        </tr>


        <tr><td width="638"><strong>I. {{ $accchartgroup[28] }}</strong></td></tr>
        <?php
        $currentTotal28=0;
        $lastTotal28=0;
        $yearTotal28=0;
        ?>
        @if(isset($data[28]))
            
            @foreach($data[28] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal28+=$row['current_amount'];
            $lastTotal28+=$row['last_amount'];
            $yearTotal28+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal28,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal28,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal28,2) }}</strong></td>
        </tr>


        <tr><td width="638"><strong>J. {{ $accchartgroup[50] }}</strong></td></tr>
        <?php
        $currentTotal50=0;
        $lastTotal50=0;
        $yearTotal50=0;
        ?>
        @if(isset($data[50]))
            
            @foreach($data[50] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal50+=$row['current_amount'];
            $lastTotal50+=$row['last_amount'];
            $yearTotal50+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal50,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal50,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal50,2) }}</strong></td>
        </tr>


        <?php
        $profitBfrTax=($profitBfrNonOperatingExpenses-$currentTotal28-$currentTotal50);
        $lastprofitBfrTax=($lastprofitBfrNonOperatingExpenses-$lastTotal28-$lastTotal50);
        $yearprofitBfrTax=($yearprofitBfrNonOperatingExpenses-$yearTotal28-$yearTotal50);
        ?>
        <tr>
        <td width="300"><strong>K. PROFIT BEFORE TAX (H-I-J)</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastprofitBfrTax,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($profitBfrTax,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearprofitBfrTax,2) }}</strong></td>
        </tr>


        <tr><td width="638"><strong>L. {{ $accchartgroup[55] }}</strong></td></tr>
        <?php
        $currentTotal55=0;
        $lastTotal55=0;
        $yearTotal55=0;
        ?>
        @if(isset($data[55]))
            
            @foreach($data[55] as $row)
            <tr>
            <td width="300" style="padding-left: 30px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $row['particulars']}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['last_amount'],2)}}</td>
            <td width="112" align="right">{{ print_with_bracket($row['current_amount'],2)}}</td>
            <td width="114" align="right">{{ print_with_bracket($row['year_amount'],2)}}</td>
            </tr>
            <?php
            $currentTotal55+=$row['current_amount'];
            $lastTotal55+=$row['last_amount'];
            $yearTotal55+=$row['year_amount'];
            ?>
            @endforeach
        @endif
        <tr>
        <td width="300"></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($lastTotal55,2) }}</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($currentTotal55,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($yearTotal55,2) }}</strong></td>
        </tr>


        <?php
        $Netprofit=($profitBfrTax-$currentTotal55);
        $lastNetprofit=($lastprofitBfrTax-$lastTotal55);
        $yearNetprofit=($yearprofitBfrTax-$yearTotal55);
        ?>
        <tr>
        <td width="300"><strong>M. NET PROFIT / LOSS (K-L)</strong></td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">{{ print_with_bracket($lastNetprofit,2) }}</td>
        <td width="112" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000"><strong>{{ print_with_bracket($Netprofit,2) }}</strong></td>
        <td width="114" align="right" style="border-bottom:1px solid #000; border-top:1px solid #000">{{ print_with_bracket($yearNetprofit,2) }}</td>
        </tr>
        


</table>

<style>
    table {
        margin: 0 auto;
    }
    thead,  th {
        background-color: #ccc;
    }
    p {
        margin: 0 auto;
    }
</style>
@if($is_html)
<table>
    <tr>
        <td width="638" align="center"><img width="300" height="100" src="images/logo/{{ $company->logo }}"/></td>
    </tr>
    <tr><td width="638" align="center">{{$company->address}}</td></tr>
    <tr>
        <td width="638" align="center" style="font-size: 20px"><strong><u>Forcasting ID : {{ $datas['master']->id }} </u></strong></td>
    </tr>
</table>
@endif
<h3 align="center">Embelishment Price Quotation</h3>
<table cellpadding="2" cellspacing="0">
    <tr><td width="1000px">To</td></tr>
    <tr><td width="1000px">{{ $datas['master']->buyer_name }}</td></tr>
    <tr><td width="1000px">{{ $datas['master']->buyer_address }}</td></tr>
</table>
<p></p>
<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <td width="20px" align="center"><strong>SL</strong></td>
        <td width="40px" align="center"><strong>Color Range</strong></td>
        <td width="40px" align="center"><strong>Print Type</strong></td>
        <td width="30px" align="center"><strong>No of Color</strong></td>
        <td width="30px" align="center"><strong>No of Color</strong></td>
        <td width="30px" align="center"><strong>Color %</strong></td>
        <td width="30px" align="center"><strong>Color %</strong></td>
        <td width="30px" align="center"><strong>Exch Rate</strong></td>
        <td width="30px" align="center"><strong>Fab.<br> Qty</strong></td>
        <td width="40px" align="center"><strong>Dyes<br> cost</strong></td>
        <td width="40px" align="center"><strong>Chem.<br> Cost</strong></td>
        <td width="40px" align="center"><strong>Add. Pros. Cost</strong></td>
        <td width="30px" align="center"><strong>OH</strong></td>
        <td width="40px" align="center"><strong>Total Cost</strong></td>
        <td width="40px" align="center"><strong>Cost/Kg -TK</strong></td>
        <td width="40px" align="center"><strong>Quoted Price -TK</strong></td>
        <td width="40px" align="center"><strong>Profit -TK</strong></td>
        <td width="40px" align="center"><strong>Profit %</strong></td>
        <td width="40px" align="center"><strong>Cost/Kg {{ $datas['master']->currency_code }}</strong></td>
        <td width="40px" align="center"><strong>Price {{ $datas['master']->currency_code }}</strong></td>
        <td width="40px" align="center"><strong>Profit {{ $datas['master']->currency_code }}</strong></td>
        <td width="110px" align="center"><strong>Comments</strong></td>
        <td width="110px" align="center"><strong>Remarks</strong></td>
    </tr>
    <?php
        $i=1;
        $tFabricWgt=0;
        $tDyeCost=0;
        $tChemCost=0;
        $tSpecialChemCost=0;
        $tOverhead=0;
        $tTotalCost=0;
        $tCostPerKgBdt=0;
        $tProfitAmountTk=0;
        $tQuotedPriceBdt=0;
        $tCostPerKg=0;
        $tProfitPer=0;
        $tQuotedPrice=0;
        $tProfitAmount=0;
        

    ?>
    @foreach ($datas['soaopmktcosquotaionpricedetails'] as $data)
    <?php
        if ($datas['master']->asking_profit>$data->profit_per) {
            $color= "red";
            $comments="Quoted Profit is less than asking profit ".$datas['master']->asking_profit."%";
        }else {
            $color= "black";
            $comments="Quoted Profit is greater than asking profit ".$datas['master']->asking_profit."%";
        }
    ?>
    <tbody>
        <tr>
            <td width="20px" align="center">{{ $i++ }}</td>
            <td width="40px" align="center">{{ $data->colorrange_name }}</td>
            <td width="40px" align="center">{{ $data->print_type }}</td>
            <td width="30px" align="center">{{ $data->no_of_color_from }}</td>
            <td width="30px" align="center">{{ $data->no_of_color_to }}</td>
            <td width="30px" align="center">{{ $data->color_ratio_from }}</td>
            <td width="30px" align="center">{{ $data->color_ratio_to }}</td>
            <td width="30px" align="right">{{ $data->exch_rate }}</td>
            <td width="30px" align="right">{{ number_format($data->fabric_wgt,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->dyes_cost,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->chemical_cost,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->special_chemical_cost,2) }}</td>
            <td width="30px" align="right">{{ number_format($data->overhead_amount,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->total_cost,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->cost_per_kg_bdt,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->quoted_price_bdt,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->profit_amount_bdt,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->profit_per,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->cost_per_kg,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->quoted_price,2) }}</td>
            <td width="40px" align="right">{{ number_format($data->profit_amount,2) }}</td>
            <td width="110px" align="left" style="color:{{$color}}">{{ $comments }}</td>
            <td width="110px" align="left">{{ $data->remarks }}</td>
        </tr> 
    </tbody> 
    <?php
        $tFabricWgt += $data->fabric_wgt;
        $tDyeCost += $data->dyes_cost;
        $tChemCost += $data->chem_cost;
        $tSpecialChemCost += $data->special_chem_cost;
        $tOverhead += $data->overhead_amount;
        $tTotalCost += $data->total_cost;
        $tCostPerKgBdt += $data->cost_per_kg_bdt;
        $tProfitAmountTk += $data->profit_amount_bdt;
        $tQuotedPriceBdt += $data->quoted_price_bdt;
        $tCostPerKg += $data->cost_per_kg;
        $tQuotedPrice += $data->quoted_price;
        $tProfitAmount += $data->profit_amount;
        if ($tQuotedPriceBdt) {
            $tProfitPer=($tProfitAmountTk/$tQuotedPriceBdt)*100;
        }
        
    ?>
    @endforeach
    <tfoot>
        <tr>
            <td width="20px" align="center"></td>
            <td width="40px" align="center"></td>
            <td width="40px" align="center"></td>
            <td width="60px" align="left"><strong>TOTAL</strong></td>
            <td width="30px" align="center"></td>
            <td width="30px" align="center"></td>
            <td width="30px" align="right"></td>
            <td width="30px" align="right">{{ number_format($tFabricWgt,2) }}</td>
            <td width="40px" align="right">{{ number_format($tDyeCost,2) }}</td>
            <td width="40px" align="right">{{ number_format($tChemCost,2) }}</td>
            <td width="40px" align="right">{{ number_format($tSpecialChemCost,2) }}</td>
            <td width="30px" align="right">{{ number_format($tOverhead,2) }}</td>
            <td width="40px" align="right">{{ number_format($tTotalCost,2) }}</td>
            <td width="40px" align="right">{{ number_format($tCostPerKgBdt,2) }}</td>
            <td width="40px" align="right">{{ number_format($tProfitAmountTk,2) }}</td>
            <td width="40px" align="right">{{ number_format($tQuotedPriceBdt,2) }}</td>
            <td width="40px" align="right">{{ number_format($tProfitPer,2) }}</td>
            <td width="40px" align="right">{{ number_format($tCostPerKg,2) }}</td>
            <td width="40px" align="right">{{ number_format($tQuotedPrice,2) }}</td>
            <td width="40px" align="right">{{ number_format($tProfitAmount,2) }}</td>
            <td width="110px" align="left"></td>
            <td width="110px" align="left"></td>
        </tr> 
    </tfoot>
</table>
<p></p>
<p></p>
<table cellpadding="2" cellspacing="0">
    <tr>
        <td width="1000" colspan="2"><strong>Terms & Conditions:</strong></td>
    </tr>
    <?php
        $i=1;
    ?>
    <tbody>
        @foreach($datas['termscondition'] as $terms)
            <tr>
                <td width="38">
                    {{$i}}.
                </td>
                <td width="958">
                    <strong>{{$terms->term}}</strong>
                </td>
            </tr>
            <?php
            $i++;
            ?>
        @endforeach
    </tbody>
</table>
<p></p>
<br/>
<br/>
<table border="1" cellspacing="0" cellpadding="2">
    <tr>
        <th width="30">#</th>
        <th width="500">Comments</th>
        <th width="150">Comments By</th>
        <th width="200">Comments AT</th>
    </tr>  
    <tbody>
        <?php
        $i=1;
        ?>
        @foreach($datas['comment_histories'] as $comments)
        <tr>
            <td width="30">{{$i}}</td>
            <td width="500">{{$comments->comments}}</td>
            <td width="150">{{$comments->user_name}}</td>
            <td width="200">{{$comments->comments_at}}</td>
        </tr>
        <?php
        $i++;
        ?>
        @endforeach
    </tbody>
</table>
<br/>
<br/>
@if($is_html)
<table>
    <tr>
        <td width="1000" align="center">
            <form id="soaopmktcostqpriceapprovalreturncommentFrm">
                <textarea cols="3" rows="5" id="mkt_cost_aproval_return_comments" name="mkt_cost_aproval_return_comments"></textarea>
            </form>
        </td>
    </tr>
</table>
<br/>
<br/>
<table>
    <tr>
        <td width="1000" align="center">
        @permission('approvefirst.soaopmktcostqprices')
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.approveSingle('{{$approval_type}}',{{$datas['master']->id}})">Approve
            </a>
            <a href="javascript:void(0)" class="easyui-linkbutton  c2" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px; margin-right: 30px" iconCls="icon-save" plain="true" id="save" onClick="MsSoDyeingMktCostQpriceApproval.appReturn('{{$approval_type}}',{{$datas['master']->id}})">Return
            @endpermission
            <a href="javascript:void(0)" class="easyui-linkbutton  c4" style="height:25px; font-size: 24px; border-radius:1px; padding: 5px 10px" iconCls="icon-save" plain="true" id="save" onClick="closeWindow()">Close
            </a>
        </td>
    </tr>
    <tr><td width="1000" align="center"></td></tr>
</table>
<br/>
<br/>
@endif
<p></p>
@if(!$is_html)
<p></p>
<p></p>
<table>
    <tr>
        <td></td>
        <td>@if ($datas['master']->first_approval_signature)<img src="{{ $datas['master']->first_approval_signature }}" width="100", height="40"/>
            @endif</td>
        <td>@if ($datas['master']->second_approval_signature)<img src="{{ $datas['master']->second_approval_signature }}" width="100", height="40"/>
            @endif</td>
        
        <td align="center">@if ($datas['master']->final_approval_signature)<img src="{{ $datas['master']->final_approval_signature }}" width="100", height="40"/>@endif
            @if(!$datas['master']->final_approval_name)<strong style="font-stretch: ultra-expanded" >UNAPPROVED</strong>@endif</td>
    </tr>
    <tr>
        <td><strong>Prepared By</strong></td>
        <td><strong>Director</strong></td>
        <td><strong>Deputy Managing Director</strong></td>
        <td align="center"><strong>Managing Director</strong></td>
    </tr>
    <tr>
        <td>{{ $datas['master']->user_name }}<br/>{{ date('d-M-Y',strtotime($datas['master']->created_at)) }}</td>
        <td><strong>{{ $datas['master']->first_approval_emp_name }}<br/>
            {{ $datas['master']->first_approval_emp_contact }}<br/>
            {{ $datas['master']->first_approval_emp_designation }}<br/>
            {{ $datas['master']->first_approved_at }}
            </strong></td>
        <td><strong>{{ $datas['master']->second_approval_name }}<br/>
            {{ $datas['master']->second_approved_at }}
            </strong></td>
        <td align="center"><strong>{{ $datas['master']->final_approval_name }}<br/>
            {{ $datas['master']->final_approved_at }}
            </strong></td>
    </tr>
</table>
@endif
@if($is_html)
<script>
    function closeWindow(){
        $('#soaopMktCostQpriceApprovalDetailContainer').html('');
        $('#soaopMktCostQpriceApprovalDetailWindow').window('close');
    }
</script>
@endif
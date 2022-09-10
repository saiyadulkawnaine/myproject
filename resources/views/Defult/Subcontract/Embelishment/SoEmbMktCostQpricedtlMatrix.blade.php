<?php
  $i=1;
?>
@if($rows->isNotEmpty())
<p>New Quoted Price Details</p>
<table border="1">
  <tr align="center">
    <th width="15px" class="text-center">SL</th>
    <th width="70px" class="text-center">Color Range</th>
    <th width="40px" class="text-center">Color %</th>
    <th width="40px" class="text-center">Color %</th>
    <th width="40px" class="text-center">Exch Rate</th>
    <th width="100px" class="text-center">Fabric Qty</th>
    <th width="100px" class="text-center">Dyes cost</th>
    <th width="100px" class="text-center">Chemical Cost</th>
    <th width="100px" class="text-center">Add. Pros. Chem Cost</th>
    <th width="100px" class="text-center">Over<br/>heads</th>
    <th width="100px" class="text-center">Total Cost</th>
    <th width="100px" class="text-center">Cost/Kg -TK</th>
    <th width="100px" class="text-center">Quoted Price -TK</th>
    <th width="100px" class="text-center">Profit -TK</th>
    <th width="100px" class="text-center">Profit %</th>
    <th width="100px" class="text-center">Cost/Kg Usd</th>
    <th width="100px" class="text-center">Price Usd</th>
    <th width="100px" class="text-center">Profit Usd</th>
    <th width="100px" class="text-center">Remarks</th>
  </tr>
  <tbody>
  @foreach($rows as $data)
  <tr align="center">
    <td width="15px" class="text-center">{{ $i }}</td>
    <td width="70px"  align="left">
        {{ $data->colorrange_name }}
        <input type="hidden" name="so_aop_mkt_cost_param_id[{{ $i }}]" id="so_aop_mkt_cost_param_id{{ $i }}" value="{{ $data->so_aop_mkt_cost_param_id }}" />
        <input type="hidden" name="so_aop_mkt_cost_qprice_id[{{ $i }}]" id="so_aop_mkt_cost_qprice_id{{ $i }}" value="{{ $data->so_aop_mkt_cost_qprice_id }}" />
    </td>
    <td width="40px" align="right">{{ $data->color_ratio_from }}</td>
    <td width="40px" align="right">{{ $data->color_ratio_to }}</td>
    <td width="40px" align="right">
      <input type="text" name="exch_rate[{{ $i }}]" id="exch_rate{{ $i }}" value="{{ $data->exch_rate }}" readonly />
    </td>
    <td width="100px" align="right">
      <input type="text" name="fabric_wgt[{{ $i }}]" id="fabric_wgt{{ $i }}" value="{{ $data->fabric_wgt }}" readonly />
    </td>
    <td width="100px" align="right">{{ $data->dyes_cost }}</td>
    <td width="100px" align="right">{{ $data->chem_cost }}</td>
    <td width="100px" align="right">{{ $data->special_chem_cost }}</td>
    <td width="100px" align="right">{{ $data->overhead_amount }}</td>
    <td width="100px" align="right">
      <input type="text" name="total_cost[{{ $i }}]" id="total_cost{{ $i }}" value="{{ $data->total_cost }}" readonly />
    </td>
    <td width="100px" align="right">
      <input type="text" name="cost_per_kg_bdt[{{ $i }}]" id="cost_per_kg_bdt{{ $i }}" value="{{ $data->cost_per_kg_bdt }}" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="quoted_price_bdt[{{ $i }}]" id="quoted_price_bdt{{ $i }}" value="" class="number integer" onchange="MsSoEmbMktCostQpricedtl.calculateProfitQuotePrice({{ $i }},{{ $loop->count}})"/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_amount_bdt[{{ $i }}]" id="profit_amount_bdt{{ $i }}" value="" class="number integer" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_per[{{ $i }}]" id="profit_per{{ $i }}" value="" class="number integer" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="cost_per_kg[{{ $i }}]" id="cost_per_kg{{ $i }}" value="{{ $data->cost_per_kg }}" class="number integer" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="quoted_price[{{ $i }}]" id="quoted_price{{ $i }}" value="" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_amount[{{ $i }}]" id="profit_amount{{ $i }}" value="" class="number integer" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="" />
    </td>
  </tr>
  <?php
  $i++;
  ?>
  @endforeach
 </tbody>
</table>
@endif
@if($saved->isNotEmpty())
<br />
<p>Saved Quoted Price Details</p>
<table border="1">
 <tr align="center">
  <th width="15px" class="text-center">SL</th>
  <th width="70px" class="text-center">Color Range</th>
  <th width="40px" class="text-center">Color %</th>
  <th width="40px" class="text-center">Color %</th>
  <th width="40px" class="text-center">Exch Rate</th>
  <th width="100px" class="text-center">Fabric Qty</th>
  <th width="100px" class="text-center">Dyes cost</th>
  <th width="100px" class="text-center">Chemical Cost</th>
  <th width="100px" class="text-center">Add. Pros. Chem Cost</th>
  <th width="100px" class="text-center">Over<br/>heads</th>
  <th width="100px" class="text-center">Total Cost</th>
  <th width="100px" class="text-center">Cost/Kg -TK</th>
  <th width="100px" class="text-center">Quoted Price -TK</th>
  <th width="100px" class="text-center">Profit -TK</th>
  <th width="100px" class="text-center">Profit %</th>
  <th width="100px" class="text-center">Cost/Kg Usd</th>
  <th width="100px" class="text-center">Price Usd</th>
  <th width="100px" class="text-center">Profit Usd</th>
  <th width="100px" class="text-center">Remarks</th>
  <th width="100px" class="text-center"></th>
 </tr>
 <tbody>
  <?php
    //$i=1;
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
    $tQuotedPrice=0;
    $tProfitAmount=0;
    $tProfitPer=0;
  ?>
  @foreach($saved as $data)
  <tr align="center">
    <td width="15px" class="text-center">{{ $i }}</td>
    <td width="70px" align="left">
        {{ $data->colorrange_name }}
        <input type="hidden" name="so_aop_mkt_cost_param_id[{{ $i }}]" id="so_aop_mkt_cost_param_id{{ $i }}" value="{{ $data->so_aop_mkt_cost_param_id }}" />
        <input type="hidden" name="so_aop_mkt_cost_qprice_id[{{ $i }}]" id="so_aop_mkt_cost_qprice_id{{ $i }}" value="{{ $data->so_aop_mkt_cost_qprice_id }}" />
    </td>
    <td width="40px" align="right">{{ $data->color_ratio_from }}</td>
    <td width="40px" align="right">{{ $data->color_ratio_to }}</td>
    <td width="40px" align="right">
      <input type="text" name="exch_rate[{{ $i }}]" id="exch_rate{{ $i }}" value="{{ $data->exch_rate }}" readonly />
    </td>
    <td width="100px" align="right">
      <input type="text" name="fabric_wgt[{{ $i }}]" id="fabric_wgt{{ $i }}" value="{{ $data->fabric_wgt }}" readonly />
    </td>
    <td width="100px" align="right">{{ $data->dyes_cost }}</td>
    <td width="100px" align="right">{{ $data->chem_cost }}</td>
    <td width="100px" align="right">{{ $data->special_chem_cost }}</td>
    <td width="100px" align="right">{{ $data->overhead_amount }}</td>
    <td width="100px" align="right">
      <input type="text" name="total_cost[{{ $i }}]" id="total_cost{{ $i }}" value="{{ $data->total_cost }}" readonly />
    </td>
    <td width="100px" align="right">
      <input type="text" name="cost_per_kg_bdt[{{ $i }}]" id="cost_per_kg_bdt{{ $i }}" value="{{ $data->cost_per_kg_bdt }}" readonly/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="quoted_price_bdt[{{ $i }}]" id="quoted_price_bdt{{ $i }}" value="{{ $data->quoted_price_bdt }}" class="number integer" onchange="MsSoEmbMktCostQpricedtl.calculateProfitQuotePrice({{ $i }},{{ $loop->count}})"/>
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_amount_bdt[{{ $i }}]" id="profit_amount_bdt{{ $i }}" value="{{ $data->profit_amount_bdt }}" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_per[{{ $i }}]" id="profit_per{{ $i }}" value="{{ $data->profit_per }}" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="cost_per_kg[{{ $i }}]" id="cost_per_kg{{ $i }}" value="{{ $data->cost_per_kg }}" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="quoted_price[{{ $i }}]" id="quoted_price{{ $i }}" value="{{ $data->quoted_price }}" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="profit_amount[{{ $i }}]" id="profit_amount{{ $i }}" value="{{ $data->profit_amount }}" class="number integer" readonly />
    </td>
    <td width="100px" class="text-center">
      <input type="text" name="remarks[{{ $i }}]" id="remarks{{ $i }}" value="{{ $data->remarks }}"/>
    </td>
    <td width="100px"><a href="javascript:void(0)"
      onclick="MsSoEmbMktCostQpricedtl.delete(event,{{ $data->so_aop_mktcost_qpricedtl_id }})">Remove</a></td>
  </tr>
  <?php
    $i++;
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
 </tbody>
 <tr align="center">
    <td class="text-center" colspan="5"><strong>TOTAL</strong></td>
    <td width="100px" align="right">{{ $tFabricWgt }}</td>
    <td width="100px" align="right">{{ $tDyeCost }}</td>
    <td width="100px" class="text-center">{{ $tChemCost }}</td>
    <td width="100px" class="text-center">{{ $tSpecialChemCost }}</td>
    <td width="100px" class="text-center">{{ $tOverhead }}</td>
    <td width="100px" class="text-center">{{ $tTotalCost }}</td>
    <td width="100px" class="text-center">{{ $tCostPerKgBdt }}</td>
    <td width="100px" class="text-center">{{ $tQuotedPriceBdt }}</td>
    <td width="100px" class="text-center">{{ number_format($tProfitAmountTk,2) }}</td>
    <td width="100px" class="text-center">{{ number_format($tProfitPer,2) }}</td>
    <td width="100px" class="text-center">{{ $tCostPerKg }}</td>
    <td width="100px" class="text-center">{{ $tQuotedPrice }}</td>
    <td width="100px" class="text-center">{{ $tProfitAmount }}</td>
    <td width="100px" class="text-center"></td>
    <td width="100px"></td>
</tr>
</table>
@endif
<script>
 $('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
</script>
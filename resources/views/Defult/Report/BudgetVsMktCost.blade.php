<?php
$color='#F009';
?>
<table id="orderwisebudgetTbl" width="2990" border="1" style="border-style:dotted;line-height: 25px;">
<thead>
     <tr>
               <th width="40"></th>
               <th width="60" class="text-center">1</th>
               <th width="60" class="text-center">2</th>
               <th width="80" class="text-center">3</th>
               <th width="100" class="text-center">4</th>
               <th width="100" class="text-center">5</th>
               <th width="60" class="text-center">6</th>
               <th width="60" class="text-center">7</th>
               <th width="80" class="text-center">8</th>
               <th width="100" class="text-center">9</th>
               <th width="100" class="text-center">10</th>
                <th width="100" class="text-center">11</th>
               <th width="100" class="text-center">12</th>
               <th width="100" class="text-center">13</th>
               <th width="30" class="text-center">14</th>
       
               <th width="80" class="text-center">15</th>
               <th width="80" class="text-center">16</th>
               <th width="80" class="text-center">17</th>
               <th width="100" class="text-center">18</th>
               <th width="100" class="text-center">19</th>
               <th width="100" class="text-center">20</th>
               <th width="80" class="text-center">21</th>
               <th width="80" class="text-center">22</th>
               <th width="80" class="text-center">23</th>
               <th width="80" class="text-center">24</th>
               <!-- <th  width="80" class="text-center">Weaving</th> -->
               <th width="80" class="text-center">25</th>
               <th width="80" class="text-center">26</th>
               <th width="80" class="text-center">27</th>
               <!-- <th  width="80" class="text-center">Finishing</th> -->
               <th width="80" class="text-center">28</th>
               <th width="80" class="text-center">29</th>
               <th width="80" class="text-center">30</th>
               <th width="80" class="text-center">31</th>
               <th width="80" class="text-center">32</th>
               <th width="80" class="text-center">33</th>
               <th width="80" class="text-center">34</th>
               <th width="80" class="text-center">35</th>
               <th width="80" class="text-center">36</th>
               <th width="80" class="text-center">37</th>
               <th width="80" class="text-center">38</th>
               <th width="80" class="text-center">39</th>
               <th width="80" class="text-center">40</th>
               <th width="80" class="text-center">41</th>
     </tr>
    <tr>
        <th width="40">#</th>
        <th width="60" class="text-center">Company</th>
        <th width="60" class="text-center">Producing <br/>Unit</th>
        <th width="80" class="text-center">Team <br/>Leader</th>
        <th width="100" class="text-center">Dealing <br/>Merchant</th>
        <th width="100" class="text-center">Delivery Date</th>
        <th width="60" class="text-center">Buyer</th>
        <th width="60" class="text-center">Buying <br/>House</th>
        <th width="80" class="text-center">Style No</th>
        <th width="100" class="text-center">Season</th>
        <th width="100" class="text-center">Order No</th>
        <th width="100" class="text-center">LC/SC No</th>
        <th width="100" class="text-center">Order <br/>Recv Date</th>
        <th width="100" class="text-center">Prod. Dept</th>
        <th width="30" class="text-center">Image</th>

        <th width="80" class="text-center">Order Qty <br/>(Pcs)</th>
        <th width="80" class="text-center">Price (Pcs)</th>
        <th width="80" class="text-center">Selling Value</th>
        <th width="100" class="text-center">Particulars</th>
        <th width="80" class="text-center">Fin. Cons/Dzn</th>
        <th width="80" class="text-center">Fab Purchase</th>
        <th width="80" class="text-center">Yarn</th>
        <th width="80" class="text-center">Trim</th>
        <th width="80" class="text-center">Yarn Dyeing</th>
        <th width="80" class="text-center">Knitting</th>
        <!-- <th  width="80" class="text-center">Weaving</th> -->
        <th width="80" class="text-center">Dyeing</th>
        <th width="80" class="text-center">AOP</th>
        <th width="80" class="text-center">Burn Out</th>
        <!-- <th  width="80" class="text-center">Finishing</th> -->
        <th width="80" class="text-center">Washing</th>
        <th width="80" class="text-center">Screen <br/> Printing</th>
        <th width="80" class="text-center">Embroidery</th>
        <th width="80" class="text-center">Sp. Embelishment</th>
        <th width="80" class="text-center">GMT Dyeing</th>
        <th width="80" class="text-center">GMT Washing</th>
        <th width="80" class="text-center">Others</th>
        <th width="80" class="text-center">Freight</th>
        <th width="80" class="text-center">CM</th>
        <th width="80" class="text-center">Commission</th>
        <th width="80" class="text-center">Commercial</th>
        <th width="80" class="text-center">Total Cost</th>
        <th width="80" class="text-center">Profit</th>
        <th width="80" class="text-center">Profit %</th>
   </tr>
</thead>
<tbody>
    <?php
    $i=1;
    $qty=0;
    $qty=0;
    ?>
     @foreach ($rows as $row)
     <?php
    $qty+=$row->qty;
    $amount+=$row->amount;
    ?>
    <tr align="right">
        <td width="40" rowspan="3" align="left">{{$i}}</td>
        <td width="60" rowspan="3" align="center">{{$row->company_code}}</td>
        <td width="60" rowspan="3" align="center">{{$row->produced_company_code}}</td>
        <td width="80" rowspan="3" align="center">{{$row->team_name}}</td>
        <td width="100" rowspan="3" align="center">{{$row->team_member_name}}</td>
        <td width="100" rowspan="3" align="left">{{$row->delivery_date}}</td>
        
        <td width="60" rowspan="3" align="left">{{$row->buyer_name}}</td>
        <td width="60" rowspan="3" align="left">{{ $row->buying_agent_name }}</td>
        <td width="80" rowspan="3" align="left">{{ $row->style_ref }}</td>
        <td width="100" rowspan="3" align="left">{{$row->season_name}}</td>
        <td width="100" rowspan="3" align="left">{{$row->sale_order_no}}</td>
        <td width="100" rowspan="3" align="left">{{$row->lc_sc_no}}</td>
        <td width="100" rowspan="3" align="left">{{$row->sale_order_receive_date}}</td>
        <td width="100" rowspan="3" align="left">{{$row->department_name}}</td>
        <td width="30" rowspan="3" align="center"><img src="<?php echo url('/')."/images/".$row->flie_src?>" width="25" height="25" onClick="MsBudgetAndCostingComparison.imageWindow('{{$row->flie_src}}')"/></td>
        <td width="80"  rowspan="3">{{number_format($row->qty,2)}}</td>
        <td width="80" rowspan="3">{{number_format($row->rate,2)}}</td>
        <td width="80" rowspan="3">{{number_format($row->amount,2)}}</td>
        <td width="100" align="left">Marketing Cost</td>
        <td width="80">{{number_format($row->mkt_fab_fin_cons,2)}}</td>
        <td width="80">{{number_format($row->mkt_fab_pur_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_yarn_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_trim_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_yd_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_knit_amount,2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80">{{number_format($row->mkt_dye_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_aop_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_burn_amount,2)}}</td>
        <!-- <td  width="100" align="right">Finishing</td> -->
        <td width="80">{{number_format($row->mkt_fab_wash_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_print_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_embel_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_spembel_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_gmt_dye_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_gmt_wash_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_other_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_frei_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_cm_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_commi_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_commer_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_total_amount,2)}}</td>
        <td width="80">{{number_format($row->mkt_total_profit,2)}}</td>
        <td width="80">{{number_format($row->mkt_total_profit_per,2)}}</td>
   </tr>
   <tr align="right">
        <td width="100" align="left">Manufacturing Cost</td>
        <td width="80">{{number_format($row->fin_fab_cons,2)}}</td>
        <td width="80">{{number_format($row->fab_pur_amount,2)}}</td>
        <td width="80">{{number_format($row->yarn_amount,2)}}</td>
        <td width="80">{{number_format($row->trim_amount,2)}}</td>
        <td width="80">{{number_format($row->yarn_dying_amount,2)}}</td>
        <td width="80">{{number_format($row->kniting_amount,2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80">{{number_format($row->dying_amount,2)}}</td>
        <td width="80">{{number_format($row->aop_amount,2)}}</td>
        <td width="80">{{number_format($row->burn_out_amount,2)}}</td>
        <!-- <td  width="80" align="right">Finishing</td> -->
        <td width="80">{{number_format($row->washing_amount,2)}}</td>
        <td width="80">{{number_format($row->printing_amount,2)}}</td>
        <td width="80">{{number_format($row->emb_amount,2)}}</td>
        <td width="80">{{number_format($row->spemb_amount,2)}}</td>
        <td width="80">{{number_format($row->gmt_dyeing_amount,2)}}</td>
        <td width="80">{{number_format($row->gmt_washing_amount,2)}}</td>
        <td width="80">{{number_format($row->courier_amount,2)}}</td>
        <td width="80">{{number_format($row->freight_amount,2)}}</td>
        <td width="80">{{number_format($row->cm_amount,2)}}</td>
        <td width="80">{{number_format($row->commi_amount,2)}}</td>
        <td width="80">{{number_format($row->commer_amount,2)}}</td>
        <td width="80">{{number_format($row->total_amount,2)}}</td>
        <td width="80">{{number_format($row->total_profit,2)}}</td>
        <td width="80">{{number_format($row->total_profit_per,2)}}</td>
   </tr>

   <tr align="right" style="background-color: #24748f">
        <td width="80" align="left">Varience</td>
        <td width="80" style="background-color: <?php if($row->fin_fab_cons_vari < 0){ echo $color; }else{echo "";} ?>" >{{number_format($row->fin_fab_cons_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->fabpur_vari < 0){ echo $color; }else{echo "";} ?>" >{{number_format($row->fabpur_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->yarn_vari < 0){ echo $color; }else{echo "";} ?>" >{{number_format($row->yarn_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->trim_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->trim_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->yd_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->yd_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->kniting_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->kniting_vari,2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80" style="background-color: <?php if($row->dye_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->dye_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->aop_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->aop_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->burn_out_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->burn_out_vari,2)}}</td>
        <!-- <td  width="100" align="right">Finishing</td> -->
        <td width="80" style="background-color: <?php if($row->wash_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->wash_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->print_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->print_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->emb_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->emb_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->spemb_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->spemb_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_dye_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_dye_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_wash_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_wash_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_other_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_other_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_frei_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_frei_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_cm_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_cm_vari,2)}}</td>
        <td width="80" style="background-color: <?php if($row->gmt_commi_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_commi_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->gmt_commer_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->gmt_commer_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->total_amount_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->total_amount_vari,2)}}</td>

        <td width="80" style="background-color: <?php if($row->total_profit_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->total_profit_vari,2)}}</td>

       

        <td width="80" style="background-color: <?php if($row->total_profit_per_vari < 0){ echo $color; }else{echo "";} ?>">{{number_format($row->total_profit_per_vari,2)}}</td>
   </tr>
   <?php
    $i++;
    ?>
  @endforeach  
</tbody>
<tfoot>

    <tr align="right" style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="60" rowspan="3" align="right" colspan="15">Total</td>
        
        <td width="80"  rowspan="3">{{number_format($rows->sum('qty'),2)}}</td>
        <td width="80" rowspan="3">{{number_format($rows->avg('rate'),2)}}</td>
        <td width="80" rowspan="3">{{number_format($rows->sum('amount'),2)}}</td>
        <td width="100" align="left">Marketing Cost</td>
        <td width="80">{{number_format($rows->sum('mkt_fab_fin_cons'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_fab_pur_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_yarn_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_trim_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_yd_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_knit_amount'),2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80">{{number_format($rows->sum('mkt_dye_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_aop_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_burn_amount'),2)}}</td>
        <!-- <td  width="100" align="right">Finishing</td> -->
        <td width="80">{{number_format($rows->sum('mkt_fab_wash_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_print_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_embel_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_spembel_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_gmt_dye_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_gmt_wash_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_other_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_frei_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_cm_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_commi_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_commer_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_total_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_total_profit'),2)}}</td>
        <td width="80">{{number_format($rows->sum('mkt_total_profit_per'),2)}}</td>
   </tr>
   <tr align="right" style="background-color: #B1F8B7 ; color: #000;font-weight: bold">
        <td width="100" align="left">Manufacturing Cost</td>
        <td width="80">{{number_format($rows->sum('fin_fab_cons'),2)}}</td>
        <td width="80">{{number_format($rows->sum('fab_pur_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('yarn_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('trim_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('yarn_dying_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('kniting_amount'),2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80">{{number_format($rows->sum('dying_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('aop_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('burn_out_amount'),2)}}</td>
        <!-- <td  width="80" align="right">Finishing</td> -->
        <td width="80">{{number_format($rows->sum('washing_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('printing_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('emb_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('spemb_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('gmt_dyeing_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('gmt_washing_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('courier_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('freight_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('cm_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('commi_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('commer_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('total_amount'),2)}}</td>
        <td width="80">{{number_format($rows->sum('total_profit'),2)}}</td>
        <td width="80">{{number_format($rows->sum('total_profit_per'),2)}}</td>
   </tr>
   <tr align="right" style="background-color: #24748f ; color: #000;font-weight: bold">
        <td width="80" align="left">Varience</td>

        <td width="80" style="background-color: <?php if($rows->sum('fin_fab_cons_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('fin_fab_cons_vari'),2)}}</td>

        <td width="80" style="background-color: <?php if($rows->sum('fabpur_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('fabpur_vari'),2)}}</td>

        <td width="80" style="background-color: <?php if($rows->sum('yarn_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('yarn_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('trim_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('trim_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('yd_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('yd_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('kniting_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('kniting_vari'),2)}}</td>
        <!-- <td  width="80">Weaving</td> -->
        <td width="80" style="background-color: <?php if($rows->sum('dye_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('dye_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('aop_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('aop_vari'),2)}}</td>
        <td width="80">{{number_format($rows->sum('burn_out_vari'),2)}}</td>
        <!-- <td  width="100" align="right">Finishing</td> -->
        <td width="80" style="background-color: <?php if($rows->sum('wash_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('wash_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('print_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('print_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('emb_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('emb_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('spemb_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('spemb_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_dye_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_dye_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_wash_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_wash_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_other_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_other_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_frei_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_frei_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_cm_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_cm_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_commi_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_commi_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('gmt_commer_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('gmt_commer_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('total_amount_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('total_amount_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('total_profit_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('total_profit_vari'),2)}}</td>
        <td width="80" style="background-color: <?php if($rows->sum('total_profit_per_vari') < 0){ echo $color; }else{echo "";} ?>">{{number_format($rows->sum('total_profit_per_vari'),2)}}</td>
   </tr>
    
</tfoot>
</table>
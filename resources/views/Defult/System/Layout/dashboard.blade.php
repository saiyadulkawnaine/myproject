
<style>
.responsive {
  width: 100%;
  height: auto;
}
.datagrid-cell {
    height: auto;
}
.chartcon
    {
      position: absolute;
      top: 230px;
      left: 477px;
      width: 30px;
      height: 150px;
      /*background-color: #ffffff;*/
    }
</style>
<p style="margin:0 auto; text-align:center">
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.samplereportWindow()">R & D</a>&nbsp;&nbsp;&nbsp;

   <!--  <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.prodgmtcapacityachievementWindow()">RMG Prod & Achievement</a> -->

    &nbsp;&nbsp;&nbsp;
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete"onClick="MsDashBord.prodfabriccapacityachievementWindow()">Fabric Prod. </a>&nbsp;&nbsp;&nbsp;

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.todayshipmentWindow()">Today Shipment</a>&nbsp;&nbsp;&nbsp;

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.pendingshipmentWindow()">Pending Shipment </a>
    
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.liabilitycoveragereportsWindow()">Commercial</a>

    @level(5)
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.todayaccountWindow()">Accounts</a>
    @endlevel

    @level(5)
    {{--<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.receiptspaymentsaccountWindow()">Receipts & Payments</a>--}}
    @endlevel

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.bepWindow()">BEP</a>

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.prodgmtAllGraphWindow()"> Plan (RMG)</a>

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.gmtCapGraphDayWindow()">Day Plan (RMG)</a>

    

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.txtCapGraphDayWindow()">Day Plan (Textile)</a>

    <!-- <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.todaySewingAchivementWindow()">Today Sewing</a> -->

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.todayInventoryReportWindow()">Inventory</a>

    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.dailyefficiencyWindow()">RMG Effi</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.txtdailyefficiencyWindow()">Textile Effi</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.targetAchievementWindow()">Target Achive</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px;border-radius:1px"  plain="true" id="delete" onClick="MsDashBord.bankLoanWindow()">Bank Liab.</a>
</p>


<!-- <div class="chartcon">
      <canvas id="myChart" width="30" height="180"></canvas>

</div> -->

<img src="images/logo/bg-1.JPG" width="1258.6" height="887.35" usemap="#workmap" class="responsive">
<map name="workmap">
<area shape="rect" coords="475, 189, 498, 383" href="javascript:void(0)" title="Central Plan (RMG)" alt="Central Plan (RMG)" onclick="MsDashBord.gmtCapGraphWindow()">
<area shape="rect" coords="505, 236, 524, 362" href="javascript:void(0)" title="Order Forcasting" alt="Order Forcasting" onclick="MsDashBord.orderforcastingWindow()">
<area shape="rect" coords="532, 222, 552, 335" href="javascript:void(0)" title="Capacity & Order Booked" alt="Capacity & Order Booked" onclick="MsDashBord.gmtCapShipGraphWindow()">
<area shape="rect" coords="561, 211, 581, 302" href="javascript:void(0)" title="Cash Incentive" alt="">
<area shape="rect" coords="587, 134, 610, 267" href="javascript:void(0)" title="Central Plan (Textile)" alt="Central Plan (Textile)" onclick="MsDashBord.txtCapGraphWindow()">
<area shape="rect" coords="614, 179, 634, 260" href="javascript:void(0)" title="Consume Dys & Chemical" alt="">
<area shape="rect" coords="636, 153, 653, 238" href="javascript:void(0)" title="M/C Breakdown" alt="">
<area shape="rect" coords="656, 148, 674, 214" href="javascript:void(0)" title="Group Liability" alt="">
<area shape="rect" coords="677, 142, 693, 209" href="javascript:void(0)" title="Group Receivable" onclick="MsDashBord.groupReceivableWindow()">
<area shape="rect" coords="696, 39, 711, 194" href="javascript:void(0)" title="Group Sales" onclick="MsDashBord.groupSaleWindow()"> 
 


<area shape="poly" coords="824, 231, 853, 225, 887, 235, 852, 296, 843, 280" href="javascript:void(0)" title="Today Sewing Performance" alt="Today Sewing Performance" onclick="MsDashBord.todaySewingAchivementWindow()">
<area shape="poly" coords="859, 314, 910, 298, 938, 292, 935, 343, 897, 331" href="javascript:void(0)" title="Dyeing Performance" alt="Dyeing Performance" onclick="MsDashBord.todayDyeingAchivementWindow()">

<area shape="poly" coords="857, 322, 885, 330, 924, 349, 873, 385, 864, 352" href="javascript:void(0)" title="AOP Performance" alt="AOP Performance" onclick="MsDashBord.todayAopAchivementWindow()">
<area shape="poly" coords="860, 286, 899, 238, 925, 258, 935, 280, 860, 300" href="javascript:void(0)" title="Kniting Performance" alt="Kniting Performance" onclick="MsDashBord.todayKnitingAchivementWindow()">
<area shape="poly" coords="788, 250, 815, 238, 837, 300, 823, 293, 774, 269" href="javascript:void(0)" title="new" alt="">
<area shape="poly" coords="761, 294, 768, 276, 836, 309, 801, 318, 758, 325" href="javascript:void(0)" title="new" alt="">
<area shape="poly" coords="761, 339, 801, 329, 839, 320, 804, 378, 785, 366" href="javascript:void(0)" title="new" alt="">
<area shape="poly" coords="811, 382, 845, 324, 861, 378, 864, 389, 833, 386" href="javascript:void(0)" title="new" alt="">
<area shape="poly" coords="608, 368, 648, 331, 688, 372, 674, 439, 603, 435" href="#" title="Asset (Fixed)" alt="">
<area shape="rect" coords="353, 559, 721, 642" href="javascript:void(0)" title="Net Worth" alt="">
<area shape="poly" coords="903, 444, 1023, 238, 1054, 258, 942, 461, 925, 457" href="javascript:void(0)" title="Central Budget" alt="" onclick="MsDashBord.centralBudgetWindow()">
<area shape="rect" coords="502, 418, 588, 468" href="javascript:void(0)" title="Asset Idle Time Report" alt="" onclick="MsDashBord.assetIdleTimeWindow()">

<!-- <area shape="rect" coords="349, 566, 530, 630" href="javascript:void(0)" title="Cash Budget" alt="">
<area shape="rect" coords="536, 573, 718, 633" href="javascript:void(0)" title="Net Worth" alt=""> -->
</map>



<div id="todayShipmentWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayShipmentContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>



<div id="pendingShipmentWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="pendingShipmentContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>


<div id="prodgmtcapacityachievementWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="prodgmtcapacityachievementContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

    
<div id="dashbordReportImageWindow" class="easyui-window" title="Image" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:500px;padding:2px;">
    <img id="dashbordReportImageWindowoutput" src=""/>
</div>

<div id="samplerequirementWindow" class="easyui-window" title="Order Development" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="samplerequirementContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="liabilitycoveragereportsWindow" class="easyui-window" title="Liability Coverage Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="liabilitycoveragereportsContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="todayAccountWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayAccountContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="receiptspaymentsaccountWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="receiptspaymentsaccountContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="todaybepWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todaybepContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="todayInventoryReportWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="todayInventoryReportContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="gmtcapgraphwindow" class="easyui-window" title="Central Plan
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="gmtcapgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
    
</div>
<div id="orderforcastingwindow" class="easyui-window" title="Order Forcasting
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="orderforcastingwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
    
</div>
<div id="gmtcapshipgraphwindow" class="easyui-window" title="Capacity & Order Booked
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="gmtcapshipgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
    
</div>

<div id="gmtcapgraphdaywindow" class="easyui-window" title="Day Plan
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="gmtcapgraphwindowcontainerwraperday" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="gmtallcapgraphwindow" class="easyui-window" title="Production Plan
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="gmtallcapgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
    
</div>

<div id="txtcapgraphwindow" class="easyui-window" title="Central Plan (Textile)
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="txtcapgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
    
</div>

<div id="txtcapgraphdaywindow" class="easyui-window" title="Day Plan (Textile)
" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="txtcapgraphwindowcontainerwraperday" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="todaysewingachivementgraphwindow" class="easyui-window" title="Today Sewing Performance
" data-options="modal:true,closed:true,iconCls:'icon-large-chart'" style="width:100%;height:100%;padding:2px;">
    <div id="todaysewingachivementgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="todaydyeingachivementgraphwindow" class="easyui-window" title="Dyeing Performance
" data-options="modal:true,closed:true,iconCls:'icon-large-chart'" style="width:100%;height:100%;padding:2px;">
    <div id="todaydyeingachivementgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="todayaopachivementgraphwindow" class="easyui-window" title="AOP Performance
" data-options="modal:true,closed:true,iconCls:'icon-large-chart'" style="width:100%;height:100%;padding:2px;">
    <div id="todayaopachivementgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="todayknitingachivementgraphwindow" class="easyui-window" title="Kniting Performance
" data-options="modal:true,closed:true,iconCls:'icon-large-chart'" style="width:100%;height:100%;padding:2px;">
    <div id="todayknitingachivementgraphwindowcontainerwraper" style="width:100%;height:100%;padding:0px;">
    </div>
</div>

<div id="dailyefficiencyWindow" class="easyui-window" title="Daily Efficiency Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="dailyefficiencyContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="targetachievementWindow" class="easyui-window" title="Target Achievement Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="targetachievementContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="groupsalewindow" class="easyui-window" title="Group Sales Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="groupSaleContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="groupreceivablewindow" class="easyui-window" title="Group Receivable Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="groupReceivableContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="centralbudgetwindow" class="easyui-window" title="Central Budget Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="centralbudgetContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="assetidletimewindow" class="easyui-window" title="Asset Idle Time  Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="assetidletimeContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="txtdailyefficiencyWindow" class="easyui-window" title="Textile Efficiency Report" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="txtdailyefficiencyContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>

<div id="bankloanWindow" class="easyui-window" title="Bank Liablity" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="bankloanContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>






<div id="capacitydetailWindow" class="easyui-window" title="Line Wise Production Details " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="capacitydetailTbl" style="width:100%">
<thead>
    <tr>
        <th data-options="field:'apm',halign:'center'" width="80">Supervisor</th>
        <th data-options="field:'line',halign:'center'" width="100">Line</th>
        <th data-options="field:'floor',halign:'center'" width="100">Floor</th>
        <th data-options="field:'buyer_code',halign:'center'" width="80">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order no.</th>
        <th data-options="field:'cm',halign:'center'," width="50" align="right">CM/<br/>DZN</th>
        <th data-options="field:'item_description',halign:'center'"  width="100" align="left">GMT Item 
        </th>
        <th data-options="field:'smv',halign:'center'"  width="60" align="left">SMV
        </th>
        <th data-options="field:'smv_used',halign:'center'"  width="60" align="right"> Used <br/> Mnts./ Pcs
        </th>
        <th data-options="field:'smv_variance',halign:'center'"  width="50" align="right"> SMV <br/> Variance
        </th>
        <th data-options="field:'effi_per',halign:'center'" width="50" align="right">Effi. %</th>
        <th data-options="field:'operator',halign:'center'" width="40" align="right">Used <br/>Opt.</th>
        <th data-options="field:'helper',halign:'center'" width="40" align="right">Used <br/>Hlp.</th>

        <th data-options="field:'manpower',halign:'center'" width="40" align="right">TTL.<br/> Mnp  </th>
        <th data-options="field:'capacity_qty',halign:'center'" width="80" align="right">Capacity<br/> Qty.  </th>
        <th data-options="field:'sew_qty',halign:'center'" width="80" align="right">Prod.<br/> Qty  </th>
        <th data-options="field:'capacity_dev',halign:'center',styler:MsProdGmtCapacityAchievement.capacityDevformat" width="60" align="right">Capacity <br/>Variance  </th>
        <th data-options="field:'capacity_ach',halign:'center'" width="60" align="right">Capacity <br/>Achieve %  </th>
        <th data-options="field:'produced_mint',halign:'center'" width="80" align="right">Prod. <br/> Mnts. </th>
        <th data-options="field:'wh',halign:'center'" width="40" align="right" > WH </th>
        <th data-options="field:'ot',halign:'center'" width="40" align="right" >OT Hrs</th>
        <th data-options="field:'mp_ot_hrs',halign:'center'" width="40" align="right" >MP /<br/> OT Hrs</th>
        <th data-options="field:'mp_ot_hrs',halign:'center'" width="40" align="right" >OT<br/> Amount</th>
        <th data-options="field:'used_mint',halign:'center'" width="80" align="right">Used <br/> Mnts.  </th>
        <th data-options="field:'avg_smv_pcs',halign:'center'" width="80" align="right">Avg <br/> Smv/Pcs.  </th>
        <th data-options="field:'dev_avg_smv_pcs',halign:'center'" width="80" align="right">Avg <br/> Smv/Pcs. Varience  </th>
        <th data-options="field:'cm_pcs',halign:'center'" width="80" align="right">CM  <br/> Cost/Pcs.  </th>
        <th data-options="field:'cm_used_pcs',halign:'center'" width="80" align="right">CM Used <br/> Cost/Pcs.  </th>
        <th data-options="field:'dev_cm_pcs',halign:'center'" width="80" align="right">CM Cost/Pcs <br/> Varience  </th>
        <th data-options="field:'cm_earned_usd',halign:'center'" width="80" align="right">CM Earned <br/>USD</th>
        <th data-options="field:'cm_earned_tk',halign:'center'" width="80" align="right">CM Earned <br/>TK</th> 
        <th data-options="field:'prodused_fob',halign:'center'" width="80" align="right">Produced <br/>Value USD</th>
        <th data-options="field:'prodused_fob_tk',halign:'center'" width="80" align="right">Produced <br/>Value TK</th>
   </tr>
</thead>
</table>
</div>



<div id="capacitybepWindow" class="easyui-window" title="BEP Details " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="capacitybepmatrix">
    </div>
</div>

<div id="capacitycmWindow" class="easyui-window" title="BEP Details " data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="capacitycmmatrix">
    </div>
</div>










<div id="dlmerchantWindow" class="easyui-window" title="Merchandiser Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="dealmctinfoTbl" style="width:100%;height:250px">
        <thead>
            <tr>
                <th data-options="field:'name'" width="120">Name</th>
                <th data-options="field:'date_of_join'" width="80px">Date Of Join </th>
                <th data-options="field:'last_education'" width="100px">Last Education</th>
                <th data-options="field:'contact'" width="100px">Contact</th>
                <th data-options="field:'experience'" width="100px">Experience</th>
                <th data-options="field:'email'" width="100px">Email</th>
                <th data-options="field:'address'" width="350px">Address</th>
            </tr>
        </thead>
    </table>
</div>
<div id="filesrcwindow" class="easyui-window" title="Style Uploaded Files" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="filesrcTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="30">ID</th>
                <th data-options="field:'file_src'" width="180px">File Source </th>
                <th data-options="field:'original_name'" formatter="MsSampleRequirement.formatShowFile" width="250px">Original Name</th>
            </tr>
        </thead>
    </table>
</div>

<div id="buyagentwindow" class="easyui-window" title="Buying Agents Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <table id="buyagentTbl" style="width:500px">
        <thead>
            <tr>
                <th data-options="field:'id'" width="40">ID</th>
                <th data-options="field:'buyer_name'" width="180px">Buyer Name </th>
                <th data-options="field:'branch_name'" width="180px">Branch Name</th>
                <th data-options="field:'contact_person'" width="180px">Contact Person</th>
                <th data-options="field:'email'" width="180px">Email</th>
                <th data-options="field:'designation'" width="180px">Designation</th>
                <th data-options="field:'address'" width="250px">Address</th>
            </tr>
        </thead>
    </table>
</div>

<div id="capacitycartonWindow" class="easyui-window" title="Carton Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacitycartonTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'required_carton',halign:'center'" width="80" align="right">Required <br/>Carton</th>
        <th data-options="field:'no_of_carton',halign:'center'" width="80" align="right">Today <br/>Carton Made </th>
        <th data-options="field:'no_of_carton_yester_day',halign:'center'" width="80" align="right">Carton Made <br/>Upto Yesterday </th>
        <th data-options="field:'total_carton',halign:'center'" width="80" align="right">Total <br/>Carton Made </th>
        <th data-options="field:'yet_to_no_of_carton',halign:'center'" width="80" align="right">Yet To Made</th>
        <th data-options="field:'finishing_qty',halign:'center'" width="80" align="right">Today <br/>Finishing Qty</th>
        <th data-options="field:'finishingyesterday_qty',halign:'center'" width="80" align="right">Finished <br/> Upto Yesterday</th>
        <th data-options="field:'total_finishing',halign:'center'" width="80" align="right">Total <br/>Finishing Qty</th>
        <th data-options="field:'yet_to_finishing',halign:'center'" width="80" align="right">Yet to <br/>Finish</th>
        
        
        <th data-options="field:'finishing_amount',halign:'center'" width="80" align="right">Today Finishing <br/>FOB Value</th>

        <th data-options="field:'cm_mnuf',halign:'center'" width="80" align="right">Today <br/>  CM Mnfg</th>
        <th data-options="field:'cm_mkt',halign:'center'" width="80" align="right">Today <br/> CM Mkt</th>
        </tr> 
    </thead>
  </table>
</div>
<div id="capacitycartonMonthWindow" class="easyui-window" title="Carton Month Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacitycartonMonthTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'required_carton',halign:'center'" width="80" align="right">Required <br/>Carton</th>
        <th data-options="field:'no_of_carton',halign:'center'" width="80" align="right">This Month <br/>Carton Made </th>
        <th data-options="field:'no_of_carton_yester_day',halign:'center'" width="80" align="right">Carton Made <br/>Upto Last Month </th>
        <th data-options="field:'total_carton',halign:'center'" width="80" align="right">Total <br/>Carton Made </th>
        <th data-options="field:'yet_to_no_of_carton',halign:'center'" width="80" align="right">Yet To Made</th>
        <th data-options="field:'finishing_qty',halign:'center'" width="80" align="right">This Month <br/>Finishing Qty</th>
        <th data-options="field:'finishingyesterday_qty',halign:'center'" width="80" align="right">Finished <br/> Upto Last Month</th>
        <th data-options="field:'total_finishing',halign:'center'" width="80" align="right">Total <br/>Finishing Qty</th>
        <th data-options="field:'yet_to_finishing',halign:'center'" width="80" align="right">Yet to <br/>Finish</th>
        <th data-options="field:'finishing_amount',halign:'center'" width="80" align="right">This Month Finishing <br/>FOB Value</th>
        <th data-options="field:'cm_mnuf',halign:'center'" width="80" align="right">This Month <br/> CM Mnfg</th>
        <th data-options="field:'cm_mkt',halign:'center'" width="80" align="right">This Month <br/> CM Mkt</th>
        </tr> 
    </thead>
  </table>
</div>
<div id="capacitycommonWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div class="easyui-layout animated rollIn"  data-options="fit:true">
    <div data-options="region:'west',border:true,title:'Search',footer:'#capacitycommonWindowFrmft'" style="width:350px; padding:2px">
        <form id="capacitycommonWindowFrm">
            <div id="container">
                <div id="body">
                    <code>
                       <div class="row">
                            <div class="col-sm-4">Style Ref</div>
                            <div class="col-sm-8">
                            <input type="text" name="style_ref" id="style_ref" />
                            <input type="hidden" name="company_id" id="company_id" />
                            <input type="hidden" name="route_url" id="route_url" />
                        </div>
                        </div> 
                        <div class="row middle">
                            <div class="col-sm-4">Sale Order No</div>
                            <div class="col-sm-8">
                            <input type="text" name="sale_order_no" id="sale_order_no" />
                        </div>
                    </div>
                        
                    </code>
                </div>
            </div>
        </form>
        <div id="capacitycommonWindowFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProdGmtCapacityAchievement.getCommon()">Show</a>
                </div>
    </div>
    <div data-options="region:'center',border:true,title:'List'" style=" padding:2px">
        <div id="capacitycommondata">
        </div>
    </div>
</div>
</div>

<div id="capacitycuttingWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <table id="capacitycuttingTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'company_id',halign:'center'" width="60" align="center">1</th>
                <th data-options="field:'pcompany',halign:'center'" width="60" align="center">2</th>
                <th data-options="field:'buyer_code',halign:'center'" width="60" align="center">3</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">4</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">5</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">6</th>
                <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">7</th>
                <th data-options="field:'order_qty',halign:'center'" width="80" align="right">8</th>
                <th data-options="field:'order_rate',halign:'center'" width="60" align="right">9</th>
                
                <th data-options="field:'order_amount',halign:'center'" width="80" align="right">10</th>
                <th data-options="field:'order_plan_cut_qty',halign:'center'" width="80" align="right">11</th>
                <th data-options="field:'cutting_qty',halign:'center'" width="80" align="right">12</th>
                <th data-options="field:'cuttingyesterday_qty',halign:'center'" width="80" align="right">13 </th>
                <th data-options="field:'total_cut',halign:'center'" width="70" align="right">14</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">15</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">16</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">17</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">18</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">19</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">20</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">21</th>
                <th data-options="field:'yet_cut',halign:'center'" width="70" align="right">22</th>
    
            </tr> 
            <tr>
                <th data-options="field:'company_id',halign:'center'" width="60" align="center">Beneficiary<br/> Company</th>
                <th data-options="field:'pcompany',halign:'center'" width="60" align="center">Producing <br/>Company</th>
                <th data-options="field:'buyer_code',halign:'center'" width="60" align="center">Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
                <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
                <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
                <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
                
                <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
                <th data-options="field:'order_plan_cut_qty',halign:'center'" width="80" align="right">Required<br/> Qty</th>
                <th data-options="field:'cutting_qty',halign:'center'" width="80" align="right">Today <br/>Cut Qty</th>
                <th data-options="field:'cuttingyesterday_qty',halign:'center'" width="80" align="right">Cut Qty Upto <br/>Yesterday</th>
                <th data-options="field:'total_cut',halign:'center'" width="80" align="right">Total <br/>Cut Qty <br/>12+13</th>
                <th data-options="field:'yet_cut',halign:'center'" width="80" align="right">Yet To <br/>Cut Qty <br/>11-14</th>
                <th data-options="field:'style_cad_cons',halign:'center'" width="70" align="right">CAD <br/>Cons/Dzn</th>
                <th data-options="field:'used_fabric',halign:'center'" width="70" align="right">Fabric <br/>Used/Kg</th>
                <th data-options="field:'req_fabric',halign:'center'" width="70" align="right">Fabric <br/>Used/Dzn <br/>(17/14)*12</th>
                <th data-options="field:'used_variance',halign:'center'" width="70" align="right">Variance<br/> /Dzn <br/>16-18</th>
                <th data-options="field:'wastage_fabric',halign:'center'" width="70" align="right">Wastage <br/>Fabric</th>
                <th data-options="field:'cut_pcs_should_be',halign:'center'" width="70" align="right">Cut Pcs<br/>Should Be<br/>(17/16)/12</th>
                <th data-options="field:'wastage_variance',halign:'center'" width="70" align="right">Variance<br/> /Dzn <br/> 14-21 </th>
                
            </tr>
        </thead>
      </table>
</div>

<div id="capacitycuttingMonthWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacitycuttingMonthTbl" style="width:100%">
    <thead>
        <tr>
            <th data-options="field:'company_id',halign:'center'" width="80">1</th>
            <th data-options="field:'pcompany',halign:'center'" width="80">2</th>
            <th data-options="field:'buyer_code',halign:'center'" width="80">3</th>
            <th data-options="field:'style_ref',halign:'center'" width="80">4</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">5</th>
            <th data-options="field:'ship_date',halign:'center'" width="80">6</th>
            <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">7</th>
            <th data-options="field:'order_qty',halign:'center'" width="80" align="right">8</th>
            <th data-options="field:'order_rate',halign:'center'" width="60" align="right">9</th>
            
            <th data-options="field:'order_amount',halign:'center'" width="80" align="right">10</th>
            <th data-options="field:'order_plan_cut_qty',halign:'center'" width="80" align="right">11</th>
            <th data-options="field:'cutting_qty',halign:'center'" width="80" align="right">12</th>
            <th data-options="field:'cuttingyesterday_qty',halign:'center'" width="80" align="right">13</th>
            <th data-options="field:'total_cut',halign:'center'" width="80" align="right">14</th>
            <th data-options="field:'yet_cut',halign:'center'" width="80" align="right">15</th>
        </tr> 
        <tr>
            <th data-options="field:'company_id',halign:'center'" width="80">Beneficiary<br/> Company</th>
            <th data-options="field:'pcompany',halign:'center'" width="80">Producing <br/>Company</th>
            <th data-options="field:'buyer_code',halign:'center'" width="80">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
            <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
            <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
            <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
            <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
            
            <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
            <th data-options="field:'order_plan_cut_qty',halign:'center'" width="80" align="right">Requried<br/> Qty</th>
            <th data-options="field:'cutting_qty',halign:'center'" width="80" align="right">This Month <br/> Cut Qty</th>
            <th data-options="field:'cuttingyesterday_qty',halign:'center'" width="80" align="right">Cut Qty Upto <br/>Last Month</th>
            <th data-options="field:'total_cut',halign:'center'" width="80" align="right">Total <br/>Cut Qty </th>
            <th data-options="field:'yet_cut',halign:'center'" width="80" align="right">Yet To <br/>Cut Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityscprintWindow" class="easyui-window" title="Printing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityscprintTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        
        <th data-options="field:'scprint_qty',halign:'center'" width="80" align="right">Today Print Qty</th>
        <th data-options="field:'scprintyesterday_qty',halign:'center'" width="80" align="right">Print Qty Upto <br/>Yesterday</th>
        <th data-options="field:'total_scprint',halign:'center'" width="80" align="right">Total <br/>Print Qty </th>
        <th data-options="field:'yet_scprint',halign:'center'" width="80" align="right">Yet To <br/>Print Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityscprintMonthTgtWindow" class="easyui-window" title="Printing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityscprintMonthTgtTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'req_qty',halign:'center'" width="80" align="right">Req Qty</th>
        <th data-options="field:'scprint_qty',halign:'center'" width="80" align="right">This Month Print Qty</th>
        <th data-options="field:'scprintyesterday_qty',halign:'center'" width="80" align="right">Print Qty Upto <br/>Last Month</th>
        <th data-options="field:'total_scprint',halign:'center'" width="80" align="right">Total <br/>Print Qty </th>
        <th data-options="field:'yet_scprint',halign:'center'" width="80" align="right">Yet To <br/>Print Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityscprintMonthWindow" class="easyui-window" title="Printing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityscprintMonthTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        
        <th data-options="field:'scprint_qty',halign:'center'" width="80" align="right">This Month Print Qty</th>
        <th data-options="field:'scprintyesterday_qty',halign:'center'" width="80" align="right">Print Qty Upto <br/>Last Month</th>
        <th data-options="field:'total_scprint',halign:'center'" width="80" align="right">Total <br/>Print Qty </th>
        <th data-options="field:'yet_scprint',halign:'center'" width="80" align="right">Yet To <br/>Print Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityembWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityembTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        
        <th data-options="field:'emb_qty',halign:'center'" width="80" align="right">Today Embro. Qty</th>
        <th data-options="field:'embyesterday_qty',halign:'center'" width="80" align="right">Embro Qty Upto <br/>Yesterday</th>
        <th data-options="field:'total_emb',halign:'center'" width="80" align="right">Total <br/>Embro Qty </th>
        <th data-options="field:'yet_emb',halign:'center'" width="80" align="right">Yet To <br/>Embro Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityembMountWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityembMonthTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        
        <th data-options="field:'emb_qty',halign:'center'" width="80" align="right">Today Embro. Qty</th>
        <th data-options="field:'embyesterday_qty',halign:'center'" width="80" align="right">Embro Qty Upto <br/>Yesterday</th>
        <th data-options="field:'total_emb',halign:'center'" width="80" align="right">Total <br/>Embro Qty </th>
        <th data-options="field:'yet_emb',halign:'center'" width="80" align="right">Yet To <br/>Embro Qty</th>
        </tr> 
    </thead>
  </table>
</div>
<div id="capacityembMountTgtWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityembMonthTgtTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'req_qty',halign:'center'" width="80" align="right">Req. Qty</th>
        
        <th data-options="field:'emb_qty',halign:'center'" width="80" align="right">Today Embro. Qty</th>
        <th data-options="field:'embyesterday_qty',halign:'center'" width="80" align="right">Embro Qty Upto <br/>Yesterday</th>
        <th data-options="field:'total_emb',halign:'center'" width="80" align="right">Total <br/>Embro Qty </th>
        <th data-options="field:'yet_emb',halign:'center'" width="80" align="right">Yet To <br/>Embro Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityaopMonthTgtWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityaopMonthTgtTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'req_qty',halign:'center'" width="80" align="right">Req. Qty</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityexfactoryWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityexfactoryTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
       
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'exfactory_qty',halign:'center'" width="80" align="right">Today Ex-Fact Qty</th>
        <th data-options="field:'exfactoryyesterday_qty',halign:'center'" width="80" align="right">Ex-Fact Qty Upto <br/>Yesterday</th>
        <th data-options="field:'total_exfactory',halign:'center'" width="80" align="right">Total <br/>Ex-Fact Qty </th>
        <th data-options="field:'yet_exfactory',halign:'center'" width="80" align="right">Yet To <br/>Ex-Fact Qty</th>
        <th data-options="field:'exfactory_amount',halign:'center'" width="80" align="right">Today Ex-Fact Amount</th>

        </tr> 
    </thead>
  </table>
</div>

<div id="capacityexfactoryMonthWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityexfactoryMonthTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
       
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'exfactory_qty',halign:'center'" width="80" align="right">This Month <br/>Ex-Fact Qty</th>
        <th data-options="field:'exfactoryyesterday_qty',halign:'center'" width="80" align="right">Ex-Fact Qty Upto <br/>Last Month</th>
        <th data-options="field:'total_exfactory',halign:'center'" width="80" align="right">Total <br/>Ex-Fact Qty </th>
        <th data-options="field:'yet_exfactory',halign:'center'" width="80" align="right">Yet To <br/>Ex-Fact Qty</th>
        <th data-options="field:'exfactory_amount',halign:'center'" width="80" align="right">This Month <br/>Ex-Fact Amount</th>
        </tr> 
    </thead>
  </table>
</div>


<div id="capacitysewingMonthWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacitysewingMonthTbl" style="width:100%">
    <thead>
        <tr>
            <th data-options="field:'company_id',halign:'center'">1</th>
            <th data-options="field:'pcompany',halign:'center'" width="100">2</th>
            <th data-options="field:'buyer_code',halign:'center'" width="100">3</th>
            <th data-options="field:'style_ref',halign:'center'" width="80">4</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">5</th>
            <th data-options="field:'ship_date',halign:'center'" width="80">6</th>
            <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">7</th>
            <th data-options="field:'order_qty',halign:'center'" width="80" align="right">8</th>
            <th data-options="field:'order_rate',halign:'center'" width="60" align="right">9</th>
            <th data-options="field:'order_amount',halign:'center'" width="80" align="right">10</th>
            <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">11</th>
            <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">12</th>
            <th data-options="field:'total_sewing',halign:'center'" width="80" align="right">13</th>
            <th data-options="field:'yet_sewing',halign:'center'" width="80" align="right">14</th>
            <th data-options="field:'sewing_amount',halign:'center'" width="80" align="right">14</th>
        </tr> 
        <tr>
            <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
            <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
            <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
            <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
            <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
            <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
            <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
            <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
            <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">This Month <br/> Sewing Qty</th>
            <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">Sewing Qty Upto <br/>Last Month</th>
            <th data-options="field:'total_sewing',halign:'center'" width="80" align="right">Total <br/>Sewing Qty </th>
            <th data-options="field:'yet_sewing',halign:'center'" width="80" align="right">Yet To <br/>Sewing Qty</th>
            <th data-options="field:'sewing_amount',halign:'center'" width="80" align="right">This Month <br/> Sewing Amount</th>
        </tr> 
    </thead>
  </table>
</div>

<div id="capacityinvoiceMonthWindow" class="easyui-window" title="Cutting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
  <table id="capacityinvoiceMonthTbl" style="width:100%">
    <thead>
        <tr>
        <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
        <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
        <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
        <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
        <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
        <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
        <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
        <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
        <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
        <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
        <th data-options="field:'invoice_qty',halign:'center'" width="80" align="right">This Month Invoice Qty</th>
        <th data-options="field:'invoiceyesterday_qty',halign:'center'" width="80" align="right">Invoice Qty Upto <br/>Last Month</th>
        <th data-options="field:'total_invoice',halign:'center'" width="80" align="right">Total <br/>Invoice Qty </th>
        <th data-options="field:'yet_invoice',halign:'center'" width="80" align="right">Yet To <br/>Invoice Qty</th>
        <th data-options="field:'invoice_amount',halign:'center'" width="80" align="right">This Month <br/>Invoice Amount</th>
        </tr> 
    </thead>
  </table>
</div>

{{-- Target Month --}}
<div id="capacitysewingqtyMonthWindow" class="easyui-window" title="Sewing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <table id="capacitysewingqtyMonthTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'company_id',halign:'center'" width="80">1</th>
                <th data-options="field:'pcompany',halign:'center'" width="80">2</th>
                <th data-options="field:'buyer_code',halign:'center'" width="80">3</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">4</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">5</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">6</th>
                <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">7</th>
                <th data-options="field:'order_qty',halign:'center'" width="80" align="right">8</th>
                <th data-options="field:'order_rate',halign:'center'" width="60" align="right">9</th>
                <th data-options="field:'order_amount',halign:'center'" width="80" align="right">10</th>
                <th data-options="field:'last_month_shipout',halign:'center'" width="80" align="right">11</th>
                <th data-options="field:'last_month_shipout',halign:'center'" width="80" align="right">12</th>
                <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">13</th>
                <th data-options="field:'yet_to_ship',halign:'center'" width="80" align="right">14</th>
                <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">15<br/>Last Month</th>
                <th data-options="field:'tgt_this_month',halign:'center'" width="80" align="right">16</th>
                <th data-options="field:'tgt_value',halign:'center'" width="80" align="right">17</th>    
            </tr> 
            <tr>
                <th data-options="field:'company_id',halign:'center'" width="80">Beneficiary<br/> Company</th>
                <th data-options="field:'pcompany',halign:'center'" width="80">Producing <br/>Company</th>
                <th data-options="field:'buyer_code',halign:'center'" width="80">Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
                <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
                <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
                <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
                <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
                <th data-options="field:'last_month_shipout',halign:'center'" width="80" align="right">Asof Last<br/> Month Shipout</th>
                <th data-options="field:'last_month_shipout',halign:'center'" width="80" align="right">Asof This<br/> Month Shipout</th>
                <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">Total<br/> Ship Out</th>
                <th data-options="field:'yet_to_ship',halign:'center'" width="80" align="right">Yet To Ship</th>
                <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">Sewing Qty <br/>Last Month</th>
                <th data-options="field:'tgt_this_month',halign:'center'" width="80" align="right">Total Tgt<br/> This Month</th>
                <th data-options="field:'tgt_value',halign:'center'" width="80" align="right">Tgt Value</th>    
            </tr> 
        </thead>
    </table>
</div>

<div id="prodfabriccapacityachievementWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
    <div id="prodfabriccapacityachievementContainer" style="width:100%;height:100%;padding:0px;"></div>
</div>
<div id="prodFabricKnitTodayTergetWindow" class="easyui-window" title="Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricKnitTodayTergetPage" style="width:100%;height:100%;padding:0px;">
        dddd
    </div>
</div>
{{-- Today Achievement Row 8 --}}
<div id="prodFabricTodayAchieveRcvYarnWindow" class="easyui-window" title="Today Achievement Received Yarn
 Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricTodayAchieveRcvYarnPage" style="width:100%;height:100%;padding:0px;">
        Today Achievement Receive Yarn
    </div>
</div>
<div id="prodFabricTodayAchieveKnitYarnIssueWindow" class="easyui-window" title="Today Achievement Yarn Issue to Knitting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricTodayAchieveKnitYarnIssuePage" style="width:100%;height:100%;padding:0px;">
        Today Achievement Knit Yarn
    </div>
</div>
<div id="prodFabricTodayAchieveDyeingWindow" class="easyui-window" title="Today Achievement Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricTodayAchieveDyeingPage" style="width:100%;height:100%;padding:0px;">
        Today Achievement Dyeing
    </div>
</div>
<div id="prodFabricTodayAchieveAopWindow" class="easyui-window" title="Today Achievement Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricTodayAchieveAopPage" style="width:100%;height:100%;padding:0px;">
        Today Achievement
    </div>
</div>
<div id="prodFabricKnitTodayAchievementWindow" class="easyui-window" title="Today Achievement Knitting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricKnitTodayAchievePage" style="width:100%;height:100%;padding:0px;">
        Today Achievement
    </div>
</div>
{{-- Month Achievement Row 17--}}
<div id="prodFabricMonthAchieveRcvYarnWindow" class="easyui-window" title="Month Achievement Yarn Receive Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricMonthAchieveRcvYarnPage" style="width:100%;height:100%;padding:0px;">
        Month Achievement Receive Yarn
    </div>
</div>
<div id="prodFabricMonthAchieveKnitYarnIssueWindow" class="easyui-window" title="Month Achievement Yarn Issue Knitting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricMonthAchieveKnitYarnIssuePage" style="width:100%;height:100%;padding:0px;">
        Month Achievement Knit Yarn Issue
    </div>
</div>
<div id="prodFabricMonthAchieveKnittingWindow" class="easyui-window" title="Month Achievement Knitting Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <div id="prodFabricMonthAchieveKnittingPage" style="width:100%;height:100%;padding:0px;">
        Month Achievement Knit 
    </div>
</div>
{{-- 
<div id="capacityfinishingqtyMonthWindow" class="easyui-window" title="Sewing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <table id="capacityfinishingqtyMonthTbl" style="width:100%">
        <thead>
            <tr>
            <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
            <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
            <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
            <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
            <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
            <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
            <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
            <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
            <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
            <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
            <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">This Month <br/> Sewing Qty</th>
            <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">Sewing Qty Upto <br/>Last Month</th>
            <th data-options="field:'total_sewing',halign:'center'" width="80" align="right">Total <br/>Sewing Qty </th>
            <th data-options="field:'yet_sewing',halign:'center'" width="80" align="right">Yet To <br/>Sewing Qty</th>
            <th data-options="field:'sewing_amount',halign:'center'" width="80" align="right">This Month <br/> Sewing Amount</th>
            </tr> 
        </thead>
    </table>
</div>

<div id="capacityexfactoryqtyMonthWindow" class="easyui-window" title="Sewing Details" data-options="modal:true,closed:true" style="width:100%;height:100%;padding:2px;">
    <table id="capacityexfactoryqtyMonthTbl" style="width:100%">
        <thead>
            <tr>
                <th data-options="field:'company_id',halign:'center'">Beneficiary<br/> Company</th>
                <th data-options="field:'pcompany',halign:'center'" width="100">Producing <br/>Company</th>
                <th data-options="field:'buyer_code',halign:'center'" width="100">Buyer</th>
                <th data-options="field:'style_ref',halign:'center'" width="80">Style Ref</th>
                <th data-options="field:'sale_order_no',halign:'center'" width="80">Order No</th>
                <th data-options="field:'ship_date',halign:'center'" width="80">Ship Date</th>
                <th data-options="field:'flie_src',halign:'center'" width="40" formatter="MsProdGmtCapacityAchievement.formatimage">Image</th>
                <th data-options="field:'order_qty',halign:'center'" width="80" align="right">Order Qty</th>
                <th data-options="field:'order_rate',halign:'center'" width="60" align="right">Rate</th>
                <th data-options="field:'order_amount',halign:'center'" width="80" align="right">Selling Value</th>
                <th data-options="field:'sewing_qty',halign:'center'" width="80" align="right">This Month <br/> Sewing Qty</th>
                <th data-options="field:'sewingyesterday_qty',halign:'center'" width="80" align="right">Sewing Qty Upto <br/>Last Month</th>
                <th data-options="field:'total_sewing',halign:'center'" width="80" align="right">Total <br/>Sewing Qty </th>
                <th data-options="field:'yet_sewing',halign:'center'" width="80" align="right">Yet To <br/>Sewing Qty</th>
                <th data-options="field:'sewing_amount',halign:'center'" width="80" align="right">This Month <br/> Sewing Amount</th>
            </tr> 
        </thead>
    </table>
</div>
 --}}






<script type="text/javascript" src="<?php echo url('/');?>/js/Dashbord/MsDashBordController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/jquery.rwdImageMaps.min.js"></script>

<script>
    $(".datepicker" ).datepicker({
    beforeShow:function(input) {
    $(input).css({
    "position": "relative",
    "z-index": 999999
    });
    },
    dateFormat: 'yy-mm-dd',
    changeMonth: true,
    changeYear: true
    });

    $(document).ready(function(e) {
        $('img[usemap]').rwdImageMaps();
    });
</script>

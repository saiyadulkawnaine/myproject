

<div class="flex-container" style="height: 70%">
  <div>
        
      <div class="row middle">
      <div class="col-sm-8">BEP/Cost</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($lineBep,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">CM Earned</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($totProdCm,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Profit/Loss</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($totProdCm-$lineBep,0)}}
      </div>
      </div>
  </div>
  <div>
      <div class="row middle">
      <div class="col-sm-8">Target CM/Day</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($tgtCmPerDay,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Target CM/Hour</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($tgtCmPerHour,0)}}
      </div>
      </div>
     
      <div class="row middle">
      <div class="col-sm-8">Remaining Hour</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
        {{number_format($remaimingHour,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Achivement</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
            {{number_format($achivement,0)}} %
      </div>
      </div>
  </div>
  <div>
      
      <div class="row middle">
      <div class="col-sm-8">Efficiency</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
        {{number_format($efficiency,0)}} %
      
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Rejection</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
            {{number_format($rejection,0)}} %
      </div>
      </div>
       <div class="row middle">
      <div class="col-sm-8">Alter</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
            {{number_format($alter,0)}} %
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Production Yield</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
            {{number_format($productionYield,0)}} %
      </div>
      </div>
  </div>  
</div>
<div class="flex-container" style="height: 30%" >
  <div style="width: 50%">Heightest Efficient Line</div>
  <div style="width: 50%">Lowest Efficient Line</div>
</div>

<!-- <div id="container">
      <div id="body">
            <code>
                  <div class="row middle" style="font-size: 16px; font-weight: bold;">
                  <div class="col-sm-2">Line BEP</div>
                  <div class="col-sm-2" align="right" style="color: #ff0000">
                  {{number_format($lineBep,0)}}
                  </div>
                  <div class="col-sm-2">Traget CM/Hour</div>
                  <div class="col-sm-2" align="right" style="color: #ff0000">
                      {{number_format($tgtCmPerHour,0)}}  
                  </div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                  </div>
                  </div>

                  <div class="row middle" style="font-size: 16px; font-weight: bold;">
                  <div class="col-sm-2">CM Earned</div>
                  <div class="col-sm-2" align="right" style="color: #ff0000">
                  {{number_format($totProdCm,0)}}
                  </div>
                  <div class="col-sm-2">Traget CM/Day</div>
                  <div class="col-sm-2" align="right" style="color: #ff0000">
                        {{number_format($tgtCmPerDay,0)}} 
                  </div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                  </div>
                  </div>

                  <div class="row middle" style="font-size: 16px; font-weight: bold;">
                  <div class="col-sm-2">Profit/Loss</div>
                  <div class="col-sm-2" align="right" style="color: #ff0000">
                  {{number_format($totProdCm-$lineBep,0)}}
                  </div>
                  <div class="col-sm-2">Remaining Hour</div>
                  <div class="col-sm-2">
                        
                  </div>
                  <div class="col-sm-2"></div>
                  <div class="col-sm-2">
                  </div>
                  </div>
            </code>
      </div>
</div>
 -->
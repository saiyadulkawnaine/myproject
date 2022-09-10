


  <div style="width:50% ; background-color: #021344; padding:10px; color: #ffffff;">
      <div class="row middle">
      <div class="col-sm-8">All M/C Capacity</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCapQtyTot,0)}}
      </div>
      </div>
       <div class="row middle">
      <div class="col-sm-8">Today Target</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayTgtQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Today Produced</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayProdQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Target Variance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayTgtQtyTot-$dayProdQtyTot,0)}}
      </div>
      </div>

      <div class="row middle">
      <div class="col-sm-8">Capacity Variance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCapQtyTot-$dayProdQtyTot,0)}}
      </div>
      </div>
  </div>

  <div style="width:50% ; background-color: #021344; padding:10px; color: #ffffff;">
      <div class="row middle">
      <div class="col-sm-8">Cap.Achivement</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(($dayProdQtyTot/$dayCapQtyTot)*100,0)}} %
      </div>
      </div>
       <div class="row middle">
      <div class="col-sm-8">Target Achivement</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(($dayProdQtyTot/$dayTgtQtyTot)*100,0)}} %
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Unused Capacity</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(100-(($dayProdQtyTot/$dayCapQtyTot)*100),0)}}%
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">No on M/C Erected</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCapMacTot,0)}}
      </div>
      </div>

      <div class="row middle">
      <div class="col-sm-8">Today M/C Used</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayTgtMacTot,0)}}
      </div>
      </div>
  </div>
   




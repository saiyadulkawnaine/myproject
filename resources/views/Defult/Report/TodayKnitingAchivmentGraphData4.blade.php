


  <div style="width:50% ; background-color: #021344; padding:10px; color: #ffffff;">
      <div class="row middle">
      <div class="col-sm-8">All M/C Capacity</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCapQtyTot,0)}}
      </div>
      </div>
       <div class="row middle">
      <div class="col-sm-8">Target</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monTgtQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Produced</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monProdQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Target Variance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monTgtQtyTot-$monProdQtyTot,0)}}
      </div>
      </div>

      <div class="row middle">
      <div class="col-sm-8">Capacity Variance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCapQtyTot-$monProdQtyTot,0)}}
      </div>
      </div>
  </div>

  <div style="width:50% ; background-color: #021344; padding:10px; color: #ffffff;">
      <div class="row middle">
      <div class="col-sm-8">Cap.Achivement</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(($monProdQtyTot/$monCapQtyTot)*100,0)}} %
      </div>
      </div>
       <div class="row middle">
      <div class="col-sm-8">Target Achivement</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(($monProdQtyTot/$monTgtQtyTot)*100,0)}} %
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Unused Capacity</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format(100-(($monProdQtyTot/$monCapQtyTot)*100),0)}}%
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">No on M/C Erected</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCapMacTot,0)}}
      </div>
      </div>

      <div class="row middle">
      <div class="col-sm-8">M/C Used</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
       {{number_format($monTgtMacTot,0)}}
      </div>
      </div>
  </div>
   




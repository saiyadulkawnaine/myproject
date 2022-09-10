


  <div style="width:100% ; background-color: #021344; padding:10px; color: #ffffff;margin-top: 20px ">
      <div class="row middle">
      <div class="col-sm-8">Target</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCusTgtQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Receive</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCusRcvQtyTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Delivery</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCusDlvQtyTot,0)}}
      </div>
      </div>

      <div class="row middle">
      <div class="col-sm-8">Grey Used</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCusDlvGreyQtyTot,0)}}
      </div>
      </div>
      
      <div class="row middle">
      <div class="col-sm-8">Balance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCusRcvQtyTot-$monCusDlvGreyQtyTot,0)}}
      </div>
      </div>
  </div>
   




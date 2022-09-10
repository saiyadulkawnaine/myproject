


  <div style="width:100% ; background-color: #021344; padding:10px; color: #ffffff;margin-top: 20px ">
      <div class="row middle">
      <div class="col-sm-8">Target</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCusTgtAmtTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Receive</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCusRcvAmtTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Delivery</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCusDlvAmtTot,0)}}
      </div>
      </div>
      
      <div class="row middle">
      <div class="col-sm-8">Balance</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($dayCusRcvAmtTot-$dayCusDlvAmtTot,0)}}
      </div>
      </div>
  </div>
   




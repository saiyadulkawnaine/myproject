


   <div style="width:100% ; height: 100%; background-color: #021344; padding:10px; color: #ffffff;">
      <div class="row middle">
      <div class="col-sm-8">All Machine Cost</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monCapAmtTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Actually Earned</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monProdAmtTot,0)}}
      </div>
      </div>
      <div class="row middle">
      <div class="col-sm-8">Profit/Loss</div>
      <div class="col-sm-4" align="right" style="color: #ff0000">
      {{number_format($monProdAmtTot-$monCapAmtTot,0)}}
      </div>
      </div>
</div>
  
   




<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="soembprintdlvtabs">
	<div title="Delivery" style="padding:2px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'center',border:true,title:'Delivery Reference List'" style="padding:2px">
				<table id="soembprintdlvTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="40">ID</th>
							<th data-options="field:'dlv_no'" width="80">Dlv. No</th>
							<th data-options="field:'company_name'" width="80">Company</th>
							<th data-options="field:'buyer_name'" width="70">Customer</th>
							<th data-options="field:'production_area_id'" width="70">Production Area</th>
							<th data-options="field:'currency_name'" width="80">Currency</th>
							<th data-options="field:'dlv_date'" width="80">Dlv Date</th>
							<th data-options="field:'driver_name'" width="100">Driver Name</th>
							<th data-options="field:'driver_contact_no'" width="120">Driver Contact</th>
							<th data-options="field:'driver_license_no'" width="120">Driver License No</th>
							<th data-options="field:'lock_no'" width="80">Lock No</th>
							<th data-options="field:'truck_no'" width="100">Truck No</th>
							<th data-options="field:'remarks'" width="200">Remarks</th>
						</tr>
					</thead>
				</table>
			</div>
			<div data-options="region:'west',border:true,title:'Delivery Reference',footer:'#soembprintdlvFrmft'"
				style="width: 350px; padding:2px">
				<form id="soembprintdlvFrm">
					<div id="container">
						<div id="body">
							<code>
        <div class="row middle">
          <div class="col-sm-5">Dlv. No</div>
          <div class="col-sm-7">
           <input type="text" name="dlv_no" id="dlv_no" readonly />
           <input type="hidden" name="id" id="id">
          </div>
         </div>
							<div class="row middle">
								<div class="col-sm-5 req-text">Company</div>
								<div class="col-sm-7">
									{!! Form::select('company_id',$company,'',array('id'=>'company_id')) !!}
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5 req-text">Customer</div>
								<div class="col-sm-7">
									{!! Form::select('buyer_id',$buyer,'',array('id'=>'buyer_id','style'=>'width: 100%;
									border-radius:2px')) !!}
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5 req-text areaCon">Production Area</div>
								<div class="col-sm-7">
									{!!
									Form::select('production_area_id',$productionarea,'',array('id'=>'production_area_id'))
									!!}
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5 req-text">Currency </div>
								<div class="col-sm-7">
									{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5 req-text">Dlv Date</div>
								<div class="col-sm-7">
									<input type="text" name="dlv_date" id="dlv_date" class="datepicker"
										placeholder="yyyy-mm-dd" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Driver Name </div>
								<div class="col-sm-7">
									<input type="text" name="driver_name" id="driver_name" value="" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Driver Contact</div>
								<div class="col-sm-7">
									<input type="text" name="driver_contact_no" id="driver_contact_no"
										placeholder="(+88) 01234567890" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Driver License No</div>
								<div class="col-sm-7">
									<input type="text" name="driver_license_no" id="driver_license_no" value="" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Truck No</div>
								<div class="col-sm-7">
									<input type="text" name="truck_no" id="truck_no" value="" placeholder=" write" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Lock No</div>
								<div class="col-sm-7">
									<input type="text" name="lock_no" id="lock_no" value="" />
								</div>
							</div>
							<div class="row middle">
								<div class="col-sm-5">Remarks</div>
								<div class="col-sm-7">
									<textarea name="remarks" id="remarks"></textarea>
								</div>
							</div>
							</code>
						</div>
					</div>
					<div id="soembprintdlvFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
							iconCls="icon-save" plain="true" id="save" onClick="MsSoEmbPrintDlv.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="msApp.resetForm('soembprintdlvFrm')">Reset</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete" onClick="MsSoEmbPrintDlv.remove()">Delete</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-pdf" plain="true" id="cfdc" onClick="MsSoEmbPrintDlv.pdf()">BILL</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-pdf" plain="true" id="cfdc" onClick="MsSoEmbPrintDlv.challan()">Challan</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div title="Delivery Item Details" style="padding:2px">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'west',border:true,title:'Item Delivery Information',iconCls:'icon-more',footer:'#soembprintdlvitemFrmft'"
				style="width:350px; padding:2px">
				<form id="soembprintdlvitemFrm">
					<div id="container">
						<div id="body">
							<code>
		                                <div class="row">
		                                    <input type="hidden" name="id" id="id" value="" />
                                      <input type="hidden" name="so_emb_print_dlv_id" id="so_emb_print_dlv_id" value="" />
		                                </div>
																																		<div class="row middle">
																																			<div class="col-sm-4 req-text">Gmt Parts </div>
																																			<div class="col-sm-8">
																																				<input type="text" name="gmtspart" id="gmtspart" placeholder="Browse"
																																					ondblclick="MsSoEmbPrintDlvItem.openProdEmbPrintOpen()">
																																					<input type="hidden" name="so_emb_cutpanel_rcv_qty_id" id="so_emb_cutpanel_rcv_qty_id">
																																			</div>
																																		</div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Emb. Name </div>
		                                    <div class="col-sm-8">
																																										<input type="text" name="emb_name" id="emb_name" disabled>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Emb. Type </div>
		                                    <div class="col-sm-8">
																																										<input type="text" name="emb_type" id="emb_type" disabled>
		                                    </div>
		                                </div>
		                                 <div class="row middle">
		                                    <div class="col-sm-4 req-text">Emb. Size </div>
		                                    <div class="col-sm-8">
																																										<input type="text" name="emb_size" id="emb_size" disabled>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">GMT. Item </div>
		                                    <div class="col-sm-8">
																																											<input type="text" name="item_desc" id="item_desc" disabled>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">GMT. Color</div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="gmt_color" id="gmt_color" disabled/>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">GMT. Size </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="gmt_size" id="gmt_size" value="" disabled/>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">UOM </div>
		                                    <div class="col-sm-8">
																																										<input type="text" name="uom_name" id="uom_name" disabled>
		                                    </div> 
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Dlv Qty  </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="dlv_qty" id="dlv_qty" value="" class="number integer" onchange="MsSoEmbPrintDlvItem.calculate()" />
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Rate/Unit </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="rate" id="rate" value="" class="number integer" readonly/>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Add. Charge</div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="additional_charge" id="additional_charge" value="" onchange="MsSoEmbPrintDlvItem.calculate()" class="number integer"/>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4 req-text">Order Value </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="amount" id="amount" value="" class="number integer" readonly/>
		                                    </div>
		                                </div>
		                                <div class="row middle">
		                                    <div class="col-sm-4">Delivery Point </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="delivery_point" id="delivery_point" value="" />
		                                    </div>
		                                </div> 
		                                <div class="row middle">
		                                    <div class="col-sm-4">Buyer </div>
		                                    <div class="col-sm-8">
																																										<input type="text" name="buyer_name" id="buyer_name" disabled>
		                                    </div>
		                                </div> 
		                                <div class="row middle">
		                                    <div class="col-sm-4">Style Ref </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="style_ref" id="style_ref" value="" disabled/>
		                                    </div>
		                                </div> 
		                                <div class="row middle">
		                                    <div class="col-sm-4">Sales Order No </div>
		                                    <div class="col-sm-8">
		                                        <input type="text" name="sale_order_no" id="sale_order_no" value="" disabled/>
		                                    </div>
		                                </div>   
		       </code>
						</div>
					</div>
					<div id="soembprintdlvitemFrmft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
						<a href="javascript:void(0)" class="easyui-linkbutton  c1"
							style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save"
							onClick="MsSoEmbPrintDlvItem.submit()">Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsSoEmbPrintDlvItem.resetForm()">Reset</a>
						<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"
							iconCls="icon-remove" plain="true" id="delete"
							onClick="MsSoEmbPrintDlvItem.remove()">Delete</a>
					</div>
				</form>
			</div>
			<div data-options="region:'center',border:true,title:'Item Delivery Lists'" style="padding:2px">
				<table id="soembprintdlvitemTbl" style="width:100%">
					<thead>
						<tr>
							<th data-options="field:'id'" width="30">ID</th>
							<th data-options="field:'sale_order_no'" width="100">PSO</th>
							<th data-options="field:'emb_name'" width="100">Emb. Name</th>
							<th data-options="field:'emb_type'" width="100">Emb. Type</th>
							<th data-options="field:'emb_size'" width="80">Emb. Size</th>
							<th data-options="field:'design_no'" width="80">Design No</th>
							<th data-options="field:'item_desc'" width="100">GMT Item</th>
							<th data-options="field:'gmtspart'" width="100">GMT Part</th>
							<th data-options="field:'gmt_color'" width="100">GMT Color</th>
							<th data-options="field:'gmt_size'" width="80">GMT Size</th>
							<th data-options="field:'buyer_name'" width="100">Buyer</th>
							<th data-options="field:'style_ref'" width="100">Style Ref</th>
							<th data-options="field:'uom_name'" width="40">Uom</th>
							<th data-options="field:'dlv_qty'" width="80">Dlv Qty</th>
							<th data-options="field:'rate'" width="60">Rate</th>
							<th data-options="field:'additional_charge'" width="60">Additional Charge</th>
							<th data-options="field:'amount'" width="80">Amount</th>
							<th data-options="field:'delivery_point'" width="80">Delivery Point</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

{{-- Delivery window --}}
<div id="soembprintdlvitemWindow" class="easyui-window" title="Sales Order No Search Window"
	data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:100%;height:100%;padding:2px;">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'west',split:true, title:'Search'" style="width:350px;height:130px">
			<div class="easyui-layout" data-options="fit:true">
				<div id="body">
					<code>
                        <form id="soembprintsearchFrm">
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Order No</div>
                                <div class="col-sm-8">
                                    <input type="text" name="sale_order_no" id="sale_order_no" value="" />
                                </div>
                            </div>
                        </form>
                    </code>
				</div>
				<p class="footer">
					<a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px"
						onClick="MsSoEmbPrintDlvItem.searchProdEmbItem()">Search</a>
				</p>
			</div>
		</div>
		<div data-options="region:'center'" style="padding:10px;">
			<table id="soembprintsearchTbl" style="width:100%">
				<thead>
					<tr>
						<th data-options="field:'id'" width="30">ID</th>
						<th data-options="field:'buyer_name'" width="100">Buyer</th>
						<th data-options="field:'style_ref'" width="100">Style Ref</th>
						<th data-options="field:'sale_order_no'" width="100">Sales Order No</th>
						<th data-options="field:'emb_name'" width="100">Emb. Name</th>
						<th data-options="field:'emb_type'" width="100">Emb. Type</th>
						<th data-options="field:'emb_size'" width="80">Emb. Size</th>
						<th data-options="field:'design_no'" width="80">Design No</th>
						<th data-options="field:'item_desc'" width="100">GMT Item</th>
						<th data-options="field:'gmtspart'" width="100">GMT Part</th>
						<th data-options="field:'gmt_color'" width="100">GMT Color</th>
						<th data-options="field:'gmt_size'" width="80">GMT Size</th>
						<th data-options="field:'qty'" width="80" align="right">Qty</th>
						<th data-options="field:'uom_name'" width="40">Uom</th>

						<th data-options="field:'rate'" width="60" align="right">Rate</th>
						<th data-options="field:'amount'" width="80" align="right">Amount</th>
						<th data-options="field:'delivery_date'" width="80">Delivery Date</th>
					</tr>
				</thead>
			</table>
		</div>
		<div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
			<a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)"
				onclick="$('#soembprintdlvitemWindow').window('close')" style="width:80px">Close</a>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Subcontract/Embelishment/MsAllSoEmbPrintDlvController.js">
</script>
<script>
	(function(){
      $(".datepicker").datepicker({
         beforeShow:function(input) {
               $(input).css({
                  "position": "relative",
                  "z-index": 999999
               });
         },
         dateFormat: 'yy-mm-dd',
         changeMonth: true,
         changeYear: true,
      });
      $('.integer').keyup(function () {
         if (this.value != this.value.replace(/[^0-9\.]/g, '')) 
         {
            this.value = this.value.replace(/[^0-9\.]/g, '');
         }
      });
      $('#soembprintdlvFrm [id="buyer_id"]').combobox();
   })(jQuery);
</script>
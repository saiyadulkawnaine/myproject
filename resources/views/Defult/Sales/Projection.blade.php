<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="projectiontabs">
    <div title="Projection" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout "  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="projectionTbl" style="width:100%">
                <thead>
                <tr>
                <th data-options="field:'id'" width="50">ID</th>
                <th data-options="field:'proj_no'" width="80">Proj. No</th>
                <th data-options="field:'company'" width="80">Company</th>
                <th data-options="field:'buyer'" width="210">Buyer</th>
                <th data-options="field:'style_ref'" width="100">Style Ref.</th>
                <th data-options="field:'date'" width="100">Date</th>
                </tr>
                </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Projection',footer:'#ft2'" style="width:350px; padding:2px">
                <form id="projectionFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row">
                                <div class="col-sm-4 req-text" >Proj No </div>
                                <div class="col-sm-8">
                                <input type="text" name="proj_no" id="proj_no"  />
                                <input type="hidden" name="id" id="id" value=""/>
                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Company:</div>
                                <div class="col-sm-8">
                                {!! Form::select('company_id', $company,'',array('id'=>'company_id')) !!}
                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Style </div>
                                <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" placeholder="Double Click"  onDblClick="MsProjection.openStyleWindow()" readonly />
                                <input type="hidden" name="style_id" id="style_id" />
                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Buyer: </div>
                                <div class="col-sm-8">{!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Uom</div>
                                <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Season</div>
                                <div class="col-sm-8">{!! Form::select('season_id', $season,'',array('id'=>'season_id','disabled'=>'disabled')) !!}</div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Date:</div>
                                <div class="col-sm-8"><input type="text" name="date" id="date" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Currency</div>
                                <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Exch. Rate</div>
                                <div class="col-sm-8"><input type="text" name="exch_rate" id="exch_rate" class="number integer" /></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">File No</div>
                                <div class="col-sm-8"><input type="text" name="file_no" id="file_no" /></div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4">Remarks </div>
                                <div class="col-sm-8"><textarea name="remarks" id="remarks" rows="3"></textarea></div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProjection.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('projectionFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProjection.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Countries" style="padding:1px">
        <div class="easyui-layout "  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#ftprojectionqty'" style="padding:2px">
              <form id="projectionqtyFrm">
              <input type="hidden" name="projection_country_id" id="projection_country_id" value=""/>
                 <code id="gmtmatrix">
                 </code>
                    <div id="ftprojectionqty" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProjectionQty.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('projectionqtyFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProjectionQty.remove()" >Delete</a>
                    </div>
              </form>
            </div>
            <div data-options="region:'west',border:true," style="width:350px; padding:2px">
                <div class="easyui-layout" data-options="fit:true">
                    <div data-options="region:'north',border:true, title:'Countries',footer:'#ftprojectioncountry'" style="height:275px; padding:2px">
                        <form id="projectioncountryFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row ">
                                        <div class="col-sm-4 req-text">Proj No</div>
                                        <div class="col-sm-8">
                                        <input type="text" name="proj_no" id="proj_no" disabled/>
                                        <input type="hidden" name="id" id="id" value=""/>
                                        <input type="hidden" name="projection_id" id="projection_id" value=""/>
                                        </div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-4 req-text">Country</div>
                                        <div class="col-sm-8">
                                        {!! Form::select('country_id', $country,'',array('id'=>'country_id')) !!}
                                        </div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-4 req-text">Cut Off Date</div>
                                        <div class="col-sm-8"><input type="text" name="cut_off_date" id="cut_off_date" class="datepicker" /></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-4">Cut Off </div>
                                        <div class="col-sm-8">{!! Form::select('cut_off', $cutoff,'',array('id'=>'cut_off','onchange'=>'MsProjectionCountry.setCutOffDate(this.value)')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-4 req-text">Ship Date</div>
                                        <div class="col-sm-8"><input type="text" name="country_ship_date" id="country_ship_date" class="datepicker" /></div>
                                        </div>
                                        <div class="row middle">
                                        <div class="col-sm-4">Remarks</div>
                                        <div class="col-sm-8">
                                        <textarea name="remarks" id="remarks" rows="3"></textarea>
                                        </div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                        <div id="ftprojectioncountry" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsProjectionCountry.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('projectioncountryFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsProjectionCountry.remove()" >Delete</a>
                        </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                        <table id="projectioncountryTbl" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-options="field:'id'" width="50">ID</th>
                                    <th data-options="field:'country'" width="80">Country</th>
                                    <th data-options="field:'qty'" width="80" align="right">Qty</th>
                                    <th data-options="field:'rate'" width="80" align="right">Rate</th>
                                    <th data-options="field:'amount'" width="100" align="right">Amount</th>
                                    <th data-options="field:'cut_off_date'" width="100">Cut Off Date</th>
                                    <th data-options="field:'cut_off'" width="100">Cut Off </th>
                                    <th data-options="field:'shipdate'" width="100">Ship Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="w" class="easyui-window" title="Style" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="stylesearch">
                        <div class="row">
                        <div class="col-sm-2">Buyer</div>
                        <div class="col-sm-4">
                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                        </div>
                        <div class="col-sm-2 req-text">Style Ref. </div>
                        <div class="col-sm-4"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                        </div>
                        <div class="row middle">
                        <div class="col-sm-2">Style Des.  </div>
                        <div class="col-sm-4"><input type="text" name="style_description" id="style_description" value=""/></div>
                        </div>
                        </form>
                    </code>
                </div>
                <p class="footer">
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsProjection.showStyleGrid()">Search</a>
                </p>
            </div>
        </div>
    <div data-options="region:'center'" style="padding:10px;">
        <table id="styleTbl" style="width:610px">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="50">ID</th>
                    <th data-options="field:'buyer'" width="70">Buyer</th>
                    <th data-options="field:'receivedate'" width="80">Receive Date</th>
                    <th data-options="field:'style_ref'" width="100">Style Refference</th>
                    <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                    <th data-options="field:'productdepartment'" width="80">Product Department</th>
                    <th data-options="field:'season'" width="80">Season</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
        <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#w').window('close')" style="width:80px">Close</a>
    </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProjectionController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProjectionCountryController.js"></script>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsProjectionQtyController.js"></script>


<script>
(function(){
    $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
    });
    $('.integer').keyup(function () {
            if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
            this.value = this.value.replace(/[^0-9\.]/g, '');
            }
    });
    $('#stylesearch [id="buyer_id"]').combobox();
  })(jQuery);
</script>

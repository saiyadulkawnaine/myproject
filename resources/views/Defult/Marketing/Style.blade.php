<div class="easyui-tabs" style="width:100%;height:100%; border:none" id="styletabs">
    
    <div title="Style" style="padding:1px" data-options="selected:true">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="styleTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'buyer'" width="100">Buyer</th>
                            <th data-options="field:'buying_agent_id'" width="100">Buying Agent</th>
                            <th data-options="field:'receivedate'" width="100">Receive Date</th>
                            <th data-options="field:'style_ref'" width="100">Style Refference</th>
                            <th data-options="field:'style_description'" width="150">Style Description</th>
                            <th data-options="field:'deptcategory'" width="100">Dept. Category</th>
                            <th data-options="field:'productdepartment'" width="120">Product Department</th>
                            <th data-options="field:'season'" width="100">Season</th>
                            <th data-options="field:'uom'" width="60">Uom</th>
                            <th data-options="field:'team'" width="80">Team</th>
                            <th data-options="field:'teammember'" width="80">Team Member</th>
                            <th data-options="field:'contact'" width="100">Contact</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New Style',iconCls:'icon-more',footer:'#ft2'" style="width:380px; padding:2px">
                <form id="styleFrm">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Style ID</div>
                                    <div class="col-sm-7">
                                        <input type="text" name="id" id="id" value="" readonly ondblclick="MsStyle.openStyleWindow()" placeholder="double click" />
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Buyer</div>
                                    <div class="col-sm-7">
                                        {!! Form::select('buyer_id', $buyer,'',array('id'=>'buyer_id','style'=>'width: 100%; border-radius:2px')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Receive Date </div>
                                    <div class="col-sm-7"><input type="text" name="receive_date" id="receive_date" value="" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Style Refference</div>
                                    <div class="col-sm-7"><input type="text" name="style_ref" id="style_ref" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Style Description</div>
                                    <div class="col-sm-7"><input type="text" name="style_description" id="style_description" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Dept. Category</div>
                                    <div class="col-sm-7">{!! Form::select('dept_category_id', $deptcategory,'',array('id'=>'dept_category_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Product Department</div>
                                    <div class="col-sm-7">{!! Form::select('productdepartment_id', $productdepartment,'',array('id'=>'productdepartment_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Product Code</div>
                                    <div class="col-sm-7"><input type="text" name="product_code" id="product_code" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Offer Qty</div>
                                    <div class="col-sm-7"><input type="text" name="offer_qty" id="offer_qty" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Ship Date</div>
                                    <div class="col-sm-7"><input type="text" name="ship_date" id="ship_date" value="" class="datepicker"/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Season</div>
                                    <div class="col-sm-7">{!! Form::select('season_id', $season,'',array('id'=>'season_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Uom</div>
                                    <div class="col-sm-7">{!! Form::select('uom_id', $uom,1,array('id'=>'uom_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Team</div>
                                    <div class="col-sm-7">{!! Form::select('team_id', $team,'',array('id'=>'team_id','onchange'=>'MsStyle.getTeamMember(this.value)')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5 req-text">Marketing Member</div>
                                    <div class="col-sm-7">{!! Form::select('teammember_id', $teammember,'',array('id'=>'teammember_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buyer Ref</div>
                                    <div class="col-sm-7"><input type="text" name="buyer_ref" id="buyer_ref" value=""/></div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Factory Merchant</div>
                                    <div class="col-sm-7">{!! Form::select('factory_merchant_id', $teammember,'',array('id'=>'factory_merchant_id')) !!}</div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Buying Agent</div>
                                    <div class="col-sm-7">{!! Form::select('buying_agent_id', $buyinghouses,'',array('id'=>'buying_agent_id')) !!}
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Remarks</div>
                                    <div class="col-sm-7">
                                        <textarea name="remarks" id="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row middle">
                                    <div class="col-sm-5">Contact</div>
                                    <div class="col-sm-7"><input type="text" name="contact" id="contact">
                                    </div>
                                </div>
                            </code>
                        </div>
                    </div>
                    <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyle.submit()">Save</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('styleFrm')" >Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyle.remove()" >Delete</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div title="GMT Color" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylecolorTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style'" width="100">Style</th>
    <th data-options="field:'name'" width="100">Color</th>
    <th data-options="field:'sort'" width="70" align="right">Sequence</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StyleColor',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft5'" style="width:350px; padding:2px">
    <form id="stylecolorFrm">
    <div id="container">
     <div id="body">
       <code>
        <div class="row">
            <div class="col-sm-4 req-text">Style</div>
            <div class="col-sm-8">
            <input type="text" name="style_ref" id="style_ref" readonly/>
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="style_id" id="style_id" value=""/>
            </div>
        </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Color</div>
             <div class="col-sm-8">
             <input type="text" name="color_id" id="color_id" value=""/>
             </div>
          </div>
          <div class="row middle">
             <div class="col-sm-4">Color Code  </div>
             <div class="col-sm-8"><input type="text" name="color_code" id="color_code" value=""/></div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Sequence  </div>
             <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
         </div>
      </code>
    </div>
    </div>
    <div id="ft5" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleColor.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylecolorFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleColor.remove()" >Delete</a>
    </div>

    </form>
    </div>
    </div>

    </div>
    <div title="GMT Sizes" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylesizeTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style_ref'" width="100">Style</th>
    <th data-options="field:'name'" width="100">Size</th>
    <th data-options="field:'sort'" width="70" align="right">Sequence</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StyleSize',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft6'" style="width:350px; padding:2px">
    <form id="stylesizeFrm">
    <div id="container">
     <div id="body">
       <code>
         <div class="row">
            <div class="col-sm-4 req-text">Style</div>
            <div class="col-sm-8">
            <input type="text" name="style_ref" id="style_ref" readonly/>
             <input type="hidden" name="id" id="id" value=""/>
             <input type="hidden" name="style_id" id="style_id" value=""/>
            </div>
        </div>

         <div class="row middle">
             <div class="col-sm-4 req-text">Size</div>
             <div class="col-sm-8">
             <input type="text" name="size_id" id="size_id" value=""/>
             </div>
             </div>
             <div class="row middle">
             <div class="col-sm-4">Size Code  </div>
             <div class="col-sm-8">
              <input type="text" name="size_code" id="size_code" value=""/>
             </div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Sequence  </div>
             <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
         </div>

      </code>
    </div>
    </div>
    <div id="ft6" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSize.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesizeFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSize.remove()" >Delete</a>
    </div>

    </form>
    </div>
    </div>

    </div>
    <div title="GMT Item" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'east',border:true,title:'Edit Color Size',footer:'#ftstylegmtcolorsizes2'" style="width:350px; padding:2px">
        <table id="stylegmtcolorsizeeditTbl" style="width:100%">
        </table>
        <div id="ftstylegmtcolorsizes2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <!--<a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleGmtColorSizes.remove()" >Delete</a>-->
        </div>
        
    </div>
    <div data-options="region:'center',border:true,title:'New Color Size',footer:'#ftstylegmtcolorsizes'" style="padding:2px">
        <form id="stylegmtcolorsizeFrm">
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="style_id" id="style_id" value=""/>
            <input type="hidden" name="style_gmt_id" id="style_gmt_id" value=""/>
            <table id="stylegmtcolorsizeTbl" style="width:100%">
            </table>
        </form>
        <div id="ftstylegmtcolorsizes" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleGmtColorSizes.submit()">Save</a>
        </div>
        
    </div>
    <div data-options="region:'west',border:true" style="width:400px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'north',border:true,title:'Add New Style Gmts',footer:'#ft3'" style="width:400px; padding:2px">
    <form id="stylegmtsFrm">
    <div id="container">
     <div id="body">
       <code>
           <div class="row">
                <div class="col-sm-4 req-text">Style </div>
                <div class="col-sm-8">
                <input type="text" name="style_ref" id="style_ref" value=""/>
                <input type="hidden" name="id" id="id" value=""/>
                <input type="hidden" name="style_id" id="style_id" value=""/>
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Item Account </div>
                <div class="col-sm-8">
                {!! Form::select('item_account_id', $itemaccount,'',array('id'=>'item_account_id','onchange'=>'MsStyleGmts.setCategory(this.value)')) !!}

                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">Article </div>
                <div class="col-sm-8"><input type="text" name="article" id="article" value=""/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">GMT Qty Ratio </div>
                <div class="col-sm-8"><input type="text" name="gmt_qty" id="gmt_qty" class="number integer"/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Item Complexity </div>
                <div class="col-sm-8"> {!! Form::select('item_complexity', $itemcomplexity,'',array('id'=>'item_complexity')) !!}</div>
            </div>
            <div class="row middle">
                <div class="col-sm-4">Custom Category  </div>
                <div class="col-sm-8"><input type="text" name="custom_catg" id="custom_catg" value=""/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">GMT Category </div>
                <div class="col-sm-8">
                {!! Form::select('gmt_catg', $gmtcategory,'',array('id'=>'gmt_catg','disabled'=>'disabled')) !!}
                </div>
            </div>
            
            <div class="row middle">
                <div class="col-sm-4">Remarks</div>
                <div class="col-sm-8">
                    <textarea name="remarks" id="remarks"></textarea>
                </div>
            </div>
      </code>
    </div>
    </div>
    <div id="ft3" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleGmts.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylegmtsFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleGmts.remove()" >Delete</a>
    </div>

    </form>
    </div>
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylegmtsTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'itemaccount'" width="100">Item Account</th>
    <th data-options="field:'gmtqty'" width="70" align="right">GMT Qty Ratio</th>
    <th data-options="field:'itemcomplexity'" width="100">Item Complexity</th>
    <th data-options="field:'gmtcategory'" width="100">GMT Category</th>
    <th data-options="field:'created_by_user'" width="100">Entry By</th>
    <th data-options="field:'created_at'" width="100">Entry At</th>
    <th data-options="field:'updated_by_user'" width="100">Updated By</th>
    <th data-options="field:'updated_at'" width="100">Updated At</th>
    <th data-options="field:'article'" width="100">Article</th>
    <th data-options="field:'smv'" width="100">SMV</th>
    <th data-options="field:'sewing_effi_per'" width="100">Sewing Effi. %</th>
    <th data-options="field:'no_of_man_power'" width="100">No of Manpower </th>
    <th data-options="field:'prod_per_hour'" width="100">Prod/Hour</th>
    <th data-options="field:'remarks'" width="100">Remarks</th>
    </tr>
    </thead>
    </table>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div title="Embellishments" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="styleembelishmentTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="50">ID</th>
    <th data-options="field:'style'" width="100">Style</th>
    <th data-options="field:'stylegmts'" width="100">Gmts Item</th>
    <th data-options="field:'embelishment'" width="80">Emb. Name</th>
    <th data-options="field:'embelishmenttype'" width="80">Emb. Type</th>
    <th data-options="field:'embelishmentsize'" width="80">Emb. Size</th>
    <th data-options="field:'sort'" width="50" align="right">Sequence</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StyleEmbelishment',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft4'" style="width:350px; padding:2px">
    <form id="styleembelishmentFrm">
    <div id="container">
     <div id="body">
       <code>
            <div class="row">
                 <div class="col-sm-4 req-text">Style</div>
                 <div class="col-sm-8">
                 <input type="text" name="style_ref" id="style_ref" readonly/>
                 <input type="hidden" name="id" id="id" readonly/>
                 <input type="hidden" name="style_id" id="style_id" readonly/>
                 <input type="hidden" name="production_area_id" id="production_area_id" readonly/>
                 </div>
             </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Gmts. Item </div>
                <div class="col-sm-8">
                {!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}
                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Emb. Name </div>
                <div class="col-sm-8">
                {!! Form::select('embelishment_id', $embelishment,'',array('id'=>'embelishment_id','onChange'=>'MsStyleEmbelishment.embnameChange(this.value)')) !!}

                </div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Emb. Type </div>
                <div class="col-sm-8">{!! Form::select('embelishment_type_id', $embelishmenttype,'',array('id'=>'embelishment_type_id','style'=>'width:100%;border-radious:2px;')) !!}</div>
            </div>
            <div class="row middle">
             <div class="col-sm-4 req-text">Gmts Part </div>
             <div class="col-sm-8">{!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id','style'=>'width:100%;border-radious:2px;')) !!}</div>
         </div>
            <div class="row middle">
                <div class="col-sm-4 req-text" id ="style_emb_embelishment_size">Emb. Size </div>
                <div class="col-sm-8">{!! Form::select('embelishment_size_id', $embelishmentsize,'',array('id'=>'embelishment_size_id')) !!}</div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Sequence </div>
                <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
            </div>

      </code>
    </div>
    </div>
    <div id="ft4" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleEmbelishment.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('styleembelishmentFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleEmbelishment.remove()" >Delete</a>
    </div>

    </form>
    </div>
    </div>
    <!--<script>
    $j("select").searchable();
    </script>-->
    </div>
    <div title="GMT Fabrications" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true" style="padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'north',border:true,title:'Fabrication List',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false" style="height:320px; padding:2px">
    <table id="stylefabricationTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style'" width="100">Style</th>
    <th data-options="field:'stylegmts'" width="100">Style GMT</th>
    <th data-options="field:'fabricnature'" width="80">Fabric Nature</th>
    <th data-options="field:'gmtspart'" width="100">Gmts Part</th>
    <th data-options="field:'fabriclooks'" width="80">Fabric Look</th>
    <th data-options="field:'materialsourcing'" width="80">Material Source</th>
    <th data-options="field:'autoyarn'" width="180">Fabrication</th>
    <th data-options="field:'created_by'" width="80">Created By</th>
    <th data-options="field:'created_at'" width="80">Created At</th>
    <th data-options="field:'updated_by'" width="80">Updated By</th>
    <th data-options="field:'updated_at'" width="80">Updated At</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'center',border:true,title:'Stripe List'" style="padding:2px">
    <table id="stylefabricationstripeTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style_color'" width="100">Style Color</th>
    <th data-options="field:'color'" width="100">Color</th>
    
    <th data-options="field:'measurment'" width="100" align="right">Measurment</th>
    <th data-options="field:'feeder'" width="80" align="right">Feeder</th>
    <th data-options="field:'dyewash'" width="80" >Dye Not Req.</th>
    <th data-options="field:'created_by'" width="80">Created By</th>
    <th data-options="field:'created_at'" width="80">Created At</th>
    <th data-options="field:'updated_by'" width="80">Updated By</th>
    <th data-options="field:'updated_at'" width="80">Updated At</th>
    </tr>
    </thead>
    </table>
    </div>
    </div>
    </div>
    <div data-options="region:'west',border:true,iconCls:'icon-more',hideCollapsedContent:false,collapsed:false" style="width:350px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'north',border:true,title:'Add New Fabrication',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft7'" style="height:450px; padding:2px">

    <form id="stylefabricationFrm">
    <div id="container">
     <div id="body">
       <code>

         <div class="row">
             <div class="col-sm-4 req-text">Style</div>
             <div class="col-sm-8">
             <input type="text" name="style_ref" id="style_ref" readonly/>
             <input type="hidden" name="id" id="id" value=""/>
             <input type="hidden" name="style_id" id="style_id" value=""/>
             <input type="hidden" name="is_narrow" id="is_narrow" value="0"/>
             </div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Gmts. Item </div>
             <div class="col-sm-8">{!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Fabric Nature</div>
             <div class="col-sm-8">{!! Form::select('fabric_nature_id', $fabricnature,1,array('id'=>'fabric_nature_id')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Gmts Part </div>
             <div class="col-sm-8">{!! Form::select('gmtspart_id', $gmtspart,'',array('id'=>'gmtspart_id','onchange'=>'MsStyleFabrication.getGmtpartDetails(this.value)')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4">Fabrication </div>
             <div class="col-sm-8">
             <input type="text" name="fabrication" id="fabrication" onDblClick="MsStyleFabrication.openFabricationWindow()" placeholder="Duble click to search"/>
             <input type="hidden" name="autoyarn_id" id="autoyarn_id" readonly/>
             </div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Fabric Look </div>
             <div class="col-sm-8">{!! Form::select('fabric_look_id', $fabriclooks,'',array('id'=>'fabric_look_id','onchange'=>'MsStyleFabrication.fabricLookChange(this.value)')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">MTRL Source</div>
             <div class="col-sm-8">{!! Form::select('material_source_id', $materialsourcing,'',array('id'=>'material_source_id')) !!}</div>
         </div>
         <div class="row middle" style="display:none">
             <div class="col-sm-4">Yarn Count </div>
             <div class="col-sm-8">{!! Form::select('yarncount_id', $yarncount,'',array('id'=>'yarncount_id')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Fabric Shape</div>
             <div class="col-sm-8">{!! Form::select('fabric_shape_id', $fabricshape,'',array('id'=>'fabric_shape_id')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">UOM</div>
             <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
         </div>
          <div class="row middle">
                <div class="col-sm-4 req-text">Dyeing Type </div>
                <div class="col-sm-8">{!! Form::select('dyeing_type_id', $dyetype,'',array('id'=>'dyeing_type_id')) !!}</div>
            </div>
         <div class="row middle">
             <div class="col-sm-4">Is Stripe</div>
             <div class="col-sm-8">{!! Form::select('is_stripe', $yesno,'',array('id'=>'is_stripe')) !!}</div>
         </div>


      </code>
      <code>
      <div class="row middle">
        <div class="col-sm-4 req-text" id="stylefabricationaoptype">Aop Type </div>
        <div class="col-sm-8">{!! Form::select('embelishment_type_id', $aoptype,'',array('id'=>'embelishment_type_id')) !!}</div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text" id="stylefabricationaopcoverage">Coverage  </div>
        <div class="col-sm-8"><input type="text" name="coverage" id="coverage" class="integer number" value=""/></div>
        </div>
        <div class="row middle">
        <div class="col-sm-4 req-text" id="stylefabricationaopimpression">Impression  </div>
        <div class="col-sm-8"><input type="text" name="impression" id="impression" class="integer number" value=""/></div>
        </div>
      </code>
    </div>
    </div>
    <div id="ft7" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleFabrication.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFabrication.resetForm('stylefabricationFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFabrication.remove()" >Delete</a>
    </div>

    </form>

    </div>
    <div data-options="region:'center',border:true,title:'List',footer:'#ft71'" style="padding:2px">
    <form id="stylefabricationstripeFrm">
    <div id="container">
     <div id="body">
       <code>

    <div class="row ">
             <div class="col-sm-4">Style Color  </div>
             <div class="col-sm-8">{!! Form::select('style_color_id', $color,'',array('id'=>'style_color_id')) !!}</div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Yarn Color </div>
             <div class="col-sm-8">
                <input type="text" name="color_id" id="color_id" value=""/>
                <input type="hidden" name="id" id="id" value=""/>
                <input type="hidden" name="style_fabrication_id" id="style_fabrication_id" value=""/>
             </div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Measurment </div>
             <div class="col-sm-8"><input type="text" name="measurment" id="measurment" class="number integer"/></div>
         </div>
         <div class="row middle">
             <div class="col-sm-4 req-text">Feeder </div>
             <div class="col-sm-8"><input type="text" name="feeder" id="feeder" class="number integer"/></div>
         </div>
         <div class="row middle">
             <div class="col-sm-4">Dye Not Req.  </div>
             <div class="col-sm-8">{!! Form::select('is_dye_wash', $yesno,'',array('id'=>'is_dye_wash')) !!}</div>
         </div>

      </code>
    </div>
    </div>
    <div id="ft71" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleFabricationStripe.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylefabricationstripeFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFabricationStripe.remove()" >Delete</a>
    </div>

    </form>
    </div>
    </div>
    </div>
    </div>

    </div>
    <div title="Measurement" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylesizemsureTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style'" width="100">Style</th>
    <th data-options="field:'stylegmts'" width="100">Gmts. Item</th>
    <th data-options="field:'msurepoint'" width="100">Measure Point</th>
    <th data-options="field:'uom'" width="100">UOM</th>
    <th data-options="field:'tollerance'" width="70" align="right">Tollerance</th>
    <th data-options="field:'sort_id'" width="70" align="right">Sequence</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StyleSizeMsure',iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft8'" style="width:350px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'north',border:true,iconCls:'icon-more',hideCollapsedContent:false,footer:'#ft8'" style="height:195px; padding:2px">
    <form id="stylesizemsureFrm">
    <div id="container">
     <div id="body">
       <code>

        <div class="row">
             <div class="col-sm-4 req-text">Style</div>
             <div class="col-sm-8">
             <input type="text" name="style_ref" id="style_ref" readonly/>
             <input type="hidden" name="id" id="id" value=""/>
             <input type="hidden" name="style_id" id="style_id" value=""/>
             </div>
         </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Gmts Item </div>
                <div class="col-sm-8">
                {!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}
                </div>
                </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Msure Point </div>
                <div class="col-sm-8"><input type="text" name="msure_point" id="msure_point" value=""/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">UOM </div>
                <div class="col-sm-8">{!! Form::select('uom_id', $uom,'',array('id'=>'uom_id')) !!}</div>
                </div>
           <div class="row middle">
                <div class="col-sm-4 req-text">Tollerance </div>
                <div class="col-sm-8"><input type="text" name="tollerance" id="tollerance" class="number integer"/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Quantity </div>
                <div class="col-sm-8"><input type="text" name="qty" id="qty" class="number integer"/></div>
            </div>
            <div class="row middle">
                <div class="col-sm-4 req-text">Remarks</div>
                <div class="col-sm-8"><textarea id="remarks" name="remarks"></textarea></div>
            </div>
            <div class="row middle">
             <div class="col-sm-4">Sequence  </div>
             <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
         </div>
      </code>

    </div>
    </div>
    <div id="ft8" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSizeMsure.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesizemsureFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSizeMsure.remove()" >Delete</a>
    </div>
    </form>
    </div>
    <div data-options="region:'center',border:true,title:'List',footer:'#ft81'" style="padding:2px">
    <form id="stylesizemsurevalFrm">
    <div id="container">
     <div id="body">
        <input type="hidden" name="id" id="id" value=""/>
        <input type="hidden" name="style_id" id="style_id" value=""/>
        <input type="hidden" name="style_gmt_id" id="style_gmt_id" value=""/>
        <input type="hidden" name="style_size_msure_id" id="style_size_msure_id" value=""/>
      <code id="sizeMatrix">
      </code>
    </div>
    </div>
    <div id="ft81" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSizeMsureVal.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesizemsurevalFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSizeMsureVal.remove()" >Delete</a>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    <div title="Sample Required" style="padding:1px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List',footer:'#ftsmplecs'" style="padding:2px" >
                <form id="stylesamplecsFrm">
                <div id="container">
                    <div id="body">
                    <input type="hidden" name="style_sample_id" id="style_sample_id" value=""/>
                    <code id="scs">

                    </code>
                    </div>
                </div>
                <div id="ftsmplecs" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                 <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSampleCs.submit()">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesamplecsFrm')" >Reset</a>
        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSampleCs.remove()" >Delete</a>
                </div>
                </form>
            </div>
            <div data-options="region:'west',footer:'#ft9'" style="width:400px; padding:2px">
                <div class="easyui-layout"  data-options="fit:true">
                    <div data-options="region:'north',border:true,title:'Add Sample',footer:'#ft9'" style="height:400px; padding:2px">
                        <form id="stylesampleFrm">
                            <div id="container">
                                <div id="body">
                                    <code>
                                        <div class="row">
                                            <div class="col-sm-4 req-text">Style</div>
                                            <div class="col-sm-8">
                                             <input type="text" name="style_ref" id="style_ref" readonly/>
                                             <input type="hidden" name="id" id="id" value=""/>
                                             <input type="hidden" name="style_id" id="style_id" value=""/>
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Gmts Item </div>
                                            <div class="col-sm-8">{!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">GMTS Sample </div>
                                            <div class="col-sm-8">{!! Form::select('gmtssample_id', $gmtssample,'',array('id'=>'gmtssample_id')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Approval Priority </div>
                                            <div class="col-sm-8">{!! Form::select('approval_priority', $orderstage,'',array('id'=>'approval_priority')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Is Chargeable </div>
                                            <div class="col-sm-8">{!! Form::select('is_charge_allowed', $yesno,'',array('id'=>'is_charge_allowed')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Is Costable </div>
                                            <div class="col-sm-8">{!! Form::select('is_costing_allowed', $yesno,'',array('id'=>'is_costing_allowed')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Currency </div>
                                            <div class="col-sm-8">{!! Form::select('currency_id', $currency,'',array('id'=>'currency_id')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Fab. Instruction </div>
                                            <div class="col-sm-8">{!! Form::select('fabric_instruction_id', $fabricinstruction,'',array('id'=>'fabric_instruction_id')) !!}</div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Pattern TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="pattern_from" id="pattern_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="pattern_to" id="pattern_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Sample Book TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="sample_booking_from" id="sample_booking_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="sample_booking_to" id="sample_booking_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">YarnInhouse TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="yarn_inhouse_from" id="yarn_inhouse_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="yarn_inhouse_to" id="yarn_inhouse_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Yarn Dyeing TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="yarn_dyeing_from" id="yarn_dyeing_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="yarn_dyeing_to" id="yarn_dyeing_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Knitting TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="knitting_from" id="knitting_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="knitting_to" id="knitting_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Dyeing TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="dyeing_from" id="dyeing_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="dyeing_to" id="dyeing_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">AOP TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="aop_from" id="aop_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="aop_to" id="aop_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Finishing TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="finishing_from" id="finishing_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="finishing_to" id="finishing_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Cutting TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="cutting_from" id="cutting_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="cutting_to" id="cutting_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Print TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="print_emb_from" id="print_emb_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="print_emb_to" id="print_emb_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Emb TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="emb_from" id="emb_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="emb_to" id="emb_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Washing TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="washing_from" id="washing_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="washing_to" id="washing_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Trims TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="trims_from" id="trims_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="trims_to" id="trims_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Sewing TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="sewing_from" id="sewing_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="sewing_to" id="sewing_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">BuyerSub TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="sub_from" id="sub_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="sub_to" id="sub_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4">Approval TNA</div>
                                            <div class="col-sm-4" style="padding-right:0px">
                                                <input type="text" name="app_from" id="app_from" class="datepicker" placeholder="  From Date" />
                                            </div>
                                            <div class="col-sm-4" style="padding-left:0px">
                                                <input type="text" name="app_to" id="app_to" class="datepicker"  placeholder="  To Date" />
                                            </div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Remarks</div>
                                            <div class="col-sm-8"><textarea id="remarks" name="remarks"></textarea></div>
                                        </div>
                                        <div class="row middle">
                                            <div class="col-sm-4 req-text">Sequence </div>
                                            <div class="col-sm-8"><input type="text" name="sort_id" id="sort_id" class="number integer"/></div>
                                        </div>
                                    </code>
                                </div>
                            </div>
                            <div id="ft9" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                                <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleSample.submit()">Save</a>
                                <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylesampleFrm')" >Reset</a>
                                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleSample.remove()" >Delete</a>
                            </div>
                        </form>
                    </div>
                    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                        <table id="stylesampleTbl" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-options="field:'style'" width="100">Style</th>
                                    <th data-options="field:'stylegmts'" width="100">Gmts. Item</th>
                                    <th data-options="field:'gmtssample'" width="100">Gmts. Sample</th>
                                    <th data-options="field:'qty'" width="50" align="right">Qty</th>
                                    <th data-options="field:'rate'" width="50" align="right">Rate</th>
                                    <th data-options="field:'amount'" width="50" align="right">Amount</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div title="Poly Ratio" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylepolyTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="80">ID</th>
    <th data-options="field:'style'" width="100">Style</th>
    <th data-options="field:'itemclass'" width="100">Item Class</th>
    <th data-options="field:'spec'" width="100">Spec</th>
    <th data-options="field:'gmt_ratio'" width="70" align="right">GMT Ratio</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StylePoly'" style="width:350px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'north',border:true,footer:'#ft10'" style="height:147px; padding:2px">
    <form id="stylepolyFrm">
    <div id="container">
    <div id="body">
    <code>

        <div class="row">
            <div class="col-sm-4 req-text">Style</div>
            <div class="col-sm-8">
            <input type="text" name="style_ref" id="style_ref" readonly/>
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="style_id" id="style_id" value=""/>
            </div>

        </div>
         <div class="row middle">
           <div class="col-sm-4 req-text">Item Class </div>
           <div class="col-sm-8">{!! Form::select('itemclass_id', $itemclass,'',array('id'=>'itemclass_id')) !!}</div>
        </div>
        <div class="row middle">
            <div class="col-sm-4 req-text">Spec </div>
            <div class="col-sm-8"><input type="text" name="spec" id="spec" value=""/></div>
        </div>

        <div class="row middle">
            <div class="col-sm-4">Assortment  </div>
            <div class="col-sm-8">{!! Form::select('assortment', $assortment,'',array('id'=>'assortment')) !!}</div>
        </div>

    </code>
    </div>
    </div>
    <div id="ft10" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePoly.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylepolyFrm')" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePoly.remove()" >Delete</a>
    </div>
    </form>
    </div>
    <div data-options="region:'center',border:true,title:'Style Poly Ratio'" style="padding:2px">
    <div class="easyui-layout"  data-options="fit:true">
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
    <table id="stylepolyratioTbl" style="width:100%">
    <thead>
    <tr>
    <th data-options="field:'id'" width="30">ID</th>
    <th data-options="field:'stylegmts'" width="100">Style GMT</th>
    <th data-options="field:'gmtratio'" width="50" align="right">GMT Ratio</th>
    </tr>
    </thead>
    </table>
    </div>
    <div data-options="region:'north',border:true,title:'Add New StylePolyRatio',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ftpolyrario'" style="height:130px; padding:2px">
    <form id="stylepolyratioFrm">
    <div id="container">
    <div id="body">
    <code>
    <div class="row">
     <div class="col-sm-4 req-text">Gmts. Item </div>
    <div class="col-sm-8">
    {!! Form::select('style_gmt_id', $stylegmts,'',array('id'=>'style_gmt_id')) !!}
    <input type="hidden" name="id" id="id" value=""/>
    <input type="hidden" name="style_id" id="style_id" value=""/>
    <input type="hidden" name="style_poly_id" id="style_poly_id"/>
    </div>
    </div>
    <div class="row middle">
    <div class="col-sm-4">GMT Ratio  </div>
    <div class="col-sm-8"><input type="text" name="gmt_ratio" id="gmt_ratio" class="number integer"/></div>
    </div>

    </code>
    </div>
    </div>
    <div id="ftpolyrario" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePolyRatio.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylepolyratioFrm')" >Reset</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePolyRatio.remove()" >Delete</a>
    </div>

    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>

    </div>
    <div title="Packing Ratio" style="padding:1px">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'center',border:true,title:'List',footer:'#ftpkgratio'" style="padding:2px">
    <form id="stylepkgratioFrm">
    <div id="container">
    <div id="body">
    <input type="hidden" name="style_pkg_id" id="style_pkg_id" value=""/>
    <input type="hidden" name="id" id="id" value=""/>
    <input type="hidden" name="style_id" id="style_id" value=""/>
    <code id="pkgcs">
    </code>
    </div>
    </div>
    <div id="ftpkgratio" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePkgRatio.submit()">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkgRatio.resetForm()" >Reset</a>
    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkgRatio.remove()" >Delete</a>
    </div>
    </form>
    </div>
    <div data-options="region:'west',border:true,title:'Add New StylePkg',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false" style="width:400px; padding:2px">
    <div class="easyui-layout"  data-options="fit:true" >
    <div data-options="region:'north',border:true,hideCollapsedContent:false,collapsed:false,footer:'#ft12'" style="height:230px; padding:2px">
        <form id="stylepkgFrm">
            <div id="container">
                <div id="body">
                <code>

                <div class="row">
                    <div class="col-sm-4 req-text">Style </div>
                    <div class="col-sm-8">
                    <input type="text" name="style_ref" id="style_ref" readonly/>
                    <input type="hidden" name="id" id="id" value=""/>
                    <input type="hidden" name="style_id" id="style_id" value=""/>
                    </div>

                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Item Class </div>
                    <div class="col-sm-8">{!! Form::select('itemclass_id', $itemclass,62,array('id'=>'itemclass_id')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Assortment Name</div>
                    <div class="col-sm-8">
                        <input type="text" name="assortment_name" id="assortment_name" value="" />
                    </div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4 req-text">Spec </div>
                    <div class="col-sm-8"><input type="text" name="spec" id="spec" value=""/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">No Of Pack  </div>
                    <div class="col-sm-8"><input type="text" name="qty" id="qty" class="number integer"/></div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Assortment  </div>
                    <div class="col-sm-8">{!! Form::select('assortment', $assortment,'',array('id'=>'assortment')) !!}</div>
                </div>
                <div class="row middle">
                    <div class="col-sm-4">Packing Type  </div>
                    <div class="col-sm-8"><input type="text" name="packing_type" id="packing_type" class="number integer"/></div>
                </div>
                </code>
            </div>
        </div>
        <div id="ft12" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
            <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStylePkg.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkg.resetForm()" >Reset</a>
            <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStylePkg.remove()" >Delete</a>
        </div>
    </form>
    </div>
    <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="stylepkgTbl" style="width:100%">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'assortment_name'" width="100">Assortment Name</th>
                    <th data-options="field:'itemclass'" width="100">Item Class</th>
                    <th data-options="field:'spec'" width="100">Spec</th>
                    <th data-options="field:'qty'" width="70" align="right">Qty</th>
                </tr>
            </thead>
        </table>
    </div>
    </div>
    </div>
    </div>

    </div>
    <div title="Evaluation Results" style="padding:1px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="styleevaluationTbl" style="width:100%">
                <thead>
                <tr>
                <th data-options="field:'id'" width="80">ID</th>
                <th data-options="field:'style'" width="100">Style</th>
                <th data-options="field:'favorable'" width="100">Favorable</th>
                <th data-options="field:'risk'" width="100">Risk</th>
                </tr>
                </thead>
                </table>
            </div>
            <div data-options="region:'west',border:true,title:'Add New StyleEvaluation',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ft13'" style="width:350px; padding:2px">
                <form id="styleevaluationFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                                <div class="col-sm-4 req-text">Style</div>
                                <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" readonly/>
                                <input type="hidden" name="id" id="id" value=""/>
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Favorable</div>
                                <div class="col-sm-8">
                                    <textarea name="favorable"></textarea>

                                </div>
                                </div>
                                <div class="row middle">
                                <div class="col-sm-4 req-text">Risk </div>
                                <div class="col-sm-8">
                                    <textarea  name="risk"></textarea>
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ft13" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleEvaluation.submit()">Save</a>
                    <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('styleevaluationFrm')" >Reset</a>
                    <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleEvaluation.remove()" >Delete</a>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div title="Image Upload" style="padding:1px">
        <div class="easyui-layout"  data-options="fit:true">
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
              <img id="output" src="../../../../public/images/1532460938.jpg"/>
            </div>
            <div data-options="region:'west',border:true,title:'Add Image',iconCls:'icon-more',hideCollapsedContent:false,collapsed:false,footer:'#ftuf13'" style="width:350px; padding:2px">
                <form id="styleimageFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                                <div class="col-sm-4 req-text">Style</div>
                                <div class="col-sm-8">
                                <input type="text" name="style_ref" id="style_ref" readonly/>
                                <input type="hidden" name="id" id="id" value=""/>
                                <input type="hidden" name="style_id" id="style_id" value=""/>
                                </div>
                            </div>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">Image</div>
                                <div class="col-sm-8">
                                    <input type="file" id="uploadfile" name="uploadfile" onchange="MsStyleEvaluation.loadFile(event)">
                                </div>
                            </div>
                            <br/><br/>
                            <div class="row middle">
                                <div class="col-sm-4 req-text">File</div>
                                <div class="col-sm-8">
                                    <input type="file" id="uploadfilename" name="uploadfilename">
                                </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ftuf13" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                    <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleEvaluation.upload()">Upload File</a>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div title="File Upload" style="padding:2px">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'west',border:true,title:'Upload File',iconCls:'icon-more',footer:'#assetfileft'" style="width:450px; padding:2px">
                <form id="stylefileuploadFrm" enctype="multipart/form-data">
                    <div id="container">
                        <div id="body">
                            <code>
                                <div class="row middle">
                                    <input type="hidden" name="id" id="id" value="" />
                                    <input type="hidden" name="style_id" id="style_id" value=""/>
                                    <div class="col-sm-4 req-text">Style Ref</div>
                                   <div class="col-sm-8">
                                        <input type="text" name="style_ref" id="style_ref">    	
                                   </div>                                   
                                </div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Name</div>
                                   <div class="col-sm-8">
                                    	<input type="text" id="original_name" name="original_name" value="" />
                                   </div>
                            	</div>
                                <div class="row middle">
                                   <div class="col-sm-4 req-text">File Upload</div>
                                   <div class="col-sm-8">
                                    	<input type="file" id="file_upload" name="file_upload" value="" />            	
                                   </div>
                            	</div>                                
                            </code>
                        </div>
                    </div>
                    <div id="assetfileft" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
                        <a href="javascript:void(0)" class="easyui-linkbutton  c1" style="height:25px; border-radius:1px" iconCls="icon-save" plain="true" id="save" onClick="MsStyleFileUpload.submit()">Upload</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('stylefileuploadFrm')">Reset</a>
                        <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px" iconCls="icon-remove" plain="true" id="delete" onClick="MsStyleFileUpload.remove()">Delete</a>
                    </div>
                </form>
            </div>
            <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
                <table id="stylefileuploadTbl" style="width:100%">
                    <thead>
                        <tr>
                            <th data-options="field:'id'" width="80">ID</th>
                            <th data-options="field:'original_name'" width="80">Original Name</th>
                            <th data-options="field:'file_src'" width="80" formatter="MsStyleFileUpload.formatfile">Upload Files</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="styleFabricationWindow" class="easyui-window" title="Fabrications" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout"  data-options="fit:true">
        <div data-options="region:'center',border:true,footer:'#stylefabricsearchTblft'" style="padding:2px">
            <table id="stylefabricsearchTbl" style="width:100%">
                <thead>
                    <tr>
                    <th data-options="field:'id'" width="80">ID</th>
                    <th data-options="field:'name'" width="100">Construction</th>
                    <th data-options="field:'composition_name'" width="300">Composition</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div data-options="region:'west',border:true" style="padding:2px; width:350px">
            <form id="stylefabricsearchFrm">
                <div id="container">
                    <div id="body">
                        <code>
                            <div class="row ">
                            <div class="col-sm-4 req-text">Construction</div>
                            <div class="col-sm-8"> <input type="text" name="construction_name" id="construction_name" /> </div>
                            </div>
                            <div class="row middle">
                            <div class="col-sm-4 req-text">Composition</div>
                            <div class="col-sm-8">
                            <input type="text" name="composition_name" id="composition_name" />
                            </div>
                            </div>
                        </code>
                    </div>
                </div>
                <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;" >
                <a href="javascript:void(0)" class=" easyui-linkbutton c5" style="height:25px;border-radius:1px"  plain="true" \ onClick="MsStyleFabrication.searchFabric()" >Search</a>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Style Window --}}
<div id="stylesearchWindow" class="easyui-window" title="Style Window" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:1000px;height:500px;padding:2px;">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'north',split:true, title:'Search'" style="height:130px">
            <div class="easyui-layout" data-options="fit:true">
                <div id="body">
                    <code>
                        <form id="stylesearchFrm">
                            <div class="row">
                                <div class="col-sm-2">Buyer :</div>
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
                    <a href="javascript:void(0)" class="easyui-linkbutton c2" style="width:70px; height:25px" onClick="MsStyle.searchStyle()">Search</a>
                </p>
            </div>
        </div>
        <div data-options="region:'center'" style="padding:10px;">
            <table id="stylesearchTbl" style="width:610px">
            <thead>
                <tr>
                    <th data-options="field:'id'" width="50">ID</th>
                    <th data-options="field:'buyer'" width="70">Buyer</th>
                    <th data-options="field:'receivedate'" width="80">Receive Date</th>
                    <th data-options="field:'style_ref'" width="100">Style Refference</th>
                    <th data-options="field:'deptcategory'" width="80">Dept. Category</th>
                    <th data-options="field:'productdepartment'" width="80">Product Department</th>
                    <th data-options="field:'season'" width="80">Season</th>
                    <th data-options="field:'uom'" width="70">Uom</th>
                    <th data-options="field:'team'" width="80">Team</th>
                    <th data-options="field:'teammember'" width="80">Team Member</th>
                    <th data-options="field:'contact'" width="100">Contact</th>
                </tr>
            </thead>
            </table>
        </div>
        <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
            <a class="easyui-linkbutton c1" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$('#stylesearchWindow').window('close')" style="width:80px">Close</a>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url('/');?>/js/Util/MsAllStyleController.js"></script>
<script>
$(".datepicker" ).datepicker({
	dateFormat: 'yy-mm-dd',
	changeMonth: true,
	changeYear: true
});
$('.integer').keyup(function () {
    if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
$('#styleFrm [id="buyer_id"]').combobox();
$('#stylesearchFrm [id="buyer_id"]').combobox();
//$('#styleembelishmentFrm [id="gmtspart_id"]').combobox();
//$('#styleembelishmentFrm [id="embelishment_type_id"]').combobox();

$(document).ready(function() {
	var bloodhound = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.whitespace,
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	url: msApp.baseUrl()+'/color/getcolor?q=%QUERY%',
	wildcard: '%QUERY%'
	},
	});
	
	$('#color_id').typeahead(
		{
			hint: true,
			highlight: true,
			minLength: 1
		}, {
			name: 'colors',
			limit:1000,
			source: bloodhound,
			display: function(data) {
				return data.name  //Input value to be set when you select a suggestion. 
			},
			templates: {
				empty: [
					'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
				],
				header: [
					'<div class="list-group search-results-dropdown">'
				],
				suggestion: function(data) {
					return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
				}
			}
		}
	);
});


$(document).ready(function() {
	var bloodhound = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/size/getsize?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});
	
	$('#size_id').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'sizes',
		limit:1000,
		source: bloodhound,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});
});

$(document).ready(function() {
	var bloodhound = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/style/getstyle?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});
	
	$('#style_ref').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'styles',
		limit:1000,
		source: bloodhound,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});
});
$(document).ready(function() {

	var bloodhound = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.whitespace,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
		url: msApp.baseUrl()+'/style/getstyledescription?q=%QUERY%',
		wildcard: '%QUERY%'
		},
	});

$('#style_description').typeahead({
		hint: true,
		highlight: true,
		minLength: 1
	}, {
		name: 'styles',
		limit:1000,
		source: bloodhound,
		display: function(data) {
			return data.name  //Input value to be set when you select a suggestion. 
		},
		templates: {
			empty: [
				'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
			],
			header: [
				'<div class="list-group search-results-dropdown">'
			],
			suggestion: function(data) {
				return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
			}
	    }
	});

var contact = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
    url: msApp.baseUrl()+'/style/getcontact?',
    replace: function(url, uriEncodedQuery) {
      val = $('#styleFrm  [name=buying_agent_id]').val();
      contact = $('#styleFrm  [name=contact]').val();
      if (!val) return url;
      return url + '&buying_agent_id=' + encodeURIComponent(val)+ '&q=' + encodeURIComponent(contact)
      },
    wildcard: '%QUERY%'
    },
  });

$('#contact').typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'contacts',
    limit:1000,
    source: contact,
   
    display: function(data) {
      return data.name  //Input value to be set when you select a suggestion. 
    },
    templates: {
      empty: [
        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
      ],
      header: [
        '<div class="list-group search-results-dropdown">'
      ],
      suggestion: function(data) {
        return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
      }
      }
  });

});

$(document).ready(function() {

	var spec = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.whitespace,
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
	url: msApp.baseUrl()+'/stylepkg/getspec?q=%QUERY%',
	wildcard: '%QUERY%'
	},
	});
	
	$('#stylepkgFrm [name=spec]').typeahead(
		{
			hint: true,
			highlight: true,
			minLength: 1
		}, {
			name: 'specs',
			limit:1000,
			source: spec,
			display: function(data) {
				return data.name  //Input value to be set when you select a suggestion. 
			},
			templates: {
				empty: [
					'<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
				],
				header: [
					'<div class="list-group search-results-dropdown">'
				],
				suggestion: function(data) {
					return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
				}
			}
		}
    );
    
    var assortmentame = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.whitespace,
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: msApp.baseUrl()+'/stylepkg/getassortmentname?q=%QUERY%',
        wildcard: '%QUERY%'
        },
    });

    $('#assortment_name').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
        {
            name: 'assortment_name',
            limit:1000,
            source: assortmentame,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion. 
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function(data) {
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item">' + data.name + '</div></div>'
                }
            }
    });
});

</script>

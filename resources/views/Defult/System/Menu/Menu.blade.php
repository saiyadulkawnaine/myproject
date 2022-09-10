<style>
  @-webkit-keyframes swing {
    15% {
      -webkit-transform: translateX(5px);
      transform: translateX(5px);
    }

    30% {
      -webkit-transform: translateX(-5px);
      transform: translateX(-5px);
    }

    50% {
      -webkit-transform: translateX(3px);
      transform: translateX(3px);
    }

    65% {
      -webkit-transform: translateX(-3px);
      transform: translateX(-3px);
    }

    80% {
      -webkit-transform: translateX(2px);
      transform: translateX(2px);
    }

    100% {
      -webkit-transform: translateX(0);
      transform: translateX(0);
    }
  }

  @keyframes swing {
    15% {
      -webkit-transform: translateX(5px);
      transform: translateX(5px);
    }

    30% {
      -webkit-transform: translateX(-5px);
      transform: translateX(-5px);
    }

    50% {
      -webkit-transform: translateX(3px);
      transform: translateX(3px);
    }

    65% {
      -webkit-transform: translateX(-3px);
      transform: translateX(-3px);
    }

    80% {
      -webkit-transform: translateX(2px);
      transform: translateX(2px);
    }

    100% {
      -webkit-transform: translateX(0);
      transform: translateX(0);
    }
  }



  .swing {
    -webkit-animation: swing 1s ease;
    animation: swing 3s ease;
    -webkit-animation-iteration-count: 1;
    animation-iteration-count: 1;
  }

  @keyframes four {
    0% {
      Border-radius: 0px;
    }

    100% {
      border-radius: 0px;
      transform:
        rotateX(360deg) rotateY(360deg);
    }
  }

  #four {
    Animation: four 1s ease 0s normal;
    Border-radius: 0px;
  }

  .animated {

    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
  }

  @-webkit-keyframes rollIn {
    0% {
      opacity: 0;
      -webkit-transform: translateX(-100%) rotate(-120deg);
    }

    100% {
      opacity: 1;
      -webkit-transform: translateX(0px) rotate(0deg);
    }
  }

  @keyframes rollIn {
    0% {
      opacity: 0;
      transform: translateX(-100%) rotate(-120deg);
    }

    100% {
      opacity: 1;
      transform: translateX(0px) rotate(0deg);
    }
  }

  .rollIn {
    -webkit-animation-name: rollIn;
    animation-name: rollIn;
  }

</style>
<div class="easyui-tabs" style="width:100%;height: 100%;border:none" id="menuTabs">
  <div title="Menu" style="padding: 2px;">
    <div class="easyui-layout" data-options="fit:true">
      <div data-options="region:'center',border:true,title:'List'" style="padding:2px">
        <table id="menuTbl" style="width:100%">
          <thead>
            <tr>
              <th data-options="field:'id'" width="80">ID</th>
              <th data-options="field:'name'" width="200">Name</th>
              <th data-options="field:'router'" width="150">Router</th>
              <!-- <th data-options="field:'detail'" width = "60" formatter="MsMenu.formatDetail">Actions</th>-->
            </tr>
          </thead>
        </table>

      </div>
      <div data-options="region:'west',border:true,title:'Add New Menu',footer:'#ft2'" style="width:400px; padding:2px">
        <form id="menuFrm">
          <div id="container">
            <div id="body">
              <code>
        <div class="row middle">
         <div class="col-sm-5 req-text">Name </div>
         <div class="col-sm-7">
          <input type="text" name="name" id="name" value="" />
           <input type="hidden" name="id" id="id" value="" />
         </div>
        </div>
        <div class="row middle">
         <div class="col-sm-5">Router</div>
         <div class="col-sm-7">
          <input type="text" name="router" id="router" value="" />
         </div>
        </div>
        <div class="row middle">
         <div class="col-sm-5">Root Manu</div>
         <div class="col-sm-7">
          {!! Form::select('root_id', $menus,'',array('id'=>'root_id')) !!}
         </div>
        </div>
        <div class="row middle">
         <div class="col-sm-5">Sort:</div>
         <div class="col-sm-7">
          <input type="text" name="sort_id" id="sort_id" value="" />
         </div>
        </div>
          </code>
            </div>
          </div>
          <div id="ft2" style="padding:0px 0px; text-align:right; background:#F3F3F3;">
            <a href="javascript:void(0)" class="easyui-linkbutton c1" style="height:25px; border-radius:1px"
              iconCls="icon-save" plain="true" id="save" onClick="MsMenu.submit()">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c6" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="msApp.resetForm('menuFrm')">Reset</a>
            <a href="javascript:void(0)" class="easyui-linkbutton c5" style="height:25px;border-radius:1px"
              iconCls="icon-remove" plain="true" id="delete" onClick="MsMenu.remove()">Delete</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="<?php echo url('/');?>/js/menu/MsMenuController.js"></script>
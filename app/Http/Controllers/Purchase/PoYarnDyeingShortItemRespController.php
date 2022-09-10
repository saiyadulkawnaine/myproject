<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRespRepository;
use App\Repositories\Contracts\Bom\BudgetFabricRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoYarnDyeingItemRespRequest;


class PoYarnDyeingShortItemRespController extends Controller
{
  private $poyarndyeing;
  private $poyarndyeingitem;
  private $budgetfabric;
  private $poyarndyeingitemresp;
  private $employeehr;

  public function __construct(
    PoYarnDyeingRepository $poyarndyeing,
    PoYarnDyeingItemRepository $poyarndyeingitem,
    EmployeeHRRepository $employeehr,
    PoYarnDyeingItemRespRepository $poyarndyeingitemresp,
    BudgetFabricRepository $budgetfabric
  ) {
    $this->poyarndyeing = $poyarndyeing;
    $this->poyarndyeingitem = $poyarndyeingitem;
    $this->poyarndyeingitemresp = $poyarndyeingitemresp;
    $this->budgetfabric = $budgetfabric;
    $this->employeehr = $employeehr;

    $this->middleware('auth');
    // $this->middleware('permission:view.poyarndyeingshortitemresp',   ['only' => ['create', 'index', 'show']]);
    // $this->middleware('permission:create.poyarndyeingshortitemresp', ['only' => ['store']]);
    // $this->middleware('permission:edit.poyarndyeingshortitemresp',   ['only' => ['update']]);
    // $this->middleware('permission:delete.poyarndyeingshortitemresp', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $shorttype = array_prepend(config('bprs.shorttype'), '-Select-', '');
    $poyarndyeingitemresps = array();
    $rows = $this->poyarndyeingitemresp
      ->join('po_yarn_dyeing_items', function ($join) {
        $join->on('po_yarn_dyeing_items.id', '=', 'po_yarn_dyeing_item_resps.po_yarn_dyeing_item_id');
      })
      ->join('po_yarn_dyeings', function ($join) {
        $join->on('po_yarn_dyeings.id', '=', 'po_yarn_dyeing_items.po_yarn_dyeing_id');
      })
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('employee_h_rs.id', '=', 'po_yarn_dyeing_item_resps.employee_h_r_id');
      })
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'employee_h_rs.company_id');
      })
      ->leftJoin('sections', function ($join) {
        $join->on('sections.id', '=', 'employee_h_rs.section_id');
      })
      ->where([['po_yarn_dyeing_item_resps.id', '=', request('po_yarn_dyeing_item_id', 0)]])
      ->orderBy('po_yarn_dyeing_item_resps.id', 'desc')
      ->get([
        'po_yarn_dyeing_item_resps.*',
        'employee_h_rs.name as employee_name',
        'companies.code as company_code',
        'sections.name as section',
        //'po_yarn_dyeing_items.id as po_yarn_dyeing_item_id'
      ]);

    foreach ($rows as $row) {
      $budgetfabric['id'] =  $row->id;
      $budgetfabric['reason'] =  $row->reason;
      $budgetfabric['section'] =  $row->section;
      $budgetfabric['company_code'] =  $row->company_code;
      $budgetfabric['cost_share_per'] =  $row->cost_share_per;
      $budgetfabric['short_type'] =  $shorttype[$row->short_type_id];
      $budgetfabric['employee_name'] =  $row->employee_name;
      array_push($poyarndyeingitemresps, $budgetfabric);
    }
    echo json_encode($poyarndyeingitemresps);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(PoYarnDyeingItemRespRequest $request)
  {
    $poyarndyeingitemresp = $this->poyarndyeingitemresp->create($request->except(['id', 'employee_name']));

    if ($poyarndyeingitemresp) {
      return response()->json(array('success' => true, 'id' => $poyarndyeingitemresp->id, 'message' => 'Save Successfully'), 200);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $poyarndyeingitemresp = $this->poyarndyeingitemresp
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('employee_h_rs.id', '=', 'po_yarn_dyeing_item_resps.employee_h_r_id');
      })
      ->where([['po_yarn_dyeing_item_resps.id', '=', $id]])
      ->get([
        'po_yarn_dyeing_item_resps.*',
        'employee_h_rs.name as employee_name'
      ])
      ->first();
    $row['fromData'] = $poyarndyeingitemresp;
    $dropdown['att'] = '';
    $row['dropDown'] = $dropdown;
    echo json_encode($row);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(PoYarnDyeingItemRespRequest $request, $id)
  {
    $poyarndyeingitemresp = $this->poyarndyeingitemresp->update($id, $request->except(['id', 'employee_name']));
    if ($poyarndyeingitemresp) {
      return response()->json(array('success' => true, 'id' => $id, 'message' => 'Update Successfully'), 200);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    if ($this->poyarndyeingitemresp->delete($id)) {
      return response()->json(array('success' => true, 'message' => 'Delete Successfully'), 200);
    }
  }

  public function getEmployeeHr()
  {
    $employeehr = $this->employeehr
      ->leftJoin('companies', function ($join) {
        $join->on('companies.id', '=', 'employee_h_rs.company_id');
      })
      ->leftJoin('designations', function ($join) {
        $join->on('designations.id', '=', 'employee_h_rs.designation_id');
      })
      ->leftJoin('departments', function ($join) {
        $join->on('departments.id', '=', 'employee_h_rs.department_id');
      })
      ->when(request('company_id'), function ($q) {
        return $q->where('employee_h_rs.company_id', '=', request('company_id', 0));
      })
      ->when(request('designation_id'), function ($q) {
        return $q->where('employee_h_rs.designation_id', '=', request('designation_id', 0));
      })
      ->when(request('department_id'), function ($q) {
        return $q->where('employee_h_rs.department_id', '=', request('department_id', 0));
      })
      ->get([
        'employee_h_rs.*',
        'departments.name as department',
        'designations.name as designation',
        'companies.name as company',
      ])
      ->map(function ($employeehr) {
        $employeehr->employee_name = $employeehr->name;
        return $employeehr;
      });

    echo json_encode($employeehr);
  }
}

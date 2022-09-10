<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\Purchase\PoTrimRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemRepository;
use App\Repositories\Contracts\Purchase\PoTrimItemResponsibleRepository;
use App\Repositories\Contracts\Bom\BudgetTrimRepository;
use App\Repositories\Contracts\HRM\EmployeeHRRepository;
use App\Library\Template;
use App\Http\Requests\Purchase\PoTrimItemResponsibleRequest;


class PoTrimShortItemResponsibleController extends Controller
{
  private $potrim;
  private $potrimitem;
  private $budgettrim;
  private $potrimitemresponsible;
  private $employeehr;

  public function __construct(
    PoTrimRepository $potrim,
    PoTrimItemRepository $potrimitem,
    EmployeeHRRepository $employeehr,
    PoTrimItemResponsibleRepository $potrimitemresponsible,
    BudgetTrimRepository $budgettrim
  ) {
    $this->potrim = $potrim;
    $this->potrimitem = $potrimitem;
    $this->potrimitemresponsible = $potrimitemresponsible;
    $this->budgettrim = $budgettrim;
    $this->employeehr = $employeehr;

    $this->middleware('auth');
    // $this->middleware('permission:view.potrimshortitemresponsibles',   ['only' => ['create', 'index','show']]);
    // $this->middleware('permission:create.potrimshortitemresponsibles', ['only' => ['store']]);
    // $this->middleware('permission:edit.potrimshortitemresponsibles',   ['only' => ['update']]);
    // $this->middleware('permission:delete.potrimshortitemresponsibles', ['only' => ['destroy']]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $potrimitemresponsible = array();
    $rows = $this->potrimitemresponsible
      ->join('po_trim_items', function ($join) {
        $join->on('po_trim_items.id', '=', 'po_trim_item_responsibles.po_trim_item_id');
      })
      ->join('po_trims', function ($join) {
        $join->on('po_trims.id', '=', 'po_trim_items.po_trim_id');
      })
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('employee_h_rs.id', '=', 'po_trim_item_responsibles.employee_h_r_id');
      })
      ->where([['po_trim_items.id', '=', request('po_trim_item_id', 0)]])
      ->orderBy('po_trim_item_responsibles.id', 'desc')
      ->get([
        'po_trim_item_responsibles.*',
        'employee_h_rs.name as employee_name'
        //'po_trim_items.id as po_trim_item_id'
      ]);

    foreach ($rows as $row) {
      $budgettrim['id'] =  $row->id;
      $budgettrim['reason'] =  $row->reason;
      $budgettrim['section'] =  $row->job_no;
      $budgettrim['parson'] =  $row->parson;
      $budgettrim['employee_name'] =  $row->employee_name;
      array_push($budgettrims, $budgettrim);
    }
    echo json_encode($budgettrims);
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
  public function store(PoTrimItemResponsibleRequest $request)
  {
    // $potrimapproved=$this->potrim->find($request->po_trim_id);
    // if($potrimapproved->approved_at){
    //   return response()->json(array('success' => false,  'message' => 'Trim Purchase Order is Approved, So Save Or Update not Possible'), 200);
    // }
    $potrimitemresponsible = $this->potrimitemresponsible->create($request->except(['id', 'employee_name']));

    if ($potrimitemresponsible) {
      return response()->json(array('success' => true, 'id' => $potrimitemresponsible->id, 'message' => 'Save Successfully'), 200);
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
    $potrimitemresponsible = $this->potrimitemresponsible
      ->leftJoin('employee_h_rs', function ($join) {
        $join->on('employee_h_rs.id', '=', 'po_trim_item_responsibles.employee_h_r_id');
      })
      ->where([['po_trim_item_responsibles.id', '=', $id]])
      ->get([
        'po_trim_item_responsibles.*',
        'employee_h_rs.name as employee_name'
      ])
      ->first();
    $row['fromData'] = $potrimitemresponsible;
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
  public function update(PoTrimItemResponsibleRequest $request, $id)
  {
    $potrimitemresponsible = $this->potrimitemresponsible->update($id, $request->except(['id', 'employee_name']));
    if ($potrimitemresponsible) {
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
    if ($this->potrimitemresponsible->delete($id)) {
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

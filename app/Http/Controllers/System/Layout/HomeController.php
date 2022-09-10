<?php

namespace App\Http\Controllers\System\Layout;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Library\Template;
class HomeController extends Controller
{
	  public function __construct(){
		$this->middleware('auth');
	  }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	
    public function index()
    {
		return Template::loadView("System.Layout.home");
    }
}

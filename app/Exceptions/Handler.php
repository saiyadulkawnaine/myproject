<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\QueryException;// add my monzu 31-10-2017 use in render function
use Yajra\Pdo\Oci8\Exceptions\Oci8Exception;// add my monzu 31-10-2017 use in render function
use Illuminate\Validation\ValidationException;// add my monzu 31-10-2017 use in render function
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		if ($exception instanceof QueryException) {// add by monzu 31-10-2016
			 return response()->json(array('success' => false,'errors' => $exception->getSql(),'message' => $exception->getMessage()),400);
        }
		if ($exception instanceof Oci8Exception) {// add by monzu 31-10-2016
			 return response()->json(array('success' => false,'errors' => $exception->getCode(),'message' => $exception->getMessage()),400);
        }
		if (($request->ajax() || $request->wantsJson()) && ( ! $exception instanceof ValidationException))// add by monzu 31-10-2016
		{
			$json = [
				'success' => false,
				'message' => $exception->getMessage(),
				'error' => [
				'code' => $exception->getCode(),
				],
			];
			return response()->json($json, 400);
		}
		// add by monzu 31-10-2016
		$userLevelCheck = $exception instanceof \jeremykenedy\LaravelRoles\Exceptions\RoleDeniedException ||
        $exception instanceof \jeremykenedy\LaravelRoles\Exceptions\RoleDeniedException ||
        $exception instanceof \jeremykenedy\LaravelRoles\Exceptions\PermissionDeniedException ||
        $exception instanceof \jeremykenedy\LaravelRoles\Exceptions\LevelDeniedException;
        if ($userLevelCheck) {
            if ($request->expectsJson()) {
                return response()->json(array('success' => false, 'error'=> 403,'message'   =>  $exception->getMessage()), 403);
            }
            return response()->view('errors.403');
        }
		//================= add by monzu End====================
        return parent::render($request, $exception);
    }
}

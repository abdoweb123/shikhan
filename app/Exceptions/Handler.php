<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException)
        {
            return $this->unauthorized($request, $exception);
        }

        if ($exception instanceof AuthorizationException)
        {
            return $this->unauthorized($request, $exception);
        }

        // This will replace our 404 response with
        // a JSON response.
        if ($exception instanceof ModelNotFoundException && $request->wantsJson())
        {
            return response()->json(['message' => 'Resource not found'], 404);
        }


        if($exception instanceof NotFoundHttpException)
        {
            if ($request->ajax())
            {
                return response()->json(['message' => 'Not Found'], 404);
            }
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException)
        {
            return redirect()->route('home');
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson())
        {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $guards = $exception->guards();
        if (in_array('web',$guards))
        {
            return redirect(route('login'));
        }
        if (in_array('admin',$guards))
        {
            return redirect(route('dashboard.login'));
        }
    }

    private function unauthorized($request,Exception $exception)
    {
        if ($request->expectsJson())
        {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
        else
        {
            \Session::flash('warning',$exception->getMessage());
            if (\Auth::guard('web')->check())
            {
                return redirect(route('home'));
            }
            if (\Auth::guard('admin')->check())
            {
                return redirect(route('dashboard.index'));
            }
        }
    }
}

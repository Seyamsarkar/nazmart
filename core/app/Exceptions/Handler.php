<?php

namespace App\Exceptions;

use GuzzleHttp\Middleware;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\URL;
use phpDocumentor\Reflection\Types\Array_;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {

        if(request()->is('api/*')){
            $message =  $exception->getMessage();
            $get_status_code = $exception->getStatusCode();
            return response()->json($message,$get_status_code);
        }

        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {

                $full_url = URL::current();
                $url_array = explode('/', $full_url);
                unset($url_array[0], $url_array[1]);
                $base_url = explode('.' ,current($url_array));

                if (count($base_url) == 2)
                {
                    return response()->view('errors.landlord-404');
                }
                elseif (count($base_url) == 3)
                {
                    return response()->view('errors.tenant-404');
                }

                abort(403);
            }
            if ($exception->getStatusCode() == 500 ) {
                return response()->view('errors.500');
            }
        }
        return parent::render($request, $exception);
    }
}

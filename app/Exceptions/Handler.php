<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    // รายการ exception ที่ไม่ต้องรายงาน
    protected $dontReport = [
        //
    ];

    // รายการ input ที่ไม่ต้องส่งกลับใน validation error
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            \Log::error('Exception caught: '.$e->getMessage());
        });
    }
}

<?php

namespace App\Http\Controllers;
use App\Model\Common;
use Illuminate\Http\Request;

class CommonController extends Controller
{
     /**
     * Response Code and Message
     *
     * @param  $code
     * @return $response
     */
    public static function responseCodeMessage($code) {
    	$message = Common::getMessageByCode($code);
        $response["code"] = $message["code"];
        $response["message"] = $message["message"];

        return $response;
    }
}

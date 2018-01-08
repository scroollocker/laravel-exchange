<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfferController extends Controller
{

    public function getMyOffers(Request $request) {

        try {
            $user = \Auth::user();

            if (is_null($user)) {
                throw new \Exception('Пользователь не авторизован');
            }


        }
        catch(\Exception $ex) {
            \Log::error('get offer Exception');
            \Log::error($ex);

            return response()->json(array(
                'status' => false,
                'message' => $ex->getMessage()
            ));
        }

        $user = \Auth::user();



    }


}

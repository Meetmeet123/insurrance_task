<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use Illuminate\Routing\Controller as BaseController;


class APIController  extends Controller
{

    public function index(){

        return view('api_form');
    }


    public function get_form(Request $request){

        $age_band = [
            61 => '61 - 65 years', 
            66 => '66 - 70 years',
            71 => '71 - 75 years', 
            76 => '> 75 years'
        ];

        $request->age_band = "";
    
        if($request->age >= 61 && $request->age <= 65){
            $request->age_band = "61 - 65 years";
        }else if($request->age >= 66 && $request->age <= 70){
            $request->age_band = "66 - 70 years";
        }else if($request->age >= 71 && $request->age <= 75){
            $request->age_band = "71 - 75 years";
        }else{
            if($request->age > 75){
                $request->age_band = "> 75 years";
            }
        }
  

        $apiURL = 'https://internal.insurance4life.in/care_token.php';
        $parameters = ['api_key' => "SIBL"];
  
        $response = Http::get($apiURL, $parameters);
  
        $statusCode = $response->status();
        $res = json_decode($response->getBody(), true);
  

        $access_token = $res['data']['accessToken'];


        $field_54 = $request->pincode;
        $field_2 =  $request->sum_insured;
        $field_3 =  $request->age_band;



        $header = [
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer '.$access_token
        ];
        

        $post_form = [
            "partnerId" => "347", 
            "abacusId" => "5031", 
            "postedField" => [
            "field_54" => $field_54, 
            "field_1" => 1, 
            "field_10" => 0, 
            "field_9" => "Individual", 
            "field_3" => $field_3, 
            "field_2" => $field_2, 
            "field_4" => "1 Year", 
            "field_NS" => "Resident of India", 
            "field_GC" => "", 
            "customerType" => "New", 
            "outPutField" => "field_8", 
            "field_OPD" => "0", 
            "field_NCB" => "0", 
            "field_34" => "0", 
            "field_SS" => "0", 
            "field_HomeCare" => "0", 
            "field_CPW" => "0", 
            "field_CS" => "0", 
            "field_35" => "1", 
            "field_AHC" => "1", 
            "field_43" => "1", 
            "field_PED_TENURE" => "0", 
            "field_OPD_SI" => "0" 
        ] ];

        $form_res = Http::withHeaders($header)->withOptions([
                "verify" => false,
            ])
            ->post("https://internal.insurance4life.in/care_quote.php",
            $post_form
        );
    

            // Handle API response and error logging
        if ($form_res->successful()) {
            $form_data = [
                "name" => $form_res['data']['abacusData']['title'],
                "amount" => $form_res['data']['grandTotal']['selectedValue']
            ];
        } else {
            // Log the error details
            \Log::error('API request failed: ' . $form_res->status() . ' - ' . $form_res->body());

            // Handle the error response or return a generic error
            return response()->json(['error' => 'API request failed'], 500);
        }

        return $form_data;
    

    }

}

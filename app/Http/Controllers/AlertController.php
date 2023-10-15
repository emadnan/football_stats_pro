<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Alert;
use App\Models\AlertQuery;
use App\Models\AlertRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AlertController extends Controller
{
    public function Createalert()
    {
        $user_id = \Auth::user()->id;
        $validator = $this->alertValidation();
        if ($validator->fails()) {
            return response()->json($validator->errors(),401);
        }else{
            try {
                $alert = new Alert();
                $alert->title = \Request::input('title');
                $alert->description = \Request::input('description');
                $alert->user_id = $user_id;
                $alert->is_on = \Request::input('is_on');
                $result = $alert->save();
                $this->alertQuery($alert->id);
                return response()->json(['message' => 'success']);
            }
            catch (\Exception $e) {
                DB::rollback();
                return response()->json($e->getMessage(),422);
            }
        }

    }
    public function alertQuery($alertId)
    {
        $queries = \Request::input('queries');
        if (!empty($queries)) {
            foreach ($queries as $key => $value) {
                $queries = new AlertQuery();
                $queries->alert_id = $alertId;
                $queries->is_AND = $value['is_AND'];
                $result = $queries->save();
                $this->alertRules($queries->id);
            }

        }
    
    } 
    public function alertRules($queryId)
    {
        $rules = \Request::input('rules');
        if (!empty($rules)) {
            foreach ($rules as $key => $value) {
                $rules = new AlertRules();
                $rules->alert_query_id = $queryId;
                $rules->dropdown1_id = $value['dropdown1_id'];
                $rules->operator_id = $value['operator_id'];
                $rules->input_value = $value['input_value'];
                $result = $rules->save();

            }
        }

    }  
    public function getAlerts()
    {
        $alert = Alert::with('queries.rules')
        ->get();
        return response()->json(['alerts' => $alert]);
    }
    //Eidt API
    public function updateAlert($id)
    {
        $alert = DB::table('alerts')->where('id', $id)->update([
            'title' => \Request::input('title'),
            'description' => \Request::input('description'),
            'is_on' => \Request::input('is_on')

        ]);

        $this->alertDetails($id);
        return response()->json(['message' => 'updated']);

    }
    public function alertDetails($alertId)
    {
        $details = \Request::input('details');
        DB::table('alerts_details')->where('alerts_id',$alertId)->delete();
        if (!empty($details)) {
            foreach ($details as $key => $value) {
                $alertsDetail = new AlertsDetail();
                $alertsDetail->alerts_id = $alertId;
                $alertsDetail->dropdown1_id = $value['dropdown1_id'];
                $alertsDetail->operator_id = $value['operator_id'];
                $alertsDetail->input_value = $value['input_value'];
                $alertsDetail->is_AND = $value['is_AND'];
                $result=$alertsDetail->save();
                
            }
        }
    }
    // Delete API
    public function destroyAlert($id)
    {
        $delete = Alert::destroy($id);
        $deleteDetail = AlertsDetail::where('alerts_id', $id)->delete();
        return response()->json(['message' => 'deleted']);
    }
    function alertValidation(){
        $rules = array(
            "title"=> "required",
            "description"=> "required",
        // "user_id"=>"required",
            "is_on"=>"required",
        // "details.*.alerts_id" => "required",
            "details.*.dropdown1_id" => "required",
            "details.*.operator_id" => "required",
            "details.*.input_value" => "required",
            "details.*.is_AND" => "required"
        );
        $validator = Validator::make(\Request::all(), $rules);
        return $validator;
    }
}

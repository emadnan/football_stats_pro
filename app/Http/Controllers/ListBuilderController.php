<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ListBuilder;
use App\Models\ListBuildersDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ListBuilderController extends Controller
{
    public function CreateListBuilder()
    {
        $user_id = \Auth::user()->id;
        $validator = $this->listBuilderValidation();
        if ($validator->fails()) {
            return response()->json($validator->errors(),401);
        }else{
            try {
        $list = new ListBuilder();
        $list->title = \Request::input('title');
        $list->description = \Request::input('description');
        $list->user_id = $user_id;
        $list->is_on = \Request::input('is_on');
        $result = $list->save();
        $this->addListBuildertDetail($list->id);
        return response()->json(['message' => 'success']);
    }
    catch (\Exception $e) {
                DB::rollback();
                return response()->json($e->getMessage(),422);
            }
}

    }
    public function addListBuildertDetail($listId)
    {
        $details = \Request::input('details');
        if (!empty($details)) {
            foreach ($details as $key => $value) {
        $detail = new ListBuildersDetail();
        $detail->list_id = $listId;
        $detail->dropdown1_id = $value['dropdown1_id'];
        $detail->operator_id = $value['operator_id'];
        $detail->input_value = $value['input_value'];
        $detail->is_AND = $value['is_AND'];
        $result = $detail->save();

    }
    }
    } 
    public function getListBuilder()
    {
        $list = ListBuilder::with('details')
        ->get();
        return response()->json(['list_builders' => $list]);
    }
    //Eidt API
     public function updateListBuilder($id)
    {
        $alert = DB::table('list_builders')->where('id', $id)->update([
            'title' => \Request::input('title'),
            'description' => \Request::input('description'),
            'is_on' => \Request::input('is_on')

        ]);

        $this->listDetails($id);
        return response()->json(['message' => 'updated']);

    }
    public function listDetails($listId)
    {
        $details = \Request::input('details');
        DB::table('list_builders_details')->where('list_id',$listId)->delete();
        if (!empty($details)) {
            foreach ($details as $key => $value) {
                $listDetail = new ListBuildersDetail();
                $listDetail->list_id = $listId;
                $listDetail->dropdown1_id = $value['dropdown1_id'];
                $listDetail->operator_id = $value['operator_id'];
                $listDetail->input_value = $value['input_value'];
                $listDetail->is_AND = $value['is_AND'];
                $result=$listDetail->save();
                
            }
        }
    }
    
    // Delete API
    public function destroyList($id)
    {
        $delete = ListBuilder::destroy($id);
        $deleteDetail = ListBuildersDetail::where('list_id', $id)->delete();
        return response()->json(['message' => 'deleted']);
    }
    function listBuilderValidation(){
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

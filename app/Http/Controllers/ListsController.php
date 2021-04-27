<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// GET 	/api/lists 	        index (get all)	
// POST 	/api/lists/ 	   store
// PUT 	/api/lists/{id} 	update 
// DELETE 	/api/lists/{id} 	destroy 

class ListsController extends Controller
{
  public function index(Request $request){
      $requestuser = $request->user();
      $user = User::find($requestuser->id);
      $lists = $user->todolists;
      return response($lists->jsonSerialize(), Response::HTTP_OK);
  }

  //The request is in body. 
  public function store(Request $request){
        $requestuser = $request->user();
      $user = User::find($requestuser->id);
      $list = new Todolist();
      $list->name = $request->name;
      $user->todolists()->save($list);
      $list->save();
      return response($list->jsonSerialize(), Response::HTTP_OK);
  }

  //id in url, name in body
  public function update(Request $request, $id){
      $list = Todolist::find($id);
        $list->name = $request->name;
        $list->save();
      return response($list->jsonSerialize(), Response::HTTP_OK);
  }

  public function destroy($id){
      Todolist::destroy($id);
      return response(null, Response::HTTP_OK);
}

}
<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use App\Models\Todoitem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// GET 	/api/items 	        index (get all)	
// POST 	/api/items/ 	   store
// PUT 	/api/items/{id} 	update 
// DELETE 	/api/items/{id} 	destroy 

class ItemsController extends Controller
{
//I've just got user id set at 1, index() is get and has no body
  public function index(Request $request){
      $requestuser = $request->user();
      $user = User::find($requestuser->id);
      $items = $user->items;
      return response($items->jsonSerialize(), Response::HTTP_OK);
  }

  //POST to /api/items, in request: user id, list id, item content
  public function store(Request $request){
    $requestuser = $request->user();
      $user = User::find($requestuser->id);
      $list = Todolist::find($request->list);
      $item = new Todoitem();
      $item->content = $request->content;
      $item->todolist_id = $request->list;
      $user->items()->save($item);
      $list->items()->save($item);
      $item->save();
      return response($item->jsonSerialize(), Response::HTTP_OK);
  }

  //PUT to /api/items/id, in url id, in request old list, new list, content
  public function update(Request $request, $id){
      $item = Todoitem::find($id);
      if($request->oldlist!==$request->newlist){
        $oldlist = Todolist::find($request->oldlist);
        $newlist = Todolist::find($request->newlist);
    //I guess this is all I need to do??? tinker shows it's changed
        $newlist->items()->save($item);
      }
      if($request->content!=="NO_CHANGE"){
        $item->content = $request->content;
        $item->save();
      }
      return response($item->jsonSerialize(), Response::HTTP_OK);
  }

//DELETE to /api/items/id, in url id, in request old list, new list, content
  public function destroy($id){
      $deletedid = $id;
      Todoitem::destroy($id);
      return response(null, Response::HTTP_OK);
}

}
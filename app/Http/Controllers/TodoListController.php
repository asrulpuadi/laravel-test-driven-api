<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Http\Requests\TodoListRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $lists = TodoList::all();

        return response($lists);
    }

    public function show (TodoList $todo_list)
    {
        return response($todo_list);
    }

    public function store(TodoListRequest $request)
    {
        $todo_list = TodoList::create($request->all());

        return response($todo_list,Response::HTTP_CREATED);
    }

    public function destroy(TodoList $todo_list)
    {
        $todo_list->delete();

        return response('',Response::HTTP_NO_CONTENT);
    }

    public function update(TodoListRequest $request,TodoList $todo_list)
    {        
        $todo_list->update($request->all());

        return response($todo_list);
    }
}

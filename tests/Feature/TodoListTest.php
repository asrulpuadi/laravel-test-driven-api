<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;

    public function setUp():void
    {
        parent::setUp();
        $this->list = $this->createTodoList();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_todo_list()
    {
        /* preparation like prepare */

        /* action like perfom */
        $response = $this->getJson(route('todo-list.index'));

        /* assertion like predict */
        $this->assertEquals(1,count($response->json()));
    }

    public function test_fetch_single_todo_list()
    {
        /* preparation */

        /* action */
        $response = $this->getJson(route('todo-list.show',$this->list->id))
                        ->assertOk()
                        ->json();

        /* assertion */
        $this->assertEquals($response['name'],$this->list->name);
    }

    public function test_store_new_todo_list()
    {
        // preparation
        $list = TodoList::factory()->make();

        // action
        $response = $this->postJson(route('todo-list.store'),['name' => $list->name])
            ->assertCreated()
            ->json();

        //assertion
        $this->assertEquals($list->name,$response['name']);

        $this->assertDatabaseHas('todo_lists',['name' => $list->name]);
    }

    public function test_while_storing_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();

        $this->postJson(route('todo-list.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
    }

    public function test_delete_todo_list()
    {
        $this->deleteJson(route('todo-list.destroy',[$this->list->id]))
        ->assertNoContent();

        $this->assertDatabaseMissing('todo_lists',['name' => $this->list->name]);
    }

    public function test_update_todo_list()
    {
        //preparation
        $this->patchJson(route('todo-list.update',$this->list->id),['name' => 'updated name'])
        ->assertOk();

        //action
        $this->assertDatabaseHas('todo_lists',[
            'id' => $this->list->id,
            'name' => 'updated name'
        ]);
    }

    public function test_while_updating_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();

        $this->patchJson(route('todo-list.update',$this->list->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
    }
}

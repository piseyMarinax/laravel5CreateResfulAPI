<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\API\PostAPIController;
use App\Http\Controllers\HttpClient;
use App\Http\Controllers\Route;
use App\Post;

class PostController extends Controller
{
    
    public function index()
    {

        $postAPIController = new PostAPIController();
        $JsonData = ($postAPIController->index());
        $posts = $JsonData->getData()->data;
        return view('posts/index',compact('posts'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function show($id)
    {
        $postAPIController = new PostAPIController();
        $response = ($postAPIController->show($id));

        if($response->getData()->success)
        {
            $post = $response->getData()->data;
            return view('posts/show',compact('post'));
        }

        return redirect()->route('posts.index')
                        ->with('failse','Posts failse to show .');
    }

     public function create()
    {
        return view('posts/create');
    }

     public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $postAPIController = new PostAPIController();
        $response = $postAPIController->store($request);

        if($response->getData()->success)
        {
            return redirect()->route('posts.index')
                        ->with('success','Posts created successfully.');
        }

        return redirect()->route('posts.index')
                        ->with('failse','Posts failse to create .');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $Post)
    {
        $postAPIController = new PostAPIController();
        $response = $postAPIController->show($Post->id);

        if($response->getData()->success)
        {
             $post = $response->getData()->data;
            return view('posts.edit',compact('post'));
        }

         return redirect()->route('posts.index')
                        ->with('failse','failse to edit');
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $Post)
    {
        request()->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $postAPIController = new PostAPIController();
        $response = ($postAPIController->update($request, $Post->id));

        if($response->getData()->success)
        {
             return redirect()->route('posts.index')
                        ->with('success','Posts updated successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $Post)
    {
        $postAPIController = new PostAPIController();
        $response = ($postAPIController->destroy($Post->id));

        if($response->getData()->success)
        {
            return redirect()->route('posts.index')
                    ->with('success','Posts deleted successfully');
        }
       
    }
}

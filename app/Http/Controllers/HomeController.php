<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // dd($request->route()->getName());
        // dd(Route::currentRouteName());
        // dd($request->path());
        // dd($request->is('/'));
        // dd($request->routeIs('mo'));
        // dd($request->url());
        // dd($request->fullUrl());
        // dd($request->fullUrlWithoutQuery('age'));
        // dd($request->method());
        // dd($request->isMethod('POST'));
        // dd($request->all());
        // dd($request->collect());
        // dd($request->input());
        // dd($request->query());
        // dd($request->boolean('is_admin'));
        // dd($request->only('name'));
        // dd($request->except('name'));
        // dd($request->date('date'));

        //input presence// has , filled , missing 
        // dd($request->has('name'));
        // dd($request->hasAny(['name' ,'qq']));
        // dd($request->whenHas('aa' ,function(){
        //     dd('yes');
        // },function(){
        //     dd('no');
        // }));

        // dd($request->filled('name'));
        // dd($request->anyFilled(['name' ,'age']));
        //   dd($request->whenFilled('age' ,function(){
        //     dd('yes');
        // },function(){
        //     dd('no');
        // }));

        // dd($request->missing('id'));
        //     dd($request->whenMissing('id' ,function(){
        //     dd('yes');
        // },function(){
        //     dd('no');
        // }));
        
        
        return view('welcome');
    }
}

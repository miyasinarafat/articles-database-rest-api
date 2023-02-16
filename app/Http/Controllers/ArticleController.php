<?php

namespace App\Http\Controllers;

use App\Domain\Article\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article): Response
    {
        //
    }
}

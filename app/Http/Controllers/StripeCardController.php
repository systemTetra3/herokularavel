<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStripeCardRequest;
use App\Http\Requests\UpdateStripeCardRequest;
use App\Models\StripeCard;

class StripeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStripeCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStripeCardRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function show(StripeCard $stripeCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function edit(StripeCard $stripeCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStripeCardRequest  $request
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStripeCardRequest $request, StripeCard $stripeCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StripeCard  $stripeCard
     * @return \Illuminate\Http\Response
     */
    public function destroy(StripeCard $stripeCard)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Http\Requests\UpdateUrlRequest;
use App\Models\Url;
use Inertia\Inertia;
use GuzzleHttp\Client;


class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $urls = Url::select("title", "url", "description", "favicon", "created_at", "updated_at")
        ->orderBy("created_at", "desc")
        ->paginate(10);

        return Inertia::render("Urls/Index", [
            "urls" => $urls
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $urls = Url::select("title", "url", "description")->get();

        return Inertia::render("Urls/Create", [
            "urls" => $urls
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUrlRequest $request)
    {
        $client = new Client();

        $response = $client->request("GET", "https://api.urlmeta.org/", [
            "query" => [
                "url" => $request->url
            ],
            "headers" => [
                "Authorization" => "Basic YmFiYXRhdHN1eWFhQGdtYWlsLmNvbTpuVFFubEJTMFpGcGJGRVlxYnZQUg=="
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $url = Url::create([
            "url" => $request->url,
            "title" => $data["meta"]["title"],
            "favicon" => $data["meta"]["site"]["favicon"],
            "description" => $data["meta"]["description"] ?? ""
        ]);

        return redirect()->back()->with('success', 'URL has been saved.')->with('url', $url);
    }

    /**
     * Display the specified resource.
     */
    public function show(Url $url)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Url $url)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUrlRequest $request, Url $url)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Url $url)
    {
        //
    }
}

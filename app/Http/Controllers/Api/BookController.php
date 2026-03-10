<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Requests\Api\StoreBookRequest;
use App\Http\Requests\Api\UpdateBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Book::class);
        return BookResource::collection(Book::with('category')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $this->authorize('create', Book::class);
        $bookField = $request->validated();
        $book = Book::create($bookField);
        $book->load('category');
        return new BookResource($book);
    }
    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $book->increment('views');
        $book->load('category');
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $this->authorize('update', $book);
        $bookField = $request->validated();
        $book->update($bookField);
        $book->load('category');
        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();
        return response()->noContent();
    }
}

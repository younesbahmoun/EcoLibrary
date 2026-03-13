<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Requests\Api\StoreBookRequest;
use App\Http\Requests\Api\UpdateBookRequest;
use App\Services\Api\BookService;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', Book::class);
        $query = Book::with('category');
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                ->orWhere('author', 'like', "%{$request->search}%");
            });
        }


        // if ($request->filled('title')) {
        //     $query->where('title', 'like', "%{$request->title}%");
        // }

        // if ($request->filled('author')) {
        //     $query->where('author', 'like', "%{$request->author}%");
        // }

        return BookResource::collection($query->get());
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
        // $this->authorize('view', $book);
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

    public function reserve(Book $book, BookService $bookService) {
        // $this->authorize('reserve', $book);
        // $bookService->reserverBock($book);
        if ($book->statut === 'disponible') {
            $book->update(['statut' => 'reserved']);
            return response()->json(['message' => 'Book reserved successfully.']);
        } else {
            return response()->json(['message' => 'Book is not available for reservation.'], 400);
        }
    }

    public function cancel(Book $book, BookService $bookService) {
        // $this->authorize('cancel', $book);
        // $bookService->calncelReserverBock($book);
        if ($book->statut === 'reserved') {
            $book->update(['statut' => 'disponible']);
            return response()->json(['message' => 'Book cancel reserved successfully.']);
        } else {
            return response()->json(['message' => 'Book is not reserved.'], 400);
        }
    }
}

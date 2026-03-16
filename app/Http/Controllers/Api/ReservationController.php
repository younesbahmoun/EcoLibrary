<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function reserve(Request $request, Book $book)
    {
        Gate::authorize('reserve', $book);

        $book->update(['statut' => 'reserved']);

        return response()->json([
            'message' => 'Book reserved successfully.',
            'book'    => new BookResource($book),
        ]);
    }

    public function cancel(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $book->update(['statut' => 'disponible']);

        return response()->json([
            'message' => 'Reservation cancelled.',
            'book'    => new BookResource($book),
        ]);
    }

    public function index()
    {
        // Gate::authorize('create', Book::class); // Admin
        return BookResource::collection(
            Book::where('statut', 'reserved')->with('category')->get()
        );
    }
}
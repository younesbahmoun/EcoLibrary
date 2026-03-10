<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;

class StatisticController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Book::class);

        return response()->json([
            'total_books'      => Book::count(),
            'total_categories' => Category::count(),
            'total_reserved'   => Book::where('statut', 'reserved')->count(),
            // 'total_degraded'   => Book::where('statut', 'degraded')->count(),
            'most_consulted'   => BookResource::collection(
                Book::orderBy('views', 'desc')->with('category')->take(10)->get()
            ),
            // 'new_arrivals'     => BookResource::collection(
            //     Book::where('is_new', true)->take(10)->get()
            // ),
            // 'degraded_books'   => BookResource::collection(
            //     Book::where('statut', 'degraded')
            //         ->select('id', 'title', 'quantity_degraded', 'quantity_total')
            //         ->get()
            // ),
        ]);
    }
}
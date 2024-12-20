<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrUpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $query = Book::query();

        if (request()->has('author')) {
            $query->where('author', 'like', '%' . request('author') . '%');
        }

        if (request()->has('year')) {
            $query->where('year', request('year'));
        }

        if (request()->has('title')) {
            $query->where('title', 'like', '%' . request('title') . '%');
        }

        $query->orderBy(request('sort', 'title'), request('order', 'asc'));

        $books = $query->get();

        return response()->json($books);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrUpdateBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return response()->json($book, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrUpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(null, 204);
    }
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }
}

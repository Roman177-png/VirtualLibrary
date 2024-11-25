<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, $bookId)
    {
        $validated = $request->validated();

        $review = Review::create([
            'book_id' => $bookId,
            'name' => $validated['name'],
            'rating' => $validated['rating'],
            'content' => $validated['content'],
        ]);

        return response()->json($review, 201);
    }

    public function update(ReviewRequest $request, $bookId, $reviewId)
    {
        $book = Book::findOrFail($bookId);

        $review = $book->reviews()->findOrFail($reviewId);

        $review->update($request->validated());

        return response()->json($review, 200);
    }

    public function destroy($bookId, $reviewId)
    {
        $book = Book::findOrFail($bookId);

        $review = $book->reviews()->findOrFail($reviewId);

        $review->delete();

        return response()->json(null, 204);
    }

    public function index($bookId): JsonResponse
    {
        $query = Review::where('book_id', $bookId);

        if (request()->has('rating') && request('rating') !== '0') {
            $query->where('rating', request('rating'));
        }

        $query->orderBy(request('sort', 'created_at'), request('order', 'desc'));

        $reviews = $query->get();

        return response()->json($reviews);
    }


}

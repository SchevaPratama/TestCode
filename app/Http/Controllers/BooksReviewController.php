<?php

namespace App\Http\Controllers;

use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookReviewRequest;
use App\Http\Resources\BookReviewResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

use function PHPUnit\Framework\isNull;

class BooksReviewController extends Controller
{
    public function __construct()
    {
    }

    public function store(int $bookId, PostBookReviewRequest $request)
    {
        // @TODO implement
        try {
            $checkBook = Book::find($request->id);

            if (is_null($checkBook)) {
                return response()->json([], 404);
            }
            $validator = Validator::make($request->all(), [
                'review' => 'required|integer|between:1,10',
                'comment' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->first(), 422);
            }

            $finalData = [
                'book_id' => $request->bookID,
                'user_id' => $request->userID,
                'comment' => $request->comment,
                'review' => $request->review,
            ];
            $bookReview = BookReview::create($finalData);
            return (new BookReviewResource($bookReview, 201));
        } catch (\Throwable $errorMessage) {
            return $errorMessage->getMessage();
        }
    }

    public function destroy(int $bookId, int $reviewId, Request $request)
    {
        // @TODO implement
        $deleteBook = BookReview::where('book_id', '=', $bookId)->where('id', $reviewId)->first();
        if ($deleteBook == null) {
            return response()->json([
                'message' => 'Book Not Found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'review' => 'required|integer|between:1,10',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 204);
        }

        $deleteBook->delete();
        return response()->json([], 204);
    }
}

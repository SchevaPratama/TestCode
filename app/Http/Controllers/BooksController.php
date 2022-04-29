<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\BookReview;
use App\Http\Requests\PostBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        // @TODO implement
    }

    public function store(PostBookRequest $request)
    {
        // @TODO implement
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|unique:books|min:13|max:13',
            'title' => 'required|string',
            'description' => 'required|string',
            'authors'   => 'required|array',
            'authors.*'   => 'integer',
            'published_year' => 'required|integer|between:1900,2020',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 422);
        }

        $author = Author::find($request->authors[0]);
        if ($author == null) {
            return response()->json('Author Not Found', 422);
        }

        $bookData = [
            'isbn' => $request->isbn,
            'title' => $request->title,
            'description' => $request->description,
            'published_year' => $request->published_year
        ];

        $book = Book::create($bookData);
        $book->authors()->attach($author);
        return (new BookResource($book, 201));
    }
}

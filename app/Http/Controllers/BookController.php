<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * Class BookController
 * @package App\Http\Controllers
 */
class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::paginate();

        return view('book.index', compact('books'))
            ->with('i', (request()->input('page', 1) - 1) * $books->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $book = new Book();
        $categories = Category::pluck('name', 'id');
        $status = ['Disponible' => 'Disponible', 'No Disponible' => 'No Disponible'];

        return view('book.create', compact('book', 'categories', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Book::$rules);

        $book = Book::create($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Libro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);

        return view('book.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        $categories = Category::pluck('name', 'id');
        $status = $book->status ?? null;

        return view('book.edit', compact('book', 'categories', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Book $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        request()->validate(Book::$rules);

        $book->update($request->all());

        return redirect()->route('books.index')
            ->with('success', 'Libro editado exitosamente.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $book = Book::find($id)->delete();

        return redirect()->route('books.index')
            ->with('success', 'Libro borrado exitosamente.');
    }
}

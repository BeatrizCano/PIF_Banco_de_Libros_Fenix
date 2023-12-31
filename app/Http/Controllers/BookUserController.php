<?php

namespace App\Http\Controllers;

use App\Models\BookUser;
use App\Models\User;
use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;

/**
 * Class BookUserController
 * @package App\Http\Controllers
 */
class BookUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookUsers = BookUser::paginate();

        return view('book-user.index', compact('bookUsers'))
            ->with('i', (request()->input('page', 1) - 1) * $bookUsers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bookUser = new BookUser();
        $books = Book::pluck('title', 'id');
        $users = User::pluck('name', 'id');
        $loanDate = ''; 
        $returnDate = ''; 
        $status = ''; 

        return view('book-user.create', compact('bookUser', 'books', 'users', 'loanDate','returnDate', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(BookUser::$rules);

        $bookUser = BookUser::create([
            'book_id' => $request->input('book_id'),
            'user_id' => $request->input('user_id'),
        ]);
    
        if ($request->has('loan_date') && $request->has('return_date')) {
            $bookUser->bookLoan()->create([
                'loan_date' => $request->input('loan_date'),
                'return_date' => $request->input('return_date'),
            ]);
        }
    
        if ($request->has('book_id')) {
            $book = Book::find($request->input('book_id'));
            $book->status = $request->input('status');
            $book->save();
        }
    
        return redirect()->route('book-users.index')
            ->with('success', 'Préstamo creado exitosamente.');

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bookUser = BookUser::with('book', 'user')->find($id);

        return view('book-user.show', compact('bookUser'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bookUser = BookUser::with('book', 'user', 'bookLoan')->find($id);
        $books = Book::pluck('title', 'id');
        $users = User::pluck('name', 'id');

        $book = $bookUser->book;
        $status = $bookUser->status ?? null;
        $loanDate = $bookUser->bookLoan->loan_date ?? null;
        $returnDate = $bookUser->bookLoan->return_date ?? null;

        return view('book-user.edit', compact('bookUser', 'books', 'users', 'status', 'loanDate', 'returnDate'));
    }


    /**
     * 
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  BookUser $bookUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookUser $bookUser)
    {
        request()->validate(BookUser::$rules);
    
        $loan_date = $request->input('loan_date');
        $return_date = $request->input('return_date');
    
        $bookUser->update([
            'book_id' => $request->input('book_id'),
            'user_id' => $request->input('user_id'),
        ]);
    
        $bookUser->bookLoan()->updateOrCreate([], [
            'loan_date' => $loan_date,
            'return_date' => $return_date,
        ]);
    
        if ($request->has('book_id')) {
            $status = $request->input('status');
            $book = Book::find($request->input('book_id'));
            $book->status = $status;
            $book->save();
        }
    
        return redirect()->route('book-users.index')
            ->with('success', 'Préstamo editado exitosamente.');
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $bookUser = BookUser::find($id)->delete();

        return redirect()->route('book-users.index')
            ->with('success', 'Préstamo eliminado exitosamente');
    }
}

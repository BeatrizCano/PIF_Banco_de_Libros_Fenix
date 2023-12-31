@extends('layouts.app')

@section('template_title')
    Book User
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Préstamos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('book-users.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Crear Nuevo') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead">
                                <tr>
                                    <th>No</th>
                                    <th>Libro Id</th>
                                    <th>Título Libro</th>                                    
                                    <th>Usuario Id</th>                                       
                                    <th>Nombre Usuario</th>
                                    <th>Fecha Entrega</th>
                                    <th>Fecha Devolución</th>
                                    <th>Estado</th>
                                    <th>Acciones</th> <!-- Agregamos una columna para las acciones -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookUsers as $bookUser)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $bookUser->book_id }}</td>
                                        <td>{{ $bookUser->book ? $bookUser->book->title : 'N/A' }}</td>                                        
                                        <td>{{ $bookUser->user_id }}</td>
                                        <td>{{ $bookUser->user ? $bookUser->user->name : 'N/A' }}</td>
                                        <td>{{ $bookUser->bookLoan ? $bookUser->bookLoan->loan_date : 'N/A' }}</td>
                                        <td>{{ $bookUser->bookLoan ? $bookUser->bookLoan->return_date : 'N/A' }}</td>
                                        <td>{{ $bookUser->book ? $bookUser->book->status : 'N/A' }}</td>   

                                        <td>
                                            <form action="{{ route('book-users.destroy',$bookUser->id) }}" method="POST">
                                                <a class="btn btn-success btn-sm custom-btn" href="{{ route('book-users.show',$bookUser->id) }}">Ver</a>
                                                <a class="btn btn-primary btn-sm custom-btn" href="{{ route('book-users.edit',$bookUser->id) }}">Editar</a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm custom-btn">Borrar</button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>
                {!! $bookUsers->links() !!}
            </div>
        </div>
    </div>
@endsection

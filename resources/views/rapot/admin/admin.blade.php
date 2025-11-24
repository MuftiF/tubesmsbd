@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Generate Rapot</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->role }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.rapot.generate', $u->id) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm">Generate Rapot</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

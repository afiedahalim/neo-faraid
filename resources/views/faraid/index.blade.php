@extends('layouts.app')

@section('content')

<div class="container">

    <h3>NeoFaraid – Telegram Calculations</h3>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Deceased</th>
            <th>Date of Death</th>
            <th>Total Estate (RM)</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
        @foreach($cases as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->deceased_name }}</td>
                <td>{{ $c->death_date }}</td>
                <td>{{ number_format($c->total_estate,2) }}</td>
                <td>
                    <a href="{{ url('/faraid-cases/'.$c->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $cases->links() }}

</div>

@endsection
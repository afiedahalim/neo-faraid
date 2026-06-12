@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Faraid Case #{{ $case->id }}</h3>

    <p>
        <strong>Deceased:</strong> {{ $case->deceased_name }}<br>
        <strong>Date of Death:</strong> {{ $case->death_date }}<br>
        <strong>Total Estate:</strong> RM {{ number_format($case->total_estate,2) }}
    </p>

    <hr>

    <h5>Heirs</h5>

    <table class="table table-sm table-bordered">
        <thead>
        <tr>
            <th>Heir</th>
            <th>Quantity</th>
        </tr>
        </thead>
        <tbody>
        @foreach($case->heirs as $h)
            <tr>
                <td>{{ $h->heir_key }}</td>
                <td>{{ $h->quantity }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr>

    <h5>Distribution</h5>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Heir</th>
            <th>Fraction</th>
            <th>Percentage</th>
            <th>Amount (RM)</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        @foreach($case->results as $r)
            <tr>
                <td>{{ $r->heir_name }}</td>
                <td>{{ $r->fraction }}</td>
                <td>{{ $r->percentage }}%</td>
                <td>{{ number_format($r->amount,2) }}</td>
                <td>{{ $r->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

@endsection
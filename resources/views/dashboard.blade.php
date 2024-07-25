@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Summary</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Income</div>
                <div class="card-body">
                    @if(isset($totalIncome))
                        ${{ number_format($totalIncome, 2) }}
                    @else
                        Total Income is not available.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Expenses</div>
                <div class="card-body">
                    @if(isset($totalExpenses))
                        ${{ number_format($totalExpenses, 2) }}
                    @else
                        Total Expenses is not available.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Balance</div>
                <div class="card-body">
                    @if(isset($balance))
                        ${{ number_format($balance, 2) }}
                    @else
                        Balance is not available.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

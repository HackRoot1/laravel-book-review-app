@extends('layouts.app')

@section('main')

<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Edit Review
                </div>
                <div class="card-body pb-3">

                    <form action="{{ route('account.reviews.update', $review->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control @error('review') is-invalid @enderror" placeholder="Review" name="review" id="review">{{ old('review', $review->review) }}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                                <option value="1" {{ ($review->status == 1) ? "selected" : "" }}>Active</option>
                                <option value="0" {{ ($review->status == 0) ? "selected" : "" }}>Block</option>
                            </select>
                            @error('status')
                            <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button class="btn btn-primary my-2">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
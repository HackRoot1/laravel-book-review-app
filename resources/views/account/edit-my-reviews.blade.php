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

                    <form action="{{ route('account.edit.updateReview', $review->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="review" class="form-label">Book</label>
                            <input type="text" class="form-control" value="{{ $review->book->title }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea class="form-control @error('review') is-invalid @enderror" placeholder="Review" name="review" id="review">{{ old('review', $review->review) }}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror">
                                <option value="1" {{ ($review->rating == 1) ? "selected" : "" }}>1</option>
                                <option value="2" {{ ($review->rating == 2) ? "selected" : "" }}>2</option>
                                <option value="3" {{ ($review->rating == 3) ? "selected" : "" }}>3</option>
                                <option value="4" {{ ($review->rating == 4) ? "selected" : "" }}>4</option>
                                <option value="5" {{ ($review->rating == 5) ? "selected" : "" }}>5</option>
                            </select>
                            @error('rating')
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
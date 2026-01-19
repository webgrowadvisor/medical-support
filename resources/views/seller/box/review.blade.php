

        <form method="POST" action="{{ route('doctor.review.store') }}">
            @csrf
            <input type="hidden" name="reviewid" value="{{ $id }}">
            {{-- <div class="form-group mb-3">
                <label>Rating <span class="text-danger">*</span></label>
                <select name="rating" required class="form-control">
                    <option value="">Select</option>
                    @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>
            </div> --}}
            <div class="form-group mb-3">
            <textarea name="review_rection" class="form-control" placeholder="Write your experience..."></textarea>
            </div>

            <button type="submit" class="form-control btn btn-primary" >Submit Rection</button>
        </form>
    
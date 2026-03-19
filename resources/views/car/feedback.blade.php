<x-app-layout :role="$role">

    <div class="feedback-container">

        <h1 class="feedback-title">App Feedback</h1>

        <p class="feedback-subtitle">
            Help us improve the platform by rating the app or reporting issues.
        </p>

        <div class="feedback-card">

            <form method="POST" action="{{ route('feedback.store') }}">

                @csrf

                <div class="form-group">
                    <label>Rate the App</label>

                    <div class="star-rating">

                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">

                            <label for="star{{ $i }}" class="star">
                                ★
                            </label>
                        @endfor

                    </div>

                </div>
                <script>
                    document.querySelectorAll('.star-rating input').forEach(star => {

                        star.addEventListener('change', function() {

                            let rating = this.value;

                            console.log("User selected rating:", rating);

                        });

                    });
                </script>

                <div class="form-group">

                    <label>Feedback Type</label>

                    <select name="type">

                        <option value="review">Review</option>
                        <option value="complaint">Complaint</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="bug">Bug Report</option>
                        <option value="general">General Comment</option>

                    </select>

                </div>

                <div class="form-group">

                    <label>Subject</label>

                    <input type="text" name="subject">

                </div>

                <div class="form-group">

                    <label>Your Message</label>

                    <textarea name="message" rows="5"></textarea>

                </div>

                <button class="feedback-btn">
                    Submit Feedback
                </button>

            </form>

        </div>


        <div class="feedback-list">

            <h2>User Feedback</h2>

            @forelse($feedbacks as $feedback)
                <div class="feedback-item">

                    <div class="feedback-user">

                        <x-user-image :user="$feedback->user" class="my-cars-img-thumbnail"/>

                        <strong>
                            {{ $feedback->user->name ?? 'Anonymous' }}
                        </strong>

                    </div>

                    <div class="feedback-content">

                        <span class="feedback-type">
                            {{ ucfirst($feedback->type) }}
                        </span>

                        @if ($feedback->rating)
                            <div class="feedback-stars">
                                {{ str_repeat('★', $feedback->rating) }}
                            </div>
                        @endif

                        <p>{{ $feedback->message }}</p>

                        <small>
                            {{ $feedback->created_at->diffForHumans() }}
                        </small>

                    </div>

                </div>
            @empty
                <h4>No feedback yet!</h4>
            @endforelse

            {{ $feedbacks->links() }}

        </div>

    </div>

</x-app-layout>

<x-app-layout :role="auth()->user()->role">

    <div class="subscription-container">

        <h1 class="subscription-title">
            Car Listing Subscription
        </h1>

        <p class="subscription-subtitle">
            Subscribe monthly to list your cars on the platform
        </p>

        <div class="plan-card">

            <h2>Monthly Plan</h2>

            <p class="price">₦5,000 / Month</p>

            <ul>
                <li>Unlimited car listings</li>
                <li>Priority search ranking</li>
                <li>Access to buyer analytics</li>
            </ul>

            <form method="POST" action="{{ route('subscription.start') }}">
                @csrf

                <input type="hidden" name="amount" value="5000">

                <button class="subscribe-btn">
                    Subscribe Now
                </button>

            </form>

        </div>

    </div>

</x-app-layout>

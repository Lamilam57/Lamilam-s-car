<x-app-layout title="Page Not Found" bodyClass="bg-light">

    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="text-center">

            <div class="mb-4">
                <h1 class="display-1 fw-bold text-primary">404</h1>
            </div>

            <h2 class="h4 fw-semibold mb-3">
                Page Not Found
            </h2>

            <p class="text-muted mb-4">
                The page you are trying to access may have been removed,
                renamed, or is temporarily unavailable.
            </p>

            <div class="d-flex justify-content-center gap-3" style="margin-top: 25px">
                <a href="{{ route('home') }}" class="btn btn-primary px-4 mt-4">
                    Go to Homepage
                </a>
                <button onclick="history.back()" class="btn btn-outline-secondary px-4">
                    Go Back
                </button>
            </div>

        </div>
    </div>

</x-app-layout>

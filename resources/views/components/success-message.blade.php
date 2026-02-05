
@if (session('success'))
    <div class="container my-large" id="success-alert">
        <div class="success-message">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) alert.remove();
            }, 3000);
        </script>
    </div>
@endif

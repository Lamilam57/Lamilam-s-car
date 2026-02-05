@if ($errors->any())
    <div class="container my-large" id="error-alert">
    @foreach ($errors->all() as $error)
        <div class="error-message">
            {{ $error }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('error-alert');
                if (alert) alert.remove();
            }, 300000);
        </script>

    @endforeach
        
    </div>
@endif
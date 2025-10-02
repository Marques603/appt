@if(session('success'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-green-500 text-white rounded shadow-lg z-50" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div id="toast" class="fixed top-0 right-0 m-4 p-4 bg-red-500 text-white rounded shadow-lg z-50" role="alert">
        <p>{{ session('error') }}</p>
    </div>
@endif

<script>
    setTimeout(() => {
        const toast = document.querySelector('.fixed.top-0.right-0');
        if (toast) toast.remove();
    }, 8000);
</script>

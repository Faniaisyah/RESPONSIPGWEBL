{{-- Success Toast --}}
@if (session()->has('success'))
  <div class="bottom-0 p-3 position-fixed end-0" style="z-index: 1000; margin-bottom: 20px;">
    <div id="liveToastSuccess" class="border-0 toast align-items-center text-bg-success" role="alert"
      aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('success') }}
        </div>
        <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
          aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    var toastLive = document.getElementById('liveToastSuccess')
    var toast = new bootstrap.Toast(toastLive)

    toast.show()
  </script>
@endif

{{-- Error Toast --}}
@if (session()->has('error'))
  <div class="bottom-0 p-3 position-fixed end-0" style="z-index: 1000; margin-bottom: 20px;">
    <div id="liveToastError" class="border-0 toast align-items-center text-bg-danger" role="alert"
      aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('error') }}
        </div>
        <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
          aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    var toastLive = document.getElementById('liveToastError')
    var toast = new bootstrap.Toast(toastLive)

    toast.show()
  </script>
@endif

{{-- Warning Toast --}}
@if (session()->has('warning'))
  <div class="bottom-0 p-3 position-fixed end-0" style="z-index: 1000; margin-bottom: 20px;">
    <div id="liveToastWarning" class="border-0 toast align-items-center text-bg-warning" role="alert"
      aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          {{ session('warning') }}
        </div>
        <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
          aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    var toastLive = document.getElementById('liveToastWarning')
    var toast = new bootstrap.Toast(toastLive)

    toast.show()
  </script>
@endif

{{-- Validation Errors Toast --}}
@if ($errors->any())
  <div class="bottom-0 p-3 position-fixed end-0" style="z-index: 1000; margin-bottom: 20px;">
    <div id="liveToastErrorValidationName" class="border-0 toast align-items-center text-bg-danger" role="alert"
      aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          @foreach ($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
        </div>
        <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
          aria-label="Close"></button>
      </div>
    </div>
  </div>
  <script>
    var toastLive = document.getElementById('liveToastErrorValidationName')
    var toast = new bootstrap.Toast(toastLive)

    toast.show()
  </script>
@endif

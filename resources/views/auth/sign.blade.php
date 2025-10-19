<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>menit.com</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
    <link rel="icon" type="https://asset.tix.id/wp-content/uploads/2022/05/TIXID_app_icon.png" href="logo.png">
</head>

<body>
    <form class="w-50 d-block mx-auto my-5" method="POST" action="{{ route('signup.send_data') }}">
        @if (Session::get('failed'))
            <div class="alert alert-danger my-3">{{ Session::get('failed') }}</div>
        @endif
        @csrf
        <div class="row mb-4">
            <div class="col">
                @error('first_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example1" class="form-control" @error('firt_name') is-invalid @enderror name="first_name" />
                    <label class="form-label" for="form3Example1">First name</label>
                </div>
            </div>
            <div class="col">
                @error('last_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                <div data-mdb-input-init class="form-outline">
                    <input type="text" id="form3Example2" class="form-control" @error('last_name') is-invalid @enderror
                        name="last_name" />
                    <label class="form-label" for="form3Example2">Last name</label>
                </div>
            </div>
        </div>

        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="form3Example3" class="form-control" @error('email') is-invalid @enderror
                name="email" />
            <label class="form-label" for="form3Example3">Email address</label>
        </div>

        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" id="form3Example4" class="form-control" @error('password') is-invalid @enderror
                name="password" />
            <label class="form-label" for="form3Example4">Password</label>
        </div>

        <!-- Submit button -->
        <button data-mdb-ripple-init type="submit" class="btn btn-warning btn-block">Sign in</button>
        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="text-black">Kembali</a>
        </div>
    </form>

    {{-- CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
    @stack('script')
</body>

</html>

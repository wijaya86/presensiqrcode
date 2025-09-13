@include('layout.head')

<body class="bg-gradient-primary d-flex justify-content-center align-items-center" style="min-height: 100vh;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-6 col-lg-8 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5 text-center">

                                    {{-- Logo di atas --}}
                                    <img src="{{ url('assets/img/e-pres.png') }}" 
                                         alt="Logo"
                                         class="mb-4"
                                         style="width:120px; height:auto;">

                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>

                                    <form action="{{ route('login.process') }}" method="POST" class="user">
                                        @csrf
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" name="password" id="password" class="form-control form-control-user"
                                                placeholder="Password">
                                            <span class="position-absolute" style="top: 35%; right: 15px; cursor: pointer;" onclick="togglePassword()">
                                                <i class="fas fa-eye" id="toggleIcon"></i>
                                            </span>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                    @if(session('error'))
                                        <p style="color: red">{{ session('error') }}</p>
                                    @endif                             
                                </div>
                            </div>
                        </div>
                    </div>
                

         
   

@include('layout.footer')

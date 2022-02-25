<x-guest-layout>
    <x-slot name="scripts">
        <script>

        </script>
    </x-slot>

    <x-slot name="styles">
        <style>
            .bd-placeholder-img {
                font-size: 1.125rem;
                text-anchor: middle;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
            }

            @media (min-width: 768px) {
                .bd-placeholder-img-lg {
                    font-size: 3.5rem;
                }
            }
            h1 {
                font-size: 18pt;
                font-weight: 650 !important;
            }
            h3 {
                font-size: 14pt;
                font-weight: 650;
            }

            body {
                background: url("/images/background.jpg") no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
        </style>
    </x-slot>

    <header>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <strong>Quiz Platform</strong>
                </a>
            </div>
            <a type="button" class="btn btn-outline-light" style="margin-right: 15px;" href="/admin/quizzes">
                {{auth()->check() ? "Admin Panel" : "Login"}}
            </a>
        </div>
    </header>

    <main>
        {{-- <section class="py-5 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-6 col-md-8 mx-auto">
                    <h1>Quizzes</h1>
                    <p class="lead text-muted pt-2">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tellus arcu, blandit ac efficitur pretium, tempor a turpis. Morbi neque lectus, ornare in eros ut, tincidunt maximus dui. Vestibulum ullamcorper.
                    </p>
                </div>
            </div>
        </section> --}}

        <div class="album py-5" style="min-height: 700px;">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    @foreach($categories as $catSlug => $cat)
                        @if(\App\Models\Quiz::where("category", $cat)->count() > 0)
                            <a href="/category/{{$catSlug}}">
                            <div class="col">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="card-text pb-3 pt-3 text-center">
                                            <h3 class="pb-2">{{$cat}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </main>

</x-guest-layout>

        


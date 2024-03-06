<header class="bg-dark">
    <div class="container">
        <nav class="navbar navbar-expand-xl" id="navbar">
            <a href="{{ route('front.store') }}" class="text-decoration-none mobile-logo">
                <span class="h2 text-uppercase text-primary bg-dark">STORE</span>
            </a>
            <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon icon-menu"></span>
                    <i class="navbar-toggler-icon fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="{{ route('front.home') }}" title="Products">Home</a>
                    </li>
                    @if (getCategories()->isNotEmpty())
                        @foreach (getCategories() as $category)
                            <li class="nav-item dropdown">
                                <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $category->name }}
                                </button>
                                @if ($category->sub_category->isNotEmpty())
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        @foreach ($category->sub_category as $subCategory)
                                            <li>
                                                <a class="dropdown-item nav-link" href="{{ route('front.store',[$category->slug,$subCategory->slug]) }}">{{ $subCategory->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>      			
            </div>
            <div class="right-nav py-0">
                <a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
                    <i class="fas fa-shopping-cart text-primary"></i>					
                </a>
            </div> 		
        </nav>
    </div>
</header>
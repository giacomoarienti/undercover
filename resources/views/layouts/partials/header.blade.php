@php
    /** @var \App\Models\User $user */
    $user = Illuminate\Support\Facades\Auth::user();
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">Undercover</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                @if ($user)
                    @if (!$user->is_seller)
                        <li class="nav-item">
                            <a class="nav-link" href="/cart">
                                <i class="fa-regular fa-cart-shopping">
                                    @if ($user->cart()->count() > 0)
                                        <span class="badge">
                                            {{ $user->cart()->count() }}
                                        </span>
                                    @endif
                                </i>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="/notifications">
                            <i class="fa-regular fa-bell">
                                @if ($user->notifications()->count() > 0)
                                    <span class="badge">
                                        {{ $user->notifications()->count() }}
                                    </span>
                                @endif
                            </i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.signout') }}">Signout</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

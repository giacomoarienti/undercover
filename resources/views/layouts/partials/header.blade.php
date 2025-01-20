@php
    /** @var \App\Models\User $user */
    $user = Illuminate\Support\Facades\Auth::user();
@endphp

<div class="sticky-top border-bottom text-center">
    <nav class="container navbar navbar-expand-lg navbar-light bg-light">
        <div class="row h-100 w-100 justify-content-between align-items-center">
            <div class="d-none col-md-3 d-md-inline-flex justify-content-start ps-3">
                <a class="navbar-brand d-none d-md-inline-flex" href="/">
                    <img src="{{ Storage::url('public/hat.svg') }}" alt="" style="max-height: 100px; width: 100px">
                </a>
            </div>
            <div class="col-8 col-md-6 h-100">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <a class="navbar-brand d-flex align-items-center m-1 mt-0" href="/">
                        <img src="{{ Storage::url('public/text.svg') }}" alt="undercover" class="w-100 h-100">
                    </a>
                    <form class="d-none d-md-flex justify-content-center align-items-center col-8" action="{{ route('products.index') }}" method="GET" role="search">
                        <div class="input-group rounded-pill">
                            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Find your new identity">
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-4 col-md-3 justify-items-end pe-1">
                <ul class="navbar-nav d-flex flex-row justify-content-end align-items-center">
                    @if ($user)
                        @if (!$user->is_seller)
                            <li class="nav-item">
                                <a class="nav-link" href="/cart">
                                    <i class="fa-regular fa-cart-shopping fa-xl ms-3">
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
                                <i class="fa-regular fa-bell fa-xl ms-3">
                                    @if ($user->unreadNotifications()->count() > 0)
                                        <span class="badge">
                                {{ $user->unreadNotifications()->count() }}
                            </span>
                                    @endif
                                </i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('settings') }}">
                                <i class="fa-regular fa-user fa-xl ms-3"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>


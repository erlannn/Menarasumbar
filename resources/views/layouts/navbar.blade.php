<header class="bg-[#001F3F]">
    <nav class="h-[70px] flex justify-start md:justify-between content-center place-items-center">
        <div>
            <img class="w-[90px] h-[70px] bg-white rounded-xl ml-8" src="{{ asset('img/LogoMS.png') }}" alt="logo MenaraSumbar" srcset="">
        </div>
        <div>
            <ul class="flex justify-between">
                <li>
                    <a href="{{ url('/beranda') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#ffffff] hover:text-[#000000] hover:bg-[#fafafa] active:hover:bg-[#fafafa] rounded-xl font-semibold text-base leading-normal">Beranda</a>
                </li>
                <li>
                    <a href="{{ url('/petaAwal') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#ffffff] hover:text-[#000000] hover:bg-[#fafafa] rounded-xl font-semibold text-base leading-normall">Peta BTS</a>
                </li>
                <li>
                    <a href="{{ url('/databts') }}" class="inline-block px-5 py-1.5 mr-2 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#ffffff] hover:text-[#000000] hover:bg-[#fafafa] rounded-xl font-semibold text-base leading-normal">Data BTS</a>
                </li>
                <li>
                    @if (Route::has('login'))
                        @auth
                            @if ((auth()->user()->hasRole('superuser')))
                            <a href="{{ url('users') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#ffffff] hover:text-[#000000] hover:bg-[#fafafa] rounded-xl font-semibold text-base leading-normal">Kelola akun</a>
                            @endif
                        @else
                        <div></div>
                        @endauth
                    @endif
                </li>
                <li>
                    @if (Route::has('login'))
                        @auth
                                <!-- Settings Dropdown -->
                      <div class="hidden sm:flex sm:items-center sm:ms-6">

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-block px-5 py-1.5 bg-[#014D9B] dark:text-[#ffffff] border hover:border-[#000000] hover:text-[#000000] rounded-xl font-semibold text-base leading-normal mr-10 focus:outline-none transition ease-in-out duration-150">
                                    <div class="flex justify-around">
                                        <div>{{ Auth::user()->name }}</div>

                                      <div class="mt-1 ml-1">
                                          <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                          </svg>
                                      </div>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Keluar') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                        @else
                        <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 bg-[#014D9B] dark:text-[#ffffff] border hover:border-[#000000] hover:text-[#000000] rounded-xl font-semibold text-base leading-normal mr-8">Masuk</a>
                        @endauth
                @endif
                </li>
            </ul>
        </div>
    </nav>
</header>
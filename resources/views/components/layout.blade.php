   @props(['title' => 'Products'])

   <!DOCTYPE html>
   <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
       <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <title>{{ $title }} - {{ config('app.name') }}</title>
       @vite(['resources/css/app.css', 'resources/js/app.js'])
   </head>
   <body>
       <header>
           <a href="{{ route('products.index') }}">{{ config('app.name') }}</a>
       </header>

       <nav>
           <ul>
                <header>
                    <a href="{{ route('products.index') }}">LOGO (test)</a>
                </header>
                 <li><a href="{{ route('products.index') }}">All products</a></li>
                <li><a href="{{ route('products.create') }}">New product</a></li>
           </ul>
       </nav>

       <main>
           @if (session('status'))
               <p role="status">{{ session('status') }}</p>
           @endif

           {{ $slot }}
       </main>

        <aside>
            <h2>Ads</h2>
            <p>Test ad: buy one, get one free!</p>
        </aside>

        <footer>
            <p>&copy; {{ date('Y') }} Test Company. All rights reserved.</p>
        </footer>
   </body>
   </html>
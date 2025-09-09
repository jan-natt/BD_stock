<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>TradeX - Modern Online Trading Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <link rel="preconnect" href="https://fonts.bunny.net">
   <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Livewire CSS -->
    @livewireStyles 

    <!-- Your CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

@include('layouts.header')

<main>
    @yield('content')
</main>

<!--livewire script -->
 @livewireScripts 

 <script src="{{asset('assets/js/script.js')}}"></script>

@include('layouts.footer')
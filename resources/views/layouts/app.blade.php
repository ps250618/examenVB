<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Summa Horeca' }}</title>

    <link href="https://fonts.bunny.net/css?family=bitter:400,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-orange-500 antialiased" style="font-family: 'Bitter', Georgia, serif">

<header class="w-full border-b-2 border-[#ff8f1f] px-6 py-4">
    <img src="/img/image_2.png" alt="summa" class="h-20">

    <div class="mt-2 flex items-center justify-between">
        <span class="inline-block rounded-full bg-[#ff8f1f] px-5 py-1 text-sm font-semibold text-white">
            Horeca
        </span>

        <nav class="flex gap-6 text-base font-semibold text-orange-500">
            <a href="/companies" wire:navigate class="hover:underline">Bedrijven</a>
            <a href="/employees" wire:navigate class="hover:underline">Medewerkers</a>
        </nav>
    </div>
</header>

<main>
    {{ $slot }}
</main>

</body>
</html>

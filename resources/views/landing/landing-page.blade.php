<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    @vite('resources/css/app.css')
    <title>Learnity</title>
</head>

<body class="bg-white dark:bg-gray-900 min-h-screen transition-colors duration-300">

    <x-header></x-header>
    
    <div id="home"><x-hero-section></x-hero-section></div>
    

    <x-stat></x-stat>

    <div id="course"><x-featured-course></x-featured-course></div>
    
    <x-quality-section></x-quality-section>

    <div id="review"><x-testimonial-section></x-testimonial-section></div>
    
    <x-cta-section></x-cta-section>

    <div id="about"><x-footer></x-footer></div>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./view/style/output.css">
    <title> <?= $title  ?>?> </title>
</head>

<body class="h-screen bg-gray-900 flex text-gray-100">
    <aside class="w-64 h-screen bg-gray-850 bg-gray-800 border-r border-gray-700 flex flex-col p-6 shadow-xl">
        <header class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-sky-500 rounded-xl flex items-center justify-center font-bold text-white">
                EM
            </div>
            <h1 class="text-2xl font-bold tracking-wide">EduManager</h1>
        </header>
        <hr class="border-gray-700 mb-6" />
        <nav class="flex flex-col space-y-2 text-gray-300">

            <a href="courses_list.php"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-700 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17h11A2.5 2.5 0 0 1 20 19.5v0A2.5 2.5 0 0 1 17.5 22h-11A2.5 2.5 0 0 1 4 19.5z" />
                    <path d="M6 17V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13" />
                </svg>
                Cours
            </a>

            <a href="add_cours.php"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-700 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Ajouter
            </a>

            <a href="#"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-700 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16v6H4z" />
                    <path d="M4 14h16v6H4z" />
                </svg>
                Sections
            </a>
        </nav>
    </aside>

    <?= $content ?>
</body>

</html>
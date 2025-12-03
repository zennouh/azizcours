<?php

include_once "connect.php";


function getCourses()
{
    $conn = makeConnection();

    $sql = "SELECT * FROM cours";
    $result = mysqli_query($conn, $sql);

    $courses = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $courses[] = [
                'cours_id' => $row['cours_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'level' => $row['levele'],
                'image' => "http://localhost/azizcours/src/upload/" . $row['image']
            ];
        }
    }

    mysqli_close($conn);

    return $courses;
}

// Fetch courses dynamically

$courses = getCourses();
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>EduManager</title>
    <link rel="stylesheet" href="./src/style/output.css" />
</head>

<body class="h-screen bg-gray-900 flex text-gray-100">


    <aside class="w-64 bg-gray-800 flex flex-col p-4">

        <header class="flex items-center mb-4">
            <div class="icon w-8 h-8 bg-white rounded-full mr-2"></div>
            <div class="text-xl font-bold">EduManager</div>
        </header>

        <hr class="border-gray-700 mb-4" />


        <nav class="flex flex-col space-y-2">
            <a href="courses_list.php" class="px-2 py-1 rounded hover:bg-gray-700 transition">Cours</a>
            <a href="add_cours.php" class="px-2 py-1 rounded hover:bg-gray-700 transition">ajouter</a>
            <a href="#" class="px-2 py-1 rounded hover:bg-gray-700 transition">Sections</a>
        </nav>
    </aside>


    <main class="flex-1 p-6 overflow-y-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2">Gestion des Cours</h1>
            <p class="text-gray-300">Découvrez nos formations pour développer vos compétences</p>
        </div>


        <div class="bg-gray-800 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-4 border border-gray-700 mb-6">
            <!-- Input -->
            <div class="relative flex-1 w-full md:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.34-4.34M11 19a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
                <input type="text" name="query" id="textQuery" placeholder="Rechercher un cours..."
                    class="w-full md:w-[300px] pl-10 pr-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 outline-none transition" />
            </div>

            <!-- Level Select -->
            <div class="flex-1 w-full md:w-auto">
                <select name="level" id="level"
                    class="w-full md:w-[200px] px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 outline-none transition">
                    <option value="">Niveau</option>
                    <option value="beginner">Débutant</option>
                    <option value="intermediate">Intermédiaire</option>
                    <option value="advanced">Avancé</option>
                </select>
            </div>
            <!-- Search Button -->
            <button class="px-5 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-lg transition">
                Rechercher
            </button>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
                <?php renderCourseCard($course); ?>
            <?php endforeach; ?>
        </div>



    </main>
</body>
<script src="./src/script/index.js" defer></script>

</html>

<?php
function renderCourseCard($course)
{
    // Level colors
    $levelColors = [
        'Débutant' => 'bg-green-500 text-white',
        'Intermédiaire' => 'bg-blue-500 text-white',
        'Avancé' => 'bg-purple-500 text-white',
    ];
    $levelClass = $levelColors[$course['level']] ?? 'bg-gray-500 text-white';
?>
    <div class="card group bg-gray-800 text-gray-100 rounded-lg overflow-hidden shadow-md hover:-translate-y-2 hover:shadow-lg transition cursor-pointer flex flex-col h-full">

        <div class="relative h-48 overflow-hidden">
            <img src="<?= $course['image'] ?>" alt="<?= htmlspecialchars($course['title']) ?>" class="w-full h-full object-cover">
            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold <?= $levelClass ?>">
                <?= $course['level'] ?>
            </span>
        </div>

        <div class="p-4 flex flex-col grow">
            <div class="info flex justify-between">
                <h3 class="text-lg font-bold mb-2"><?= htmlspecialchars($course['title']) ?></h3>
                <div class="ud">
                    <a href="delete_cours.php?id=<?php echo $course['cours_id'] ?>" class="delete inline-block cursor-pointer text-red-700 group-hover:bg-red-700/20 transition p-2 rounded-sm">
                        <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2 h-4 w-4" data-darkreader-inline-stroke="" style="--darkreader-inline-stroke: currentColor;">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            <line x1="10" x2="10" y1="11" y2="17"></line>
                            <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                    </a>
                    <button class="edit inline-block cursor-pointer text-amber-50 group-hover:bg-amber-50/10 transition p-2 rounded-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil h-4 w-4" data-darkreader-inline-stroke="" style="--darkreader-inline-stroke: currentColor;">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                            <path d="m15 5 4 4"></path>
                        </svg>
                    </button>
                </div>

            </div>
            <p class="text-sm text-gray-300 line-clamp-3 grow">
                <?= htmlspecialchars($course['description']) ?>
            </p>
        </div>
    </div>
<?php
}

?>
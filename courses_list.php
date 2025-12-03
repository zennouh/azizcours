<?php

include_once "connect.php";


function getCourses()
{
    $conn = makeConnection();

    $sql = <<<EOD
    SELECT COALESCE(MAX(s.position), 0)  max_position, 
       c.cours_id,
       c.title, 
       c.levele, 
       c.image, 
       c.description
FROM cours c
LEFT JOIN sections s
ON c.cours_id = s.cours_id
GROUP BY c.cours_id, c.title, c.levele, c.image, c.description
EOD;
    $result = mysqli_query($conn, $sql);

    $courses = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $courses[] = [
                'cours_id' => $row['cours_id'],
                'title' => $row['title'],
                'description' => $row['description'],
                'level' => $row['levele'],
                'position' => $row['max_position'],
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
    <aside class="w-64 h-screen bg-gray-850 bg-gray-800 border-r border-gray-700 flex flex-col p-6 shadow-xl">

        <!-- Logo -->
        <header class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-sky-500 rounded-xl flex items-center justify-center font-bold text-white">
                EM
            </div>
            <h1 class="text-2xl font-bold tracking-wide">EduManager</h1>
        </header>

        <hr class="border-gray-700 mb-6" />

        <!-- Navigation -->
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

    <main class="flex-1 p-6 overflow-y-auto">
        <div class="mb-10">
            <h1 class="text-3xl font-bold mb-2">Gestion des Cours</h1>
            <p class="text-gray-300">Découvrez nos formations pour développer vos compétences</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
                <?php renderCourseCard($course); ?>
            <?php endforeach; ?>
        </div>
    </main>

    <div id="editModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4">Modifier le Cours</h2>

            <form id="editForm" action="edit_cours.php" method="POST" enctype="multipart/form-data" class="space-y-4">

                <input type="hidden" name="cours_id" id="edit_id">

                <div>
                    <label class="block mb-1">Titre</label>
                    <input type="text" name="title" id="edit_title"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                </div>

                <div>
                    <label class="block mb-1">Description</label>
                    <textarea name="description" id="edit_description"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded"></textarea>
                </div>

                <div>
                    <label class="block mb-1">Niveau</label>
                    <select name="level" id="edit_level"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                        <option value="Débutant">Débutant</option>
                        <option value="Intermédiaire">Intermédiaire</option>
                        <option value="Avancé">Avancé</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Imagee</label>
                    <input type="file" id="imgInp" name="image" class="w-full">
                    <img id="imgPr"
                        src=""
                        class="hidden mt-3 w-64 h-40 object-cover rounded-lg border border-gray-700 shadow"
                        alt="Prévisualisation" />
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModal"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded">
                        Annuler
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 hover:bg-sky-500 rounded">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="detailsModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4">Modifier le Cours</h2>

            <form id="editForm" class="space-y-4">

                <input type="hidden" name="cours_id" id="details_id">
                <input type="hidden" name="position" id="position">

                <div>
                    <label class="block mb-1">Titre</label>
                    <input type="text" name="title" id="details_title" disabled
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                </div>

                <div>
                    <label class="block mb-1">Description</label>
                    <textarea name="description" id="details_description" disabled
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded"></textarea>
                </div>

                <div>
                    <label class="block mb-1">Niveau</label>
                    <input type="text" name="option" id="details_level" disabled
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                </div>

                <div>
                    <label class="block mb-1">Image</label>

                    <img id="imgPrdet"
                        src=""
                        class="hidden mt-3 w-64 h-40 object-cover rounded-lg border border-gray-700 shadow"
                        alt="Prévisualisation" />
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModalDetail"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded">
                        Annuler
                    </button>


                </div>
            </form>
        </div>
    </div>

    <div id="sectionModal"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4">Ajouter les section</h2>

            <form id="editForm" class="space-y-4" action="add_section.php" method="POST">
                <input type="hidden" name="cours_id" id="cours_id">
                <input type="hidden" name="sections_position" id="sections_position">

                <div>
                    <label class="block mb-1">Section Titre</label>
                    <input type="text" name="sections_title" id="sections_title"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                </div>

                <div>
                    <label class="block mb-1">Description</label>
                    <textarea name="sections_content" id="sections_description"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModalSection"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-sky-600 hover:bg-sky-500 rounded">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
<script>
    document.querySelectorAll(".edit").forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".card");

            const cours = {
                id: card.querySelector(".delete").dataset.id,
                title: card.querySelector("h3").innerText,
                description: card.querySelector("p").innerText,
                level: card.querySelector("span").innerText,
                image: card.querySelector("img").src,
            };
            const input = document.getElementById('imgInp');
            const preview = document.getElementById('imgPr');
            preview.classList.remove("hidden")
            preview.src = cours.image;
            console.log(preview.src);
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (!file) return;

                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.classList.remove('hidden');
            });
            document.getElementById("edit_id").value = cours.id;
            document.getElementById("edit_title").value = cours.title;
            document.getElementById("edit_description").value = cours.description;
            document.getElementById("edit_level").value = cours.level;
            document.getElementById("editModal").classList.remove("hidden");
            document.getElementById("editModal").classList.add("flex");
        });
    });
    document.querySelectorAll(".details").forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".card");

            const cours = {
                id: card.querySelector(".delete").dataset.id,
                title: card.querySelector("h3").innerText,
                description: card.querySelector("p").innerText,
                level: card.querySelector("span").innerText,
                image: card.querySelector("img").src,
            };
            const input = document.getElementById('imgInpdet');
            const preview = document.getElementById('imgPrdet');
            preview.classList.remove("hidden")
            preview.src = cours.image;

            document.getElementById("details_id").value = cours.id;
            document.getElementById("details_title").value = cours.title;
            document.getElementById("details_description").value = cours.description;
            document.getElementById("details_level").value = cours.level;
            document.getElementById("detailsModal").classList.remove("hidden");
            document.getElementById("detailsModal").classList.add("flex");
        });
    });
    document.querySelectorAll(".sections").forEach(btn => {
        btn.addEventListener("click", () => {
            const card = btn.closest(".card");

            const sec = {
                id: card.querySelector(".delete").dataset.id,
                position: card.querySelector(".sections").dataset.position,
                title: card.querySelector("h3").innerText,
                description: card.querySelector("p").innerText,
            };


            console.log(sec.position);

            document.getElementById("cours_id").value = sec.id;
            document.getElementById("sections_position").value = sec.position || 123;
            // document.getElementById("sections_title").value = sec.title;
            // document.getElementById("sections_description").value = sec.description;
            // document.getElementById("details_level").value = cours.level;
            document.getElementById("sectionModal").classList.remove("hidden");
            document.getElementById("sectionModal").classList.add("flex");
        });
    });
    document.getElementById("closeModal").onclick = () => {
        document.getElementById("editModal").classList.add("hidden");
        document.getElementById("editModal").classList.remove("flex");
    };
    document.getElementById("closeModalDetail").onclick = () => {
        document.getElementById("detailsModal").classList.add("hidden");
        document.getElementById("detailsModal").classList.remove("flex");
    };
    document.getElementById("closeModalSection").onclick = () => {
        document.getElementById("sectionModal").classList.add("hidden");
        document.getElementById("sectionModal").classList.remove("flex");
    };
</script>

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
    <div class="card group bg-gray-800 text-gray-100 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all flex flex-col h-full border border-gray-700">

        <!-- Image -->
        <div class="relative h-48 overflow-hidden">
            <img src="<?= $course['image'] ?>"
                alt="<?= htmlspecialchars($course['title']) ?>"
                class="w-full h-full object-cover transition duration-300 group-hover:scale-105">

            <!-- Level Badge -->
            <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold <?= $levelClass ?> shadow-md">
                <?= $course['level'] ?>
            </span>
        </div>

        <!-- Content -->
        <div class="p-5 flex flex-col grow">
            <div class="flex items-start justify-between mb-3">

                <!-- Title -->
                <h3 class="text-lg font-bold leading-tight pr-6">
                    <?= htmlspecialchars($course['title']) ?>
                </h3>

                <!-- Buttons -->
                <div class="flex items-center space-x-2">


                    <a data-id="<?= $course['cours_id'] ?>"
                        href="#"
                        class="details p-2 rounded-lg bg-green-700/30 text-green-700  transition">
                        Details
                    </a>
                    <a
                        data-id="<?= $course['cours_id'] ?>"
                        data-position="<?= $course['position'] ?>"
                        href="#"
                        class="sections p-2 rounded-lg bg-orange-700/30 text-orange-700  transition">
                        Sections
                    </a>
                    <a data-id="<?= $course['cours_id'] ?>"
                        href="delete_cours.php?id=<?= $course['cours_id'] ?>"
                        class="delete p-2 rounded-lg bg-white/20 text-red-700 hover:bg-red-700/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20"
                            viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4">
                            <path d="M3 6h18"></path>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" x2="10" y1="11" y2="17"></line>
                            <line x1="14" x2="14" y1="11" y2="17"></line>
                        </svg>
                    </a>

                    <!-- EDIT -->
                    <button data-id="<?= $course['cours_id'] ?>"
                        class="edit p-2 rounded-lg bg-white/20  hover:bg-yellow-400/40 hover:text-yellow-300 transition">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="20" height="20"
                            viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="h-4 w-4">
                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                            <path d="m15 5 4 4"></path>
                        </svg>
                    </button>

                </div>
            </div>

            <!-- Description -->
            <p class="text-sm text-gray-300 line-clamp-3">
                <?= htmlspecialchars($course['description']) ?>
            </p>
        </div>
    </div>
<?php
}
?>
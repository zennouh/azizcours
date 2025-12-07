<?php

function makeConnection()
{
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "aziz_cours";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

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

$courses = getCourses();
?>

<?php
ob_start();
?>

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
    document.querySelectorAll(".sections").forEach(btn => {
        btn.addEventListener("click", () => {
            console.log("azeer");

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

<?php
function renderCourseCard($course)
{
    $levelColors = [
        'Débutant' => 'bg-green-500 text-white',
        'Intermédiaire' => 'bg-blue-500 text-white',
        'Avancé' => 'bg-purple-500 text-white',
    ];
    $levelClass = $levelColors[$course['level']] ?? 'bg-gray-500 text-white';
?>
    <div class="card group bg-gray-800 text-gray-100 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all flex flex-col h-full border border-gray-700">
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
                        href="page_detail.php?id=<?= $course['cours_id'] ?>&position=<?= $course['position'] ?>"

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

<?php
$content = ob_get_clean();
?>


<?php
$title = "Courses";
include "./view/layout.php";
?>
<?php
include "connect.php";

if (isset($_POST["submit_edit"])) {
    $id = $_POST["cours_id"];
    $section_id = $_POST["section_id"];
    $title = $_POST["sections_title"];
    $desc = $_POST["sections_content"];

    $conn = makeConnection();
    $sql = "UPDATE `sections` SET `section_title`='$title',`section_content`='$desc' WHERE `section_id`='$section_id'";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
    header("Location: courses_list.php");
}


function getSection($id)
{
    $conn = makeConnection();
    $id = intval($id);

    $sql = "SELECT section_title, section_content, section_id,position 
            FROM sections 
            WHERE cours_id = $id
            ORDER BY position ASC";

    $result = mysqli_query($conn, $sql);
    $sections = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $sections[] = [
                "sec_title"    => $row["section_title"],
                "sec_dsc"      => $row["section_content"],
                "sec_position" => $row["position"],
                "sec_id" => $row["section_id"],
            ];
        }
    }

    mysqli_close($conn);
    return $sections;
}


function getCourses($id)
{
    $conn = makeConnection();
    $id = intval($id);

    // We get the max position of sections directly here
    $sql = "SELECT c.cours_id, c.title, c.description, c.levele, c.image,
                   COALESCE(MAX(s.position),0) AS max_position
            FROM cours c
            LEFT JOIN sections s ON c.cours_id = s.cours_id
            WHERE c.cours_id = $id
            GROUP BY c.cours_id, c.title, c.description, c.levele, c.image";

    $result = mysqli_query($conn, $sql);

    $courses = [];

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $courses[] = [
            'cours_id'   => $row['cours_id'],
            'title'      => $row['title'],
            'description' => $row['description'],
            'level'      => $row['levele'],
            'max_position' => $row['max_position'],
            'image'      => "http://localhost/azizcours/src/upload/" . $row['image']
        ];
    }

    mysqli_close($conn);
    return $courses;
}



$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$position = isset($_GET["position"]) ? intval($_GET["position"]) : 0;
// echo $id;
$cours = getCourses($id);
$sect  = getSection($id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Cours</title>
    <link rel="stylesheet" href="./src/style/output.css">
    <script src="https://cdn.tailwindcss.com"></script>
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

    <main class="flex-1 p-6 overflow-y-auto flex gap-4 justify-center">

        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">

            <h2 class="text-2xl font-bold mb-4">Détails du Cours</h2>

            <?php if (!empty($cours)) :
                $c = $cours[0]; ?>

                <form id="t" class="space-y-4">

                    <input type="hidden" id="details_id" value="<?= $c['cours_id'] ?>">
                    <input type="hidden" id="position" value="<?= $c['max_position'] ?>">

                    <div>
                        <label class="block mb-1">Titre</label>
                        <input type="text" disabled value="<?= htmlspecialchars($c['title']) ?>"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded opacity-75">
                    </div>

                    <div>
                        <label class="block mb-1">Description</label>
                        <textarea disabled
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded opacity-75"
                            rows="4"><?= htmlspecialchars($c['description']) ?></textarea>
                    </div>

                    <div>
                        <label class="block mb-1">Niveau</label>
                        <input type="text" disabled value="<?= $c['level'] ?>"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded opacity-75">
                    </div>

                    <div>
                        <label class="block mb-1">Image</label>
                        <img src="<?= $c['image'] ?>"
                            class="mt-3 w-full h-48 object-cover rounded-lg border border-gray-700 shadow">
                    </div>

                </form>

            <?php else : ?>

                <p class="text-red-400">Cours introuvable.</p>

            <?php endif; ?>

        </div>
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700  overflow-y-scroll">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Sections du Cours</h2>

                <a href="#"
                    class="sections px-3 py-1 bg-sky-600 rounded-lg text-sm hover:bg-sky-500 transition">
                    ➕ Ajouter
                </a>
            </div>
            <input type="hidden" value="<?= $id ?>" name="name_coursid" id="id_coursid">
            <input type="hidden" value="<?= $position ?>" name="name_position" id="id_position">
            <?php if (!empty($sect)) : ?>

                <div class="space-y-3">

                    <?php foreach ($sect as $s) : ?>
                        <div class="group border border-gray-700 rounded-xl p-4 bg-gray-750 hover:bg-gray-700 transition">


                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($s['sec_title']) ?></h3>

                                    <span class="text-xs bg-gray-900 px-2 py-1 rounded border border-gray-600">
                                        Position : <?= $s['sec_position'] ?>
                                    </span>
                                </div>


                                <div class="opacity-0 group-hover:opacity-100 transition flex gap-2">


                                    <a href="#"
                                        class="edit px-2 py-1 rounded bg-yellow-500 text-black text-sm hover:bg-yellow-400"
                                        data-title="<?= htmlspecialchars($s['sec_title']) ?>"
                                        data-description="<?= htmlspecialchars($s['sec_dsc']) ?>"
                                        data-position="<?= $s['sec_position'] ?>"
                                        data-secid="<?= $s['sec_id'] ?>"
                                        data-cours="<?= $id ?>">
                                        ✏️
                                    </a>



                                    <a href="delete_section.php?id=<?= $s['sec_id'] ?>"
                                     data-secid="<?= $s['sec_id'] ?>"
                                        class="px-2 py-1 rounded bg-red-600 text-white text-sm hover:bg-red-500"
                                        onclick="return confirm('Supprimer cette section ?');">
                                        🗑️
                                    </a>

                                </div>
                            </div>

                            <!-- Description -->
                            <p class="mt-3 text-gray-300 text-sm leading-relaxed">
                                <?= nl2br(htmlspecialchars($s['sec_dsc'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>

                </div>

            <?php else : ?>

                <p class="text-red-400">Aucune section trouvée.</p>

            <?php endif; ?>

        </div>



    </main>

    <div id="sectionModalDetail"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4">Ajouter les section</h2>

            <form id="ajoutForm" class="space-y-4" action="add_section.php" method="POST">
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

    <div id="sectionModalEdit"
        class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4">
        <div class="bg-gray-800 w-full max-w-lg p-6 rounded-xl border border-gray-700">
            <h2 class="text-2xl font-bold mb-4">Modifier les section</h2>

            <form id="editForm" class="space-y-4" action="page_detail.php" method="POST">
                <input type="hidden" name="cours_id" id="cours_id">
                <input type="hidden" name="section_id" id="section_id">
                <input type="hidden" name="sections_position" id="sections_position_edit">

                <div>
                    <label class="block mb-1">Section Titre</label>
                    <input type="text" name="sections_title" id="sections_title_edit"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                </div>

                <div>
                    <label class="block mb-1">Description</label>
                    <textarea name="sections_content" id="sections_description_edit"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModalSectionedit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded">
                        Annuler
                    </button>
                    <button type="submit" name="submit_edit"
                        class="px-4 py-2 bg-sky-600 hover:bg-sky-500 rounded">
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        console.log(document.querySelector(".sections"));

        document.querySelector(".sections").addEventListener("click", () => {
            let id = document.getElementById("id_coursid").value;
            let pos = document.getElementById("id_position").value;

            document.getElementById("cours_id").value = id;
            document.getElementById("sections_position").value = pos;
            // document.getElementById("sections_title").value = sec.title;
            // document.getElementById("sections_description").value = sec.description;
            // document.getElementById("details_level").value = cours.level;
            document.getElementById("sectionModalDetail").classList.remove("hidden");
            document.getElementById("sectionModalDetail").classList.add("flex");
        });
        document.querySelectorAll(".edit").forEach(editBtn => {

            editBtn.addEventListener("click", (e) => {
                // e.preventDefault();
                console.log("hjgefhzfg");


                let title = editBtn.dataset.title;
                console.log(title);

                let description = editBtn.dataset.description;
                let position = editBtn.dataset.position;
                let cours = editBtn.dataset.cours;
                let secID = editBtn.dataset.secid;


                document.getElementById("sections_title_edit").value = title;
                document.getElementById("sections_description_edit").value = description;
                document.getElementById("section_id").value = secID;
                // document.getElementById("sections_position").value = position;
                // document.getElementById("cours_id").value = cours;


                document.getElementById("sectionModalEdit").classList.remove("hidden");
                document.getElementById("sectionModalEdit").classList.add("flex");
            });
        });


        document.getElementById("closeModalSection").onclick = () => {
            document.getElementById("sectionModalDetail").classList.add("hidden");
            document.getElementById("sectionModalDetail").classList.remove("flex");
        };

        document.getElementById("closeModalSectionedit").onclick = () => {
            document.getElementById("sectionModalEdit").classList.add("hidden");
            document.getElementById("sectionModalEdit").classList.remove("flex");
        };
    </script>

</body>

</html>
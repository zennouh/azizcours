<?php
ob_start();
?>

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
<?php
$content = ob_get_clean();
?>

<?php

$title = $GET["name"] ?? "name";
include "layout.php";

?>
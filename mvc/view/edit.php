<?php
ob_start();
?>

<main class="flex-1 p-6 overflow-y-auto w-full">
    <div class="bg-gray-800 w-full  p-6 rounded-xl border border-gray-700">
        <h2 class="text-2xl font-bold mb-4">Modifier le Cours</h2>

        <form id="editForme" action="updatecours.php" method="POST" enctype="multipart/form-data" class="space-y-4">

            <input type="hidden" name="cours_id" id="edit_id" value="<?= htmlspecialchars($c['cours_id']) ?>">

            <div>
                <label class="block mb-1">Titre</label>
                <input type="text" value="<?= htmlspecialchars($c['title']) ?>" name="title" id="edit_title"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
            </div>

            <div>
                <label class="block mb-1">Description</label>
                <textarea name="description" id="edit_description"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                <?= htmlspecialchars($c['description']) ?>
                </textarea>
            </div>

            <div>
                <label class="block mb-1">Niveau</label>

                <select name="level" id="edit_level" value="<?= $c['level'] ?>"
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded">
                    <option value="Débutant">Débutant</option>
                    <option value="Intermédiaire">Intermédiaire</option>
                    <option value="Avancé">Avancé</option>
                </select>
            </div>

            <div>
                <label class="block mb-1" id="img">Image</label>
                <input type="file" id="imgInpe" name="image" class="w-full" value="<?= $c['image'] ?>">
                <img id="imgPre"
                    src="<?= $c['image'] ?>"
                    class="mt-3 w-64 h-40 object-cover rounded-lg border border-gray-700 shadow"
                    alt="Prévisualisation" />
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="submit"
                    class="px-4 py-2 bg-sky-600 hover:bg-sky-500 rounded">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>

</main>


<script>
    const input = document.getElementById('imgInpe');
    const preview = document.getElementById('imgPre');
    input.addEventListener('change', () => {
        const file = input.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        preview.src = url;
    });

    // const form = document.getElementById("editForme");
    // form.addEventListener("submit", (e) => {
    //     // e.preventDefault();
    //     // const card = btn.closest(".card");

    //     // const cours = {
    //     //     id: card.querySelector(".delete").dataset.id,
    //     //     title: card.querySelector("h3").innerText,
    //     //     description: card.querySelector("p").innerText,
    //     //     level: card.querySelector("span").innerText,
    //     //     image: card.querySelector("img").src,
    //     // };
    //     // const input = document.getElementById('imgInpe');
    //     // const preview = document.getElementById('imgPre');
    //     // preview.classList.remove("hidden")
    //     // preview.src = cours.image;
    //     // console.log(preview.src);
    //     // input.addEventListener('change', () => {
    //     //     const file = input.files[0];
    //     //     if (!file) return;

    //     //     const url = URL.createObjectURL(file);
    //     //     preview.src = url;
    //     //     preview.classList.remove('hidden');
    //     // });
    //     // document.getElementById("edit_id").value = cours.id;
    //     // document.getElementById("edit_title").value = cours.title;
    //     // document.getElementById("edit_description").value = cours.description;
    //     // document.getElementById("edit_level").value = cours.level;
    //     // document.getElementById("editModal").classList.remove("hidden");
    //     // document.getElementById("editModal").classList.add("flex");
    // });
</script>

<?php
$content = ob_get_clean();
?>

<?php

$title = "Edit";
include "layout.php";

?>
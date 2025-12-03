<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un Cours – EduManager</title>
  <link rel="stylesheet" href="./src/style/output.css?v=0.011" />
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
      <h1 class="text-3xl font-bold mb-2">Ajouter un Cours</h1>
      <p class="text-gray-300">Créez un nouveau cours et ajoutez-le à votre plateforme</p>
    </div>

    <div class="bg-gray-800 rounded-2xl border p-10 border-gray-700 shadow-xl">

      <form id="addcours" class="flex flex-col gap-6" action="courses_create.php" method="POST" enctype="multipart/form-data">

        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Titre du cours</label>
          <input type="text" name="title" required
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 transition"
            placeholder="Entrer le titre" />
        </div>

        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Description</label>
          <textarea name="description" required rows="4"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 transition"
            placeholder="Décrivez votre cours ici..."></textarea>
        </div>


        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Niveau</label>
          <select name="level" required
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 transition">
            <option value="">Choisir un niveau...</option>
            <option value="Débutant">Débutant</option>
            <option value="Intermédiaire">Intermédiaire</option>
            <option value="Avancé">Avancé</option>
          </select>
        </div>


        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Image du cours</label>

          <input type="file" id="imageInput" name="image" accept="image/*" required
            class="w-full text-gray-300 bg-gray-700 border border-gray-600 rounded-lg py-2 px-3 file:bg-gray-600 file:border-none file:px-4 file:py-2 file:rounded-lg file:text-gray-200 file:hover:bg-gray-500 transition cursor-pointer" />

          <img id="imagePreview"
            class="hidden mt-3 w-64 h-40 object-cover rounded-lg border border-gray-700 shadow"
            alt="Prévisualisation" />
        </div>

        <button type="submit"
          class="w-full py-3 bg-sky-600 hover:bg-sky-500 text-white rounded-xl font-semibold transition shadow-lg">
          Ajouter le cours
        </button>

      </form>

    </div>
  </main>

  <script>
    const input = document.getElementById('imageInput');
    const preview = document.getElementById('imagePreview');

    input.addEventListener('change', () => {
      const file = input.files[0];
      if (!file) return;

      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.classList.remove('hidden');
    });


    function validate() {
      const form = document.getElementById("addcours");
      form.addEventListener("submit", (e) => {
        e.preventDefault();
      });
    }
    validate()
  </script>

</body>

</html>
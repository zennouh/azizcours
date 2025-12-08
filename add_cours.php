<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un Cours – EduManager</title>
  <link rel="stylesheet" href="./src/style/output.css?v=0.013" />
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

    <div class="mb-6">
      <h1 class="text-3xl font-bold mb-2">Ajouter un Cours</h1>
      <p class="text-gray-300">Créez un nouveau cours et ajoutez-le à votre plateforme</p>
    </div>

    <div class="bg-gray-800 rounded-2xl border p-10 border-gray-700 shadow-xl">

      <div class="msg hidden p-5 bg-red-700/55 text-red-200 m-5 rounded-xl">Le cours est ajouter</div>

      <form id="addcours" class="flex flex-col gap-6" action="courses_create.php" method="POST" enctype="multipart/form-data">

        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Titre du cours</label>
          <input type="text" name="title" required id="title"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 transition"
            placeholder="Entrer le titre" />
        </div>

        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Description</label>
          <textarea name="description" required rows="4" id="description"
            class="w-full px-4 py-2 rounded-lg bg-gray-700 text-gray-100 border border-gray-600 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 transition"
            placeholder="Décrivez votre cours ici..."></textarea>
        </div>


        <div class="flex flex-col gap-2">
          <label class="text-gray-300">Niveau</label>
          <select name="level" required id="level"
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
      let error = false;
      form.addEventListener("submit", (e) => {

        const title = document.getElementById("title").value;
        const desc = document.getElementById("description").value;
        const level = document.getElementById("level").value;
        if (!level.trim()) {
          alert("Select a level");
          e.preventDefault();
          return;
        } else if (!title.trim()) {
          alert("Saisir une valide title");
          e.preventDefault();
          return;
        } else if (!desc.trim()) {
          alert("Saisir une valide desc");
          e.preventDefault();
          return;
        } else {}
      });
    }
    validate()
  </script>

</body>

</html>
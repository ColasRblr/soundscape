var currentPage = $("#playerPage").attr("id");

  if (currentPage === "playerPage") {
    let url = window.location.href;
    let currentSongIndex = 0;
    let songs = [];
    let currentSong = null;

    // Extraire le deuxième chiffre de l'URL pour l'ID de la catégorie
    let categoryId = url.split("/")[5];

    // Extraire le premier chiffre de l'URL pour l'ID de la chanson
    let songId = url.split("/")[4];

    // Récupération de toutes les chansons de la même catégorie
    function getSongsByCategory(categoryId) {
      $.ajax({
        url: "/category/" + categoryId + "/getsongs",
        type: "GET",
        data: {
          category_id: categoryId,
        },
        success: function (response) {
          // Stocker les chansons dans la variable songs
          songs = response;
          // Trouver l'index de la chanson à jouer
          currentSongIndex = songs.findIndex((song) => song.id == songId);
          // Charger la chanson à jouer dans le lecteur audio
          loadSong(currentSongIndex);
        },
        error: function (error) {
          console.log(error);
        },
      });
    }

    // Appeler la fonction getSongsByCategory avec les ID de la catégorie et de la chanson récupérés depuis l'URL
    getSongsByCategory(categoryId, songId);

    // Fonction pour charger la chanson précédente
    function previousSong() {
      currentSongIndex--;
      if (currentSongIndex < 0) {
        currentSongIndex = songs.length - 1;
      }
      loadSong(currentSongIndex);
    }

    function nextSong() {
      currentSongIndex++;
      if (currentSongIndex >= songs.length) {
        currentSongIndex = 0;
      }
      loadSong(currentSongIndex);
    }

    function loadSong(index) {
      currentSong = songs[index];

      if (currentSong) {
        // Charger la chanson dans le lecteur audio
        $("#my-player").attr("src", currentSong.url);

        // Mettre à jour le titre de la chanson
        $("#song-title").text(currentSong.title);
        $("#song-artist").text(currentSong.artist);
        console.log(window.history);

        //Modifier l'URL de la page en remplaçant l'ID de la chanson
        let newUrl = location.href.replace(/\/\d+\//, `/${currentSong.id}/`);
        history.pushState(null, null, newUrl);

        // Mettre à jour l'image de la chanson

        let imageSrc = `img/albums/${currentSong.image}`;
        let image = new Image();
        image.onload = function () {
          $("#album-image").attr("src", imageSrc + "&rand=" + Math.random());
        };
      }
    }

    $(document).ready(function () {
      player = new MediaElementPlayer("#my-player", {
        features: [
          "play",
          "pause",
          "progress",
          "current",
          "duration",
          "volume",
          "fullscreen",
        ],
        loop: false,
      });

      // Charger la première chanson dans le lecteur audio
      loadSong(currentSongIndex);

      // Ajouter la fonctionnalité des boutons de lecture suivante et précédente
      $("#previous-song-btn").on("click", function () {
        previousSong();
        location.reload();
      });

      $("#next-song-btn").on("click", function () {
        nextSong();
        location.reload();
      });

      // Charger la première chanson dans le lecteur audio
      loadSong(currentSongIndex);
    });
    const disc = document.querySelector(".disc");
    const headshell = document.querySelector(".headshell");

    // Ajouter des événements de lecture à l'élément audio
    let audio = document.getElementById("my-player");
    audio.addEventListener("play", function () {
      disc.classList.add("rotate");
      headshell.classList.add("move");
      headshell.classList.remove("reset");
    });

    audio.addEventListener("pause", function () {
      disc.classList.remove("rotate");
      headshell.classList.remove("move");
      headshell.classList.add("reset");
    });

    // Afficher coeur vide ou rempli selon si chanson dans favori de l'utilisateur
    window.onload = function () {
      // Récupérer l'élément du bouton de favori et l'ID de la chanson
      var favoriteBtn = document.querySelector(".home-container-btn");

      // Effectuer une requête AJAX pour vérifier si la chanson est déjà dans les favoris
      $.ajax({
        url: "/favorite/isFavorite/" + songId,
        success: function (response) {
          var img = document.createElement("img");
          favoriteBtn.appendChild(img);
          img.setAttribute("id", "favorite-btn");
          if (response == "true") {
            // La chanson est déjà dans les favoris, afficher le cœur rempli
            img.setAttribute("src", "/img/favorite.png");
          } else {
            // La chanson n'est pas dans les favoris, afficher le cœur vide
            img.setAttribute("src", "/img/emptyHeart.png");
          }
        },
      });
    };
  }

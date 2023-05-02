var currentPage = $("#homePage").attr("id");

if (currentPage === "homePage") {
  var swiper = new Swiper(".swiper-container", {
    effect: "coverflow",
    grabCursor: true,
    // width: "100%",
    slidesPerView: 2,
    spaceBetween: 5,
    centeredSlides: true,
    coverflowEffect: {
      rotate: 50,
      stretch: 0,
      depth: 100,
      modifier: 1,
    },
    pagination: {
      el: ".swiper-pagination",
    },
    on: {
      init: function () {
        slideChange.call(this);
      },
      slideChange: slideChange,
    },
  });

  slideChange.call(swiper);

  // Définition de la fonction slideChange

  function slideChange() {
    var category_id = this.slides[this.activeIndex].getAttribute("data-id");
    var songList = document.querySelector("#home-song-list");
    const authenticatedStatus = document.getElementById("authenticated-status");
    const isAuthenticated = authenticatedStatus.dataset.authenticated;

    $.ajax({
      url: "/category/" + category_id + "/getsongs",
      success: function (response) {
        songList.innerHTML = "";

        response.forEach(function (song) {
          var tr = document.createElement("tr");
          songList.appendChild(tr);
          var td1 = document.createElement("td");
          var td2 = document.createElement("td");
          var td3 = document.createElement("td");
          tr.appendChild(td1);
          tr.appendChild(td2);
          tr.appendChild(td3);
          var h4 = document.createElement("h4");
          h4.setAttribute("id", "home-song-artist");
          h4.innerText = song.artist;
          var h5 = document.createElement("h5");
          h5.setAttribute("id", "home-song-title");
          h5.innerText = song.title;
          var player = document.createElement("a");
          player.setAttribute("href", "/player/" + song.id + "/" + category_id);
          player.classList.add("homeSong");
          player.innerHTML =
            '<i class="bi bi-play-fill" id="home-play-btn"></i>';
          td1.appendChild(h4);
          td2.appendChild(h5);
          td3.appendChild(player);

          console.log(isAuthenticated);
          if (isAuthenticated) {
            var td4 = document.createElement("td");
            tr.appendChild(td4);
            var favbtn = document.createElement("a");
            var img = document.createElement("img");
            td4.appendChild(favbtn);
            favbtn.appendChild(img);
            img.setAttribute("id", "favorite-btn");
            favbtn.classList.add("home-container-btn");
            favbtn.setAttribute("href", "/favorite/newFavorite/" + song.id);
            // Effectuer une requête AJAX pour vérifier si la chanson est dans les favoris de l'utilisateur
            $.ajax({
              url: "/favorite/isFavorite/" + song.id,
              success: function (response) {
                if (response == "true") {
                  // La chanson est déjà dans les favoris, afficher le cœur rempli
                  img.setAttribute("src", "/img/favorite.png");
                } else {
                  // La chanson n'est pas dans les favoris, afficher le cœur vide
                  img.setAttribute("src", "/img/emptyHeart.png");
                }
              },
            });
          }
        });
      },
    });
  }
}

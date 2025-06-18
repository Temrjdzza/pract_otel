const template = document.getElementById("room-template");

const list_rooms = document.querySelector(".list-rooms");

const list_imgs = ["images/room.jpg", "images/pool_inside.jpg"];

fetch("http://localhost/api/router.php/rooms")
  .then((resp) => resp.json())
  .then((data) => {
    const length = data["data"].length;

    for (let i = 0; i < length; i++) {
      let room = template.content.cloneNode(true);

      let slider_imgs = room.querySelector(".slider");

      list_imgs.forEach((path) => {
        let new_img = document.createElement("img");
        new_img.src = path;
        slider_imgs.appendChild(new_img);
      });

      let type = room.querySelector(".type");

      type.textContent += " " + data["data"][i]["room_type"];

      let capacity = room.querySelector(".capacity");

      capacity.textContent += " " + data["data"][i]["capacity"];

      let description = room.querySelector(".description");

      description.textContent += " " + data["data"][i]["description"];

      let price = room.querySelector(".price");

      price.textContent += " " + data["data"][i]["price"];

      const slider = room.querySelector(".slider");
      const prevButton = room.querySelector(".prev-button");
      const nextButton = room.querySelector(".next-button");
      const slides = Array.from(slider.querySelectorAll("img"));
      const slideCount = slides.length;
      let slideIndex = 0;

      // Устанавливаем обработчики событий для кнопок
      prevButton.addEventListener("click", showPreviousSlide);
      nextButton.addEventListener("click", showNextSlide);

      // Функция для показа предыдущего слайда
      function showPreviousSlide() {
        slideIndex = (slideIndex - 1 + slideCount) % slideCount;
        updateSlider();
      }

      // Функция для показа следующего слайда
      function showNextSlide() {
        slideIndex = (slideIndex + 1) % slideCount;
        updateSlider();
      }

      // Функция для обновления отображения слайдера
      function updateSlider() {
        slides.forEach((slide, index) => {
          if (index === slideIndex) {
            slide.style.display = "block";
          } else {
            slide.style.display = "none";
          }
        });
      }

      // Инициализация слайдера
      updateSlider();

      list_rooms.appendChild(room);
    }
  });

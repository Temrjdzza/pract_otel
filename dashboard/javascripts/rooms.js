let sort_params = "";

let filter_params = {
  type: "",
  prices: "",
  capacity: "",
};

const api = "/api/router.php/rooms";

const template = document.getElementById("room-template");

const list_rooms = document.querySelector(".list-rooms");

const list_imgs = ["images/room.jpg", "images/pool_inside.jpg"];

increment_rooms(api);

function increment_rooms(url) {
  clear_list_rooms();
  fetch(url)
    .then((resp) => resp.json())
    .then((data) => {
      const length = data["data"].length;

      for (let i = 0; i < length; i++) {
        let room = template.content.cloneNode(true);

        let slider_imgs = room.querySelector(".slider");

        let img_arr = data["data"][i]["images"];

        for (let j = 0; j < img_arr.length; j++) {
          let new_img = document.createElement("img");
          new_img.src = img_arr[j];
          slider_imgs.appendChild(new_img);
        }

        if (img_arr.length == 0) {
          let new_img = document.createElement("img");
          new_img.src = "/images/default.jpg";
          slider_imgs.appendChild(new_img);
        }

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

        prevButton.addEventListener("click", showPreviousSlide);
        nextButton.addEventListener("click", showNextSlide);

        function showPreviousSlide() {
          slideIndex = (slideIndex - 1 + slideCount) % slideCount;
          updateSlider();
        }

        function showNextSlide() {
          slideIndex = (slideIndex + 1) % slideCount;
          updateSlider();
        }

        function updateSlider() {
          slides.forEach((slide, index) => {
            if (index === slideIndex) {
              slide.style.display = "block";
            } else {
              slide.style.display = "none";
            }
          });
        }

        if (img_arr.length <= 1) {
          prevButton.style.display = "none";
          nextButton.style.display = "none";
        }

        updateSlider();

        const room_id = data["data"][i]["room_id"];
        let bron_btn = room.querySelector(".btn");
        bron_btn.addEventListener("click", () => {
          const bron_menu_template = document.getElementById(
            "bron-menu__template",
          );
          const bron_menu = bron_menu_template.content.cloneNode(true);

          const one_price = bron_menu.querySelector(".one-price");
          one_price.textContent = data["data"][i]["price"];

          create_dialog_bron(bron_menu, room_id);
          list_rooms.appendChild(bron_menu);
        });

        list_rooms.appendChild(room);
      }
    });
}

function clear_list_rooms() {
  let rooms = list_rooms.children;
  while (rooms.length > 4) {
    for (let i = 0; i < rooms.length; i++) {
      if (rooms[i] instanceof HTMLLIElement) {
        list_rooms.removeChild(rooms[i]);
      }
    }
  }
}

function url_constructor() {
  let params = "";
  if (filter_params["type"] != "")
    params += "room_type=" + filter_params["type"] + "&";
  if (filter_params["prices"] != "")
    params += "price=" + filter_params["prices"] + "&";
  if (filter_params["capacity"] != "")
    params += "capacity=" + filter_params["capacity"] + "&";

  if (sort_params == "price_up") params += "sort=price&order=asc";
  if (sort_params == "price_down") params += "sort=price&order=desc";
  if (sort_params == "capacity_up") params += "sort=capacity&order=asc";
  if (sort_params == "capacity_down") params += "sort=capacity&order=desc";
  if (sort_params == "type_up") params += "sort=type&order=asc";
  if (sort_params == "type_down") params += "sort=type&order=desc";

  return api + "?" + params;
}

function create_dialog_bron(el, room_id) {
  let date = new Date(),
    year = date.getFullYear(),
    month = date.getMonth();

  setCalendar(year, month, el, room_id);

  const fio = el.querySelector(".input-fio");

  const btn_bron = el.querySelector(".bron");

  btn_bron.addEventListener("click", () => {
    const period = get_dates_bron();

    try {
      let start_date = `${period[0].getFullYear()}-${period[0].getMonth() + 1}-${period[0].getDate()} ${period[0].getHours()}:${period[0].getMinutes()}:${period[0].getSeconds()}`;

      let end_date = `${period[1].getFullYear()}-${period[1].getMonth() + 1}-${period[1].getDate()} ${period[1].getHours()}:${period[1].getMinutes()}:${period[1].getSeconds()}`;

      const url = `/api/router.php/roomReservation?id=${room_id}&fio=${fio.value}&booking_start=${start_date}&booking_end=${end_date}`;

      fetch(url, { method: "post" }).then();
    } catch {}

    const parent = document.querySelector(".list-rooms");
    const e_el = parent.querySelector(".bron-menu");
    parent.removeChild(e_el);
  });

  const btn_cancel = el.querySelector(".cancel");
  btn_cancel.addEventListener("click", () => {
    const parent = document.querySelector(".list-rooms");
    const e_el = parent.querySelector(".bron-menu");
    parent.removeChild(e_el);
  });
}

function get_dates_bron() {
  let arr = [];
  const list_rooms = document.querySelector(".list-rooms");
  const bron_menu = list_rooms.querySelector(".bron-menu");
  const days = bron_menu.querySelector(".days");

  const year = bron_menu.querySelector(".month-year").textContent.split(" ")[1];
  const month = months.indexOf(
    bron_menu.querySelector(".month-year").textContent.split(" ")[0],
  );

  const children = days.children;

  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your") {
      arr.push(new Date(year, month, children[i].textContent));
      break;
    }
  }

  for (let i = children.length - 1; i >= 0; i--) {
    if (children[i].className == "your") {
      arr.push(new Date(year, month, children[i].textContent));
      break;
    }
  }

  return arr;
}

const months = [
  "Январь",
  "Февраль",
  "Март",
  "Апрель",
  "Май",
  "Июнь",
  "Июль",
  "Август",
  "Сентябрь",
  "Октябрь",
  "Ноябрь",
  "Декабрь",
];

function setCalendar(year, month, el, room_id) {
  let prevlastday = new Date(year, month, 0);
  let lastday = new Date(year, month + 1, 0);
  let ulEl = el.querySelector(".days");

  const month_year = el.querySelector(".month-year");

  month_year.textContent = months[month] + " " + year;

  ulEl.innerHTML = ``;

  for (let i = prevlastday.getDay() - 1; i >= 0; i--) {
    let li = document.createElement("li");
    li.classList.add("not-active");
    let h3 = document.createElement("h3");
    h3.textContent = prevlastday.getDate() - i;
    li.appendChild(h3);
    ulEl.appendChild(li);
  }

  for (let i = 1; i <= lastday.getDate(); i++) {
    let li = document.createElement("li");
    let h3 = document.createElement("h3");
    h3.textContent = i;
    h3.addEventListener("click", () => {
      if (li.className == "") {
        li.className = "your";
        if (!line_your()) li.className = "";
      } else if (li.className == "your") {
        li.className = "";
        anti_line_your();
        clear_your(h3.textContent);
      }
      final_price();
    });
    li.appendChild(h3);
    ulEl.appendChild(li);
  }

  for (let i = 1; lastday.getDay() + i <= 7; i++) {
    let li = document.createElement("li");
    li.classList.add("not-active");
    let h3 = document.createElement("h3");
    h3.textContent = i;
    li.appendChild(h3);
    ulEl.appendChild(li);
  }

  const prev_btn = el.querySelector(".left-btn");
  prev_btn.addEventListener("click", () => {
    let new_date = new Date(year, month - 1),
      new_year = new_date.getFullYear(),
      new_month = new_date.getMonth();
    const el = document
      .querySelector(".list-rooms")
      .querySelector(".bron-menu");
    setCalendar(new_year, new_month, el, room_id);
  });

  const next_btn = el.querySelector(".right-btn");
  next_btn.addEventListener("click", () => {
    let new_date = new Date(year, month + 1),
      new_year = new_date.getFullYear(),
      new_month = new_date.getMonth();

    const el = document
      .querySelector(".list-rooms")
      .querySelector(".bron-menu");
    setCalendar(new_year, new_month, el, room_id);
  });

  set_closed(room_id);
}

function set_closed(room_id) {
  try {
    fetch(`/api/router.php/roomReservation?id=${room_id}`)
      .then((resp) => resp.json())
      .then((data) => {
        const list_rooms = document.querySelector(".list-rooms");
        const bron_menu = list_rooms.querySelector(".bron-menu");
        const days = bron_menu.querySelector(".days");
        const children = days.children;
        const now = new Date();

        const year = bron_menu
          .querySelector(".month-year")
          .textContent.split(" ")[1];
        const month = months.indexOf(
          bron_menu.querySelector(".month-year").textContent.split(" ")[0],
        );

        for (let i = 0; i < data["data"].length; i++) {
          const start_date = new Date(data["data"][i].booking_start);
          const end_date = new Date(data["data"][i].booking_end);

          const start_year = start_date.getFullYear();
          const start_month = start_date.getMonth();
          const start_day = start_date.getDate();

          const end_year = end_date.getFullYear();
          const end_month = end_date.getMonth();
          const end_day = end_date.getDate();

          for (let j = 0; j < children.length; j++) {
            if (children[j].className != "not-active") {
              if (
                start_day <= parseInt(children[j].textContent) &&
                parseInt(children[j].textContent) <= end_day &&
                start_year <= parseInt(year) &&
                parseInt(year) <= end_year &&
                start_month <= month &&
                month <= end_month
              )
                children[j].className = "closed";

              if (
                (parseInt(children[j].textContent) < now.getDate() &&
                  month <= now.getMonth() &&
                  parseInt(year) <= now.getFullYear()) ||
                parseInt(year) < now.getFullYear() ||
                month < now.getMonth()
              )
                children[j].classList.add("not-active");
            }
          }
        }
      });
  } catch {}
}

function line_your() {
  const list_rooms = document.querySelector(".list-rooms");
  const bron_menu = list_rooms.querySelector(".bron-menu");
  const days = bron_menu.querySelector(".days");
  const children = days.children;

  let y_count = 0;

  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your") y_count++;
  }

  if (y_count < 2) return true;

  let y_i_count = 0;
  let your = false;
  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your" && your) {
      y_i_count++;
    } else if (children[i].className == "your" && !your) {
      your = true;
      y_i_count++;
    } else if (children[i].className == "" && your)
      children[i].className = "your";
    else if (
      children[i].className != "your" &&
      children[i].className != "" &&
      your
    )
      return false;

    if (y_i_count == y_count) return true;
  }

  return true;
}

function anti_line_your() {
  const list_rooms = document.querySelector(".list-rooms");
  const bron_menu = list_rooms.querySelector(".bron-menu");
  const days = bron_menu.querySelector(".days");
  const children = days.children;

  let y_count = 0;

  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your") y_count++;
  }
  if (y_count < 2) return true;

  let arr = [];

  let your = false;
  for (let i = children.length - 1; i >= 0; i--) {
    if (children[i].className == "" && your) {
      your = false;
      children[i].className = "your";
    } else if (children[i].className == "your" && your) {
      arr.push(children[i]);
    } else if (children[i].className == "your" && !your) {
      if (arr.length > 0) {
        arr.forEach((child) => {
          child.className = "";
        });
        return true;
      }
      arr.push(children[i]);
      your = true;
    } else if (
      children[i].className != "your" &&
      children[i].className != "" &&
      your
    )
      return false;
  }

  return true;
}

function clear_your(number_day) {
  const list_rooms = document.querySelector(".list-rooms");
  const bron_menu = list_rooms.querySelector(".bron-menu");
  const days = bron_menu.querySelector(".days");
  const children = days.children;

  let first = false;
  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your") {
      if (children[i].textContent == number_day) first = true;
      break;
    }
  }

  if (first)
    for (let i = 0; i < children.length; i++) {
      if (children[i].className == "your") children[i].className = "";
    }
}

function final_price() {
  const list_rooms = document.querySelector(".list-rooms");
  const bron_menu = list_rooms.querySelector(".bron-menu");
  const one_price = bron_menu.querySelector(".one-price");
  const price = parseFloat(one_price.textContent);
  const final_price = bron_menu.querySelector(".final-price");
  const days = bron_menu.querySelector(".days");
  const children = days.children;

  let count = 0;

  for (let i = 0; i < children.length; i++) {
    if (children[i].className == "your") count++;
  }

  final_price.textContent = count * price;
}

// Buttons clickabled
// sort menu
const sort = document.querySelector(".sort");
sort.addEventListener("click", () => {
  document.querySelector(".sort-menu").style.display = "flex";
});

const sort_menu = document.querySelector(".sort-menu");
const sort_price_up = sort_menu.querySelector(".sort-price__up");
sort_price_up.addEventListener("click", () => {
  sort_params = "price_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_price_down = sort_menu.querySelector(".sort-price__down");
sort_price_down.addEventListener("click", () => {
  sort_params = "price_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_capacity_up = sort_menu.querySelector(".sort-capacity__up");
sort_capacity_up.addEventListener("click", () => {
  sort_params = "capacity_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_capacity_down = sort_menu.querySelector(".sort-capacity__down");
sort_capacity_down.addEventListener("click", () => {
  sort_params = "capacity_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_type_up = sort_menu.querySelector(".sort-type__up");
sort_type_up.addEventListener("click", () => {
  sort_params = "type_up";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_type_down = sort_menu.querySelector(".sort-type__down");
sort_type_down.addEventListener("click", () => {
  sort_params = "type_down";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

const sort_reboot = sort_menu.querySelector(".sort-reboot");
sort_reboot.addEventListener("click", () => {
  sort_params = "";
  increment_rooms(url_constructor());
  sort_menu.style.display = "none";
});

// filter menu
const filter_menu = document.querySelector(".filter-menu");

const filter = document.querySelector(".filter");
filter.addEventListener("click", () => {
  filter_menu.style.display = "flex";
});

const filter_combobox = filter_menu.querySelector(".combobox");
const filter_price_min = filter_menu.querySelector(".price-min");
const filter_price_max = filter_menu.querySelector(".price-max");
const filter_capacity = filter_menu.querySelector(".capacity-count");

const filter_reboot = filter_menu.querySelector(".reboot");
filter_reboot.addEventListener("click", () => {
  filter_params = {
    type: "",
    prices: "",
    capacity: "",
  };
  increment_rooms(url_constructor());
  filter_menu.style.display = "none";
});

const filter_find = filter_menu.querySelector(".find");
filter_find.addEventListener("click", () => {
  if (filter_combobox.value == "all") filter_params["type"] = "";
  else filter_params["type"] = filter_combobox.value;

  if (filter_price_min.value != "" && filter_price_max.value != "")
    filter_params["prices"] =
      filter_price_min.value + "-" + filter_price_max.value;
  else filter_params["prices"] = "";

  if (filter_capacity.value != "")
    filter_params["capacity"] = filter_capacity.value;
  else filter_params["capacity"] = "";

  increment_rooms(url_constructor());
  filter_menu.style.display = "none";
});

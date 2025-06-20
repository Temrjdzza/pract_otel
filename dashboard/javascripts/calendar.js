let date = new Date(),
  year = date.getFullYear(),
  month = date.getMonth();

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

function setCalendar(year, month) {
  let prevlastday = new Date(year, month, 0);
  let lastday = new Date(year, month + 1, 0);
  let ulEl = document.querySelector(".days");

  const month_year = document.querySelector(".month-year");

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
}

setCalendar(year, month);

const prev_btn = document.querySelector(".left-btn");
prev_btn.addEventListener("click", () => {
  date = new Date(year, month - 1);
  year = date.getFullYear();
  month = date.getMonth();
  setCalendar(year, month);
});

const next_btn = document.querySelector(".right-btn");
next_btn.addEventListener("click", () => {
  date = new Date(year, month + 1);
  year = date.getFullYear();
  month = date.getMonth();
  setCalendar(year, month);
});

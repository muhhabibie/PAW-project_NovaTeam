function toggleMenu() {
  const menu = document.getElementById('dropdownMenu');
  menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

document.addEventListener('click', function (event) {
  const menu = document.getElementById('dropdownMenu');
  const icon = document.querySelector('.menu-icon');

  if (!menu.contains(event.target) && !icon.contains(event.target)) {
    menu.style.display = 'none';
  }
});

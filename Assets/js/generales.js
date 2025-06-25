const modoNocheBtn = document.getElementById('theme-toggle');
const actualModo = localStorage.getItem('theme') || 'light';
const temaPagina = document.body;

let isDark = true;

if(actualModo == "dark"){
    temaPagina.classList.add('dark-theme');
    modoNocheBtn.innerHTML = '<i class="ri-sun-fill"></i>';
}else{
    modoNocheBtn.innerHTML = '<i class="ri-moon-line"></i>';
}


function themeMode(){
    isDark = !isDark;
    isDark ? modoNocheBtn.innerHTML = '<i class="ri-moon-line"></i>' : modoNocheBtn.innerHTML = '<i class="ri-sun-fill"></i>';
    temaPagina.classList.toggle('dark-theme');

    let theme = "light";
    if(temaPagina.classList.contains('dark-theme')){
        theme = "dark";
    }
    localStorage.setItem("theme", theme);

    console.log(localStorage.getItem('theme'));
}

modoNocheBtn.addEventListener('click', themeMode);

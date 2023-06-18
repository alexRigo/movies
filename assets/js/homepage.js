import '../styles/homepage.scss'
/* import './styles/filters.scss' */

document.addEventListener('DOMContentLoaded', () => {
    init();
});

function init() {
    showFilters();
    hideFilters();
}

function showFilters() {
    const showFilters = document.querySelector("#show-filters");
    const filtersSection = document.querySelector("#filters");
    const moviesSection = document.querySelector("#movies");

    showFilters.addEventListener("click", () => {
        filtersSection.style.display = "block";
        moviesSection.style.display = "none"; 
        showFilters.style.display = "none";
    })
}

function hideFilters() {
    const showFilters = document.querySelector("#show-filters");
    const filtersSection = document.querySelector("#filters");
    const moviesSection = document.querySelector("#movies");
    const closeFilters = document.querySelector(".close-filters");
    
    closeFilters.addEventListener("click", () => {
        filtersSection.style.display = "none";
        moviesSection.style.display = "block"; 
        showFilters.style.display = "flex";
    })
}



/* const movieCards = document.querySelectorAll('.film-card');

movieCards.forEach(movieCard => {
    movieCard.addEventListener('mouseover', (event) => {
        const filmInfos = event.currentTarget.querySelector('.film-infos');
        console.log("mouseover")
        
        setTimeout(() => {
            filmInfos.classList.add("visible")
        }, 200)
    })
    movieCard.addEventListener('mouseout', (event) => {
        const filmInfos = event.currentTarget.querySelector('.film-infos');
        console.log("mouseover")
        setTimeout(() => {
            filmInfos.classList.remove("visible")
        }, 200)
        
      });
}); */



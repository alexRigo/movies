import '../styles/addFilmToPlaylist.scss';

document.addEventListener('DOMContentLoaded', () => {
	showFilms();
});

function showFilms() {
	let showMovies = document.querySelectorAll(".selection-show-films");

	showMovies.forEach(showMovie => {
		showMovie.addEventListener('click', () => {
			const target = showMovie.getAttribute('data-show')
			const listMovies = document.getElementById(target);
			listMovies.classList.toggle("hidden");
		})
	})
}
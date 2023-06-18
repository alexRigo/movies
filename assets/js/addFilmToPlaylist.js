import '../styles/addFilmToPlaylist.scss'; 

document.addEventListener('DOMContentLoaded', () => {
	init();
});

window.addEventListener('load', function() {
	if (document.querySelector('.message').textContent.trim().length > 0) {
		const overlay = document.querySelector('.overlay');
		overlay.style.display = "block";
	}
  });

function init() {
	openPopup();
	closePopup();
	addFilmToPlaylist(); 
	deleteFilmFromPlaylist();
}

function addFilmToPlaylist() {
	const selectionsForm = document.querySelectorAll('.selection-form');
	
	selectionsForm.forEach(selectionForm => {		
		selectionForm.addEventListener('submit', function(e) {
			e.preventDefault();
			document.querySelector('.message-error').innerHTML = "";
			const url = selectionForm.action;
			const formData = new FormData(selectionForm); 
		console.log(url)
			fetch(url, {
				method: 'POST',
				body: formData
			}).then(response => {
				if (response.ok) {
					return response.json();
				}
				throw new Error('Une erreur est survenue.');
			}).then(data => {
				console.log(data); 
				let selectionId = data.data;
				if(data.success == true) {
					let movieCount = document.querySelector(`[data-selection="${selectionId}"]`);
					movieCount.textContent = parseInt(movieCount.textContent) + 1;
					const filmName = data.filmName;
					const filmPoster = data.filmPoster;
					
					var parentDiv = document.querySelector('[id^="selection-films' + selectionId + '"]');
					
					// Créer un nouvel élément div avec la classe 'selection-film'
					var newDiv = document.createElement('div');
					newDiv.classList.add('selection-film');
					
					// Créer un nouvel élément img avec la classe 'selection-film-poster' et l'attribut 'src'
					var newImg = document.createElement('img');
					newImg.classList.add('selection-film-poster');
					newImg.setAttribute('src', filmPoster);

					// Créer un nouvel élément p avec la classe 'selection-film-title' et le contenu textuel
					var newP = document.createElement('p');
					newP.classList.add('selection-film-title');
					newP.textContent = filmName;

					// Ajouter les éléments img et p comme enfants de la nouvelle div
					newDiv.appendChild(newImg);
					newDiv.appendChild(newP);
					
				
					// Ajouter la nouvelle div comme enfant de l'élément parent existant
					parentDiv.appendChild(newDiv);

					
				} else if (data.success == false) {
					let message = "Le film est déjà présent dans la playlist";
					document.querySelector('.message-error').innerHTML = message;
					 
				}
			
			}).catch(error => {
				console.error(error);
			
			});
		});
	});

}

function deleteFilmFromPlaylist() {

	const removeFilmForms = document.querySelectorAll('.remove-film-form');
	
	removeFilmForms.forEach(removeFilmForm => {	
		removeFilmForm.addEventListener('submit', function(e) {
			e.preventDefault();
			const url = removeFilmForm.action;
			const formData = new FormData(removeFilmForm); 
		
			fetch(url, {
				method: 'POST',
				body: formData
			}).then(response => {
				if (response.ok) {
					return response.json();
				}
				throw new Error('Une erreur est survenue.');
			}).then(data => {
			
			    let filmId = data.filmId; 
				const selectionId = data.selectionId;
	
				if(data.success == true) {
					let filmSection = document.querySelector(`#film-${filmId}`);
					filmSection.remove();

					let selectionFilmCount = document.querySelector(`[data-selection="${selectionId}"]`);
					selectionFilmCount.textContent--;		
				} else if (data.success == false) {
					let message = "Impossible de supprimer le film";
					document.querySelector('.message-error').innerHTML = message; 	 
				}
			}).catch(error => {
				console.error(error);
			
			});
		});
	});
}

function openPopup() {
	const openPopup = document.querySelector('#open-popup');
	const popup = document.querySelector('#popup-selection');
	const overlay = document.querySelector('.overlay');
	openPopup.addEventListener("click", () => {
		popup.style.display = "block";
		overlay.style.display = "block";
	})
}

function closePopup() {
	const closePopups = document.querySelectorAll('.close-popup');
	const popups = document.querySelectorAll('.popup');
	const overlay = document.querySelector('.overlay');
	closePopups.forEach(closePopup => {
		closePopup.addEventListener("click", () => {
			popups.forEach(popup => {
				popup.style.display = "none";
			})
			
			overlay.style.display = "none";
		})
	})
}

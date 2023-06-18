import Flickity from 'flickity';
document.addEventListener('DOMContentLoaded', function() {
    let flkty = new Flickity('.carousel', {
        'wrapAround': true,
        'groupCells': true,
        'cellAlign': 'left',
        'pageDots': true
       
    });

    let dots = document.querySelectorAll('.flickity-page-dot');
    console.log(dots)
    dots.forEach(dot => {
        dot.innerHTML = '';
    })
 
    window.dispatchEvent(new Event('resize'));
    
  });
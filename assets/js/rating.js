window.onload = () => {
    const stars = document.querySelectorAll(".star");
    const rating = document.querySelector("#critic_note");
    let hoveredStars = [];

    for(star of stars){
        star.addEventListener("mouseover", function(){ 
            resetStars();

            this.src = "/build/images/star-full.svg";
            hoveredStars.push(this);

            let previousStar = this.previousElementSibling;
            while(previousStar){
                previousStar.src = "/build/images/star-full.svg";
                hoveredStars.push(previousStar);
                previousStar = previousStar.previousElementSibling;
            }
        });

        star.addEventListener("click", function() {
            rating.value = this.dataset.value;
        });

        star.addEventListener("mouseout", function() {
            resetStars();
        });
    }

    function resetStars() {
        for(star of stars){
            if (!hoveredStars.includes(star)) {
                star.src = "/build/images/star-empty.svg";
            } else {
                star.src = "/build/images/star-full.svg";
            }
        }

        hoveredStars = [];
    }
}

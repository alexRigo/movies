document.addEventListener('DOMContentLoaded', () => {
    const expandsMore = document.querySelectorAll('[expand-more]')
    const textCritics = document.querySelectorAll('.expandMoreContent')

    textCritics.forEach(critic => {
        console.log(critic.clientHeight)
    
        if (critic.clientHeight < 90){
            console.log(document.querySelector('.btn-expand-more'))
            critic.nextElementSibling.nextElementSibling.remove()
        } 
    }); 

    function expand() {
        const showContent = document.getElementById(this.dataset.target)
    
        if (showContent.classList.contains('expand-active')){
            this.innerHTML = this.dataset.showtext
        } else {
            this.innerHTML = this.dataset.hidetext
        }
        showContent.classList.toggle('expand-active')
    }

    expandsMore.forEach(expandMore => {
        expandMore.addEventListener('click', expand)
    });
})

